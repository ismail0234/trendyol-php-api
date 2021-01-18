<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Database;

Class WebhookService extends Database
{

	/**
	 *
	 * Trendyol Üzerinde yeni siparişlerin sorgulanacağı aralık (saniye)
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int 
	 *
	 */
	protected $requestTime = 180;

	/**
	 *
	 * Son yapılan istek zamanı
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int 
	 *
	 */
	protected $requestEndTime;

	/**
	 *
	 * İlk başlama zamanı
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int 
	 *
	 */
	protected $startedTime;

	/**
	 *
	 * Sipariş listesinden kaç adet sipariş getirileceği.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int 
	 *
	 */
	protected $orderMaxResult = 50;

	/**
	 *
	 * İlk kurulum için geçerli geçmiş tarih
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int 
	 *
	 */
	protected $firstSetupDate;

	/**
	 *
	 * 1 Yıl için sabit değer
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int
	 *
	 */
	const ONE_YEAR = 86400 * 365;

	/**
	 *
	 * 2 Hafta için sabit değer
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var int
	 *
	 */
	const TWO_WEEK = 86400 * 14;

	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function __construct($supplierId, $username, $password,$testmode)
	{
		$this->startedTime    = time();
		$this->requestEndTime = time();
		$this->firstSetupDate = time() - self::ONE_YEAR;
		$this->order          = new OrderService($supplierId, $username, $password,$testmode);
		parent::__construct($this->firstSetupDate);
	}

	/**
	 *
	 * Trendyol üzerinden gelen yeni siparişleri tüketir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param Class $client
	 *
	 */
	public function orderConsume($work)
	{

		while (true) 
		{
			if (time() >= $this->requestEndTime)
			{

				$this->requestEndTime = time() + $this->requestTime;
				$this->setting        = $this->selectSettings();
				foreach ($this->getDateBetweenWeeks($this->setting->lastOrderDate) as $date) 
				{
					$orderList = $this->getOrderList($date['startDate'], $date['endDate']);
					$this->callEvent($orderList, $work);

					for ($pageId = 1; $pageId < $orderList['maxPage']; $pageId++) 
					{ 
						$orderList = $this->getOrderList($date['startDate'], $date['endDate'], $pageId);
						$this->callEvent($orderList, $work);
					}

					$this->updateStartDate($date['startDate']);
				}

			}

			sleep(1);
		}

	}

	/**
	 *
	 * Başlangıç tarihinden itibaren şimdiki zamana kadar haftaları listeler.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $startDate
	 * @return array 
	 *
	 */
	protected function getDateBetweenWeeks($startDate)
	{

		$maxWeek = ceil((time() - $startDate) / self::TWO_WEEK);
		if ($maxWeek <= 1) {
			return array(array('startDate' => time() - self::TWO_WEEK, 'endDate' => time()));
		}
	
		if ($maxWeek > 20) {
			$maxWeek = 20;
		}

		$responseList = array();
		for ($i = 0; $i < $maxWeek; $i++) {
			$endDate        = $startDate + self::TWO_WEEK;
			$responseList[] = array('startDate' => $startDate, 'endDate' => $endDate);
			$startDate      += self::TWO_WEEK;
		}

		return $responseList;
	}

	/**
	 *
	 * Trenyol siparişlerini getirir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $startDate
	 * @param int $endDate
	 * @param int $pageId = 0
	 * @return array 
	 *
	 */
	protected function getOrderList($startDate, $endDate, $pageId = 0)
	{
		$orderList = $this->order->orderList(array(
			'orderByField'     => 'CreatedDate',
			'orderByDirection' => 'DESC',
			'page'             => $pageId,
			'size'             => $this->orderMaxResult,
			'startDate'        => $startDate,
			'endDate'          => $endDate
		));

		$orderResponseList = array();
		if (isset($orderList->content) && count($orderList->content) > 0) {
			$orderResponseList = $orderList->content;
		}

		return array('maxPage' => $orderList->totalPages, 'datas' => $orderResponseList);
	}

	/**
	 *
	 * Yeni siparişleri kancalar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $orderList
	 * @param Class $work
	 *
	 */
	protected function callEvent($orderList, $work)
	{
		foreach ($orderList['datas'] as $order) 
		{
			$dborder = $this->selectOrder($order->orderNumber);
			if (isset($dborder->orderid)) {
				continue;
			}

			$this->addOrder($order->orderNumber);

			if (is_array($work)) {
				call_user_func_array(array($work[0], $work[1]), array($order));
			}else{
				$work($order);
			}

			$this->finishOrder($order->orderNumber);
		}
	}

	/**
	 *
	 * Trendyol Siparişlerinin en kadar hızlı tüketileceği.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $mode  
	 *
	 */
	public function setRequestMode($mode)
	{

		switch ($mode) 
		{
			case 'slow'  : $this->requestTime = 300; break;
			case 'fast'  : $this->requestTime = 60; break;
			case 'vfast' : $this->requestTime = 30; break;
			case 'medium': 
			default:
				$this->requestTime = 180;
			break;
		}

	}

	/**
	 *
	 * Trendyol Sipariş listesinde kaç adet siparişin getirileceği
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $mode 
	 *
	 */
	public function setResultMode($mode)
	{

		switch ($mode) 
		{
			case 'vmax'  	: $this->orderMaxResult = 200; break;
			case 'max' 		: $this->orderMaxResult = 150; break;
			case 'min'		: $this->orderMaxResult = 50; break;
			case 'medium'	: 
			default:
				$this->orderMaxResult = 100;
			break;
		}

	}

}

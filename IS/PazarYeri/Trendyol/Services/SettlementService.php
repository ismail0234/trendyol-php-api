<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;
use IS\PazarYeri\Trendyol\Helper\TrendyolException;

Class SettlementService extends Request
{
    /**
	 *
	 * Default API Url Adresi
	 *
	 * @author Özer Özdaş <ozer@ozdas.org>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/integration/finance/che/sellers/{supplierId}/settlements';
    
	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Özer Özdaş <ozer@ozdas.org>
	 *
	 */
	public function __construct($supplierId, $username, $password, $testmode)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password, $testmode);
	}

    /**
	 *
	 * Trendyol sisteminde oluşan muhasebesel kayıtlarınızı bu servis aracılığı ile entegrasyon üzerinden çekebilirsiniz.
	 *
	 * @author Özer Özdaş <ozer@ozdas.org>
	 * @return array 
	 *
	 */
	public function getSettlements($data = array())
	{
		$query = array(
			'transactionType'   => array('required' => array("Sale", "Return", "Discount", "DiscountCancel", "Coupon", "CouponCancel", "ProvisionPositive", "ProvisionNegative")),
			'startDate'         => array('required' => array('format' => 'unixTime')),
			'endDate'           => array('required' => array('format' => 'unixTime')),
			'page'              => '',
			'size'              => '',
			'supplierId'        => array('required' => ''),
		);
		$this->setApiUrl($this->apiUrl);
		return $this->getResponse($query, $data);
	}
}
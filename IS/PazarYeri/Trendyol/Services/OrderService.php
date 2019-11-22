<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;

Class OrderService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/suppliers/{supplierid}/orders';

	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function __construct($supplierId, $username, $password)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password);
	}

	/**
	 *
	 * Trendyol üzerinde siparişleri arar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $degisken
	 * @return string 
	 *
	 */
	public function orderList($data = array())
	{

		$query = array(
			'startDate'          => array('format' => 'unixTime'),
			'endDate'            => array('format' => 'unixTime'),
			'page'               => '',
			'size'               => '',
			'orderNumber'        => '',
			'status'             => array('required' => array('Created', 'Picking', 'Invoiced', 'Shipped', 'Cancelled', 'Delivered', 'UnDelivered', 'Returned', 'Repack', 'UnSupplied')),
			'orderByField'       => array('required' => array('PackageLastModifiedDate', 'CreatedDate')),
			'orderByDirection'   => array('required' => array('ASC', 'DESC')),
			'shipmentPackagesId' => '',
		);

		return $this->getResponse($query, $data);
	}
	

}
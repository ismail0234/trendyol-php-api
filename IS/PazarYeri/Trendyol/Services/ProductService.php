<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;

Class ProductService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/suppliers/{supplierId}/products';

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
	 * Trendyol üzerindeki ürünleri filtrelemek için kullanılır.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @return array 
	 *
	 */
	public function filterProducts($data = array())
	{

		$query = array(
			'approved'      => '',
			'barcode'       => '',
			'startDate'     => array('format' => 'unixFormat'),
			'endDate'       => array('format' => 'unixFormat'),
			'page'          => '',
			'dateQueryType' => array('required' => array('CREATED_DATE' , 'LAST_MODIFIED_DATE')),
			'size'          => '',
			'supplierId'    => ''
		);

		return $this->getResponse($query, $data);
	}
}


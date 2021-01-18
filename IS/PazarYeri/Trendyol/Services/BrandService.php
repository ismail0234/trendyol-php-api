<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;

Class BrandService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/brands';

	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function __construct($supplierId, $username, $password,$testmode)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password, $testmode);
	}

	/**
	 *
	 * createProduct servisine yapılacak isteklerde gönderilecek brandId bilgisi bu servis kullanılarak alınacaktır.
	 * Bir sayfada en fazla 500 adet brand bilgisi alınabilmektedir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $degisken
	 * @return string 
	 *
	 */
	public function getBrands($size = 100, $pageId = 0)
	{
		$this->setApiUrl($this->apiUrl);
		return $this->getResponse(true, array('page' => $pageId, 'size' => $size), false);
	}

	/**
	 *
	 * Marka araması yapmak için kullanılır.
	 * BÜYÜK / küçük harf ayrımına dikkat etmelisiniz.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $degisken
	 * @return string 
	 *
	 */
	public function getBrandByName($brandName)
	{
		$this->setApiUrl($this->apiUrl . '/by-name');
		return $this->getResponse(true, array('name' => $brandName), false);
	}

}

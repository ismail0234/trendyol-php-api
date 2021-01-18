<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;

Class CategoryService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/product-categories';

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
	 * Trendyol üzerindeki bütün kategorileri getirir.
	 * createProduct servisine yapılacak isteklerde gönderilecek categoryId
	 * bilgisi bu servis kullanılarak alınacaktır.
	 * 
	 * createProduct yapmak için en alt seviyedeki kategori ID bilgisi kullanılmalıdır. 
	 * Seçtiğiniz kategorinin alt kategorileri var ise bu kategori bilgisi ile ürün aktarımı yapamazsınız.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @return array 
	 *
	 */
	public function getCategoryTree()
	{
		$this->setApiUrl($this->apiUrl);
		return $this->getResponse(true, true, false);
	}

	/**
	 *
	 * Trendyol üzerindeki kategorinin özelliklerini döndürür.
	 * createProduct servisine yapılacak isteklerde gönderilecek attributes bilgileri 
	 * ve bu bilgilere ait detaylar bu servis kullanılarak alınacaktır.
	 * 
	 * createProduct yapmak için en alt seviyedeki kategori ID bilgisi kullanılmalıdır. 
	 * Seçtiğiniz kategorinin alt kategorileri var ise (leaf:true) bu kategori bilgisi ile ürün aktarımı yapamazsınız.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $categoryId
	 * @return array 
	 *
	 */
	public function getCategoryAttributes($categoryId)
	{
		$this->setApiUrl($this->apiUrl . '/{categoryId}/attributes');
		return $this->getResponse(true, array('categoryId' => $categoryId), false);
	}

}

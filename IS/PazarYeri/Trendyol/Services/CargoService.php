<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;

Class CargoService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = '';

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
	 * Trendyol üzerindeki bütün kargo şirketlerini getirir.
     *
	 * createProduct servisine yapılacak isteklerde gönderilecek kargo firma bilgileri 
	 * ve bu bilgilere ait ID değerleri bu servis kullanılarak alınacaktır.
	 *
	 * Ürün gönderimi yaparken gönderdiğiniz kargo şirketleri, Trendyol sözleşmenizde 
	 * onayladığınız kargo firmasından farklı olmamalıdır. Bu durum ürünlerinizi yayına çıkmasını engelleyecektir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @return array 
	 *
	 */
	public function getProviders()
	{
		$this->setApiUrl('https://api.trendyol.com/sapigw/shipment-providers');
		return $this->getResponse(true, true, false);
	}

	/**
	 *
	 * Trendyol üzerindeki tedarikçi adreslerinizi getirir.
     *
	 * createProduct V2 servisine yapılacak isteklerde gönderilecek sipariş ve sevkiyat kargo 
	 * firma bilgileri ve bu bilgilere ait ID değerleri bu servis kullanılarak alınacaktır. 
	 *
	 * "SATICI BAŞVURU SÜRECİM" tam olarak tamamlanmadı ise bu servisi kullanmamanız gerekir.
	 *
	 * Ürün gönderimi yaparken adresi ID değerlerini kontrol etmelisiniz. Hatalı gönderim 
	 * yapılması halinde ürün aktarımı gerçekleşmeyecektir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @return array 
	 *
	 */
	public function getSuppliersAddresses()
	{
		$this->setApiUrl('https://api.trendyol.com/sapigw/suppliers/{supplierId}/addresses');
		return $this->getResponse(true, true);
	}

}
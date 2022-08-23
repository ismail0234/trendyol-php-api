<?php

namespace IS\PazarYeri\Trendyol\Services;

use IS\PazarYeri\Trendyol\Helper\Request;
use IS\PazarYeri\Trendyol\Helper\TrendyolException;

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
	public function __construct($supplierId, $username, $password,$testmode)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password, $testmode);
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
			'startDate'     => array('format' => 'unixTime'),
			'endDate'       => array('format' => 'unixTime'),
			'page'          => '',
			'dateQueryType' => array('required' => array('CREATED_DATE' , 'LAST_MODIFIED_DATE')),
			'size'          => ''
		);

		return $this->getResponse($query, $data);
	}

    /**
     * Bu servis kullanılarak gönderilen ürünler Trendyol.com'da daha hızlı yayına alınmaktadır. (Ürün Aktarımı v2)
     *
     *
     * @param array $data
     * @return array
     * @throws TrendyolException
     */
    public function createProducts($data = array())
    {
        $this->setApiUrl('https://api.trendyol.com/sapigw/suppliers/{supplierId}/v2/products');
        $this->setMethod("POST");
        $query = array(
            'items'=> array(
                'barcode'               => '',
                'title'                 => '',
                'productMainId'         => '',
                'brandId'               => '',
                'categoryId'            => '',
                'quantity'              => '',
                'stockCode'             => '',
                'dimensionalWeight'     => '',
                'description'           => '',
                'currencyType'          => '',
                'listPrice'             => '',
                'salePrice'             => '',
                'cargoCompanyId'        => '',
                'deliveryDuration'      => '',
                'images'                => '',
                'vatRate'               => '',
                'shipmentAddressId'     => '',
                'returningAddressId'    => '',
                'attributes'            => ''
            )
        );

        return $this->getResponse($query, $data);
    }

    /**
     * Ürün bilgilerini güncellemek için kullanılır. (Fiyat ve stok güncellemek için updatePriceAndInventory fonksiyonu kullanılmalıdır)
     *
     *
     * @param array $data
     * @return array
     * @throws TrendyolException
     */
    public function updateProducts($data = array())
    {
        $this->setApiUrl('https://api.trendyol.com/sapigw/suppliers/{supplierId}/v2/products');
        $this->setMethod("PUT");
        $query = array(
            'items'=> array( 
                'barcode'               => '',
                'title'                 => '',
                'productMainId'         => '',
                'brandId'               => '',
                'categoryId'            => '',
                'quantity'              => '',
                'stockCode'             => '',
                'dimensionalWeight'     => '',
                'description'           => '',
                'currencyType'          => '',
                'listPrice'             => '',
                'salePrice'             => '',
                'cargoCompanyId'        => '',
                'deliveryDuration'      => '',
                'images'                => '',
                'vatRate'               => '',
                'shipmentAddressId'     => '',
                'returningAddressId'    => '',
                'attributes'            => ''
            ),
        );

        return $this->getResponse($query, $data);
    }

    /**
     * Trendyol'a aktarılan ve onaylanan ürünlerin fiyat ve stok bilgileri eş zamana yakın güncellenir. Stok ve fiyat bligilerini istek içerisinde ayrı ayrı gönderebilirsiniz.
     *
     * @param array $data
     * @return array
     * @throws TrendyolException
     */
    public function updatePriceAndInventory(array $data = array())
    {

        $this->setApiUrl('https://api.trendyol.com/sapigw/suppliers/{supplierId}/products/price-and-inventory');
        $this->setMethod("POST");
        $query = array(
            'items'=> array(
                'barcode'               => '',
                'quantity'              => '',
                'listPrice'             => '',
                'salePrice'             => '',
            )
        );
        return $this->getResponse($query, $data);
    }

    /**
     * Bu method yardımıyla batchRequestId ile alınan işlemlerin sonucunun kontrolü yapılabilir.
     *
     * @param string $batchRequestId
     * @return array
     * @throws TrendyolException
     */
    public function getBatchRequestResult($batchRequestId)
    {
        $this->setApiUrl('https://api.trendyol.com/sapigw/suppliers/{supplierId}/products/batch-requests/{batchRequestId}');

        return $this->getResponse(true, array('batchRequestId' => $batchRequestId));
    }
}


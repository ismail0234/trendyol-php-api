[![Latest Stable Version](https://poser.pugx.org/ismail0234/trendyol-php-api/v/stable)](https://packagist.org/packages/ismail0234/trendyol-php-api)
[![Total Downloads](https://poser.pugx.org/ismail0234/trendyol-php-api/downloads)](https://packagist.org/packages/ismail0234/trendyol-php-api)
[![License](https://poser.pugx.org/ismail0234/trendyol-php-api/license)](https://packagist.org/packages/ismail0234/trendyol-php-api)

# Trendyol PHP Api

Bu api trendyol için yazılmıştır. Trendyol pazaryeri için yazılmış olan gelişmiş bir php apisi. Ekstra olarak trendyol üzerinde mağazanıza gelen siparişleri websitenize aktaracak bir fonksiyonda mevcuttur.

## Katkı Çağrısı

Çok fazla vaktim olmadığından Trendyolun bütün api fonksiyonları tamamlanmamıştır. Eksik fonksiyonları isterseniz tamamlayarak **pull request** gönderebilirsiniz veya istediğiniz fonksiyonun eklenmesi için **issue** açabilirsiniz. 

### Change Log
- See [ChangeLog](https://github.com/ismail0234/trendyol-php-api/blob/master/CHANGELOG.md)

### Licence
- See [ChangeLog](https://github.com/ismail0234/trendyol-php-api/blob/master/LICENCE)

## Hızlı Bakış
 * [Kurulum](#kurulum)
 * [Kullanım](#kullanım)
 * [Marka Servisi (Brand Service)](#marka-servisi-brand-service)
 * [Kargo Servisi (Cargo Service)](#kargo-servisi-cargo-service)
 * [Kategori Servisi (Category Service)](#kategori-servisi-category-service)
 * [Ürün Servisi (Product Service)](#ürün-servisi-product-service)
 * [Sipariş Servisi (Order Service)](#sipariş-servisi-order-service)
 * [Trendyol Sipariş Bildirimi WebHook (Trendyol Order WebHook)](#trendyol-sipariş-bildirimi-webhook-trendyol-order-webhook)
 
 ## Kurulum

Kurulum için composer kullanmanız gerekmektedir. Composer'a sahip değilseniz windows için [Buradan](https://getcomposer.org/) indirebilirsiniz.

```php

composer require ismail0234/trendyol-php-api

```

## Kullanım

```php

include "vendor/autoload.php";

use IS\PazarYeri\Trendyol\TrendyolClient;
use IS\PazarYeri\Trendyol\Helper\TrendyolException;

$trendyol = new TrendyolClient(); 
$trendyol->setSupplierId(100000);
$trendyol->setUsername("xxxxxxxxxxxxxxxxxxxx");
$trendyol->setPassword("xxxxxxxxxxxxxxxxxxxx");

```

### Marka Servisi (Brand Service)

```php
/**
 *
 * createProduct servisine yapılacak isteklerde gönderilecek brandId bilgisi bu servis kullanılarak alınacaktır.
 * Bir sayfada en fazla 500 adet brand bilgisi alınabilmektedir.
 *
 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
 * @param int $size
 * @param int $pageId
 * @return array 
 *
 */
$trendyol->brand->getBrands(100, 0);

/**
 *
 * Marka araması yapmak için kullanılır.
 * BÜYÜK / küçük harf ayrımına dikkat etmelisiniz.
 *
 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
 * @param string $brandName
 * @return array 
 *
 */
$trendyol->brand->getBrandByName("Milla");
```

### Kargo Servisi (Cargo Service)

```php
/**
 *
 * Trendyol üzerindeki bütün kargo şirketlerini getirir.
 *
 * createProduct V2 servisine yapılacak isteklerde gönderilecek kargo firma bilgileri 
 * ve bu bilgilere ait ID değerleri bu servis kullanılarak alınacaktır.
 *
 * Ürün gönderimi yaparken gönderdiğiniz kargo şirketleri, Trendyol sözleşmenizde 
 * onayladığınız kargo firmasından farklı olmamalıdır. Bu durum ürünlerinizi yayına çıkmasını engelleyecektir.
 *
 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
 * @return array 
 *
 */
$trendyol->cargo->getProviders();

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
$trendyol->cargo->getSuppliersAddresses();
```

### Kategori Servisi (Category Service)

```php
/**
 *
 * Trendyol üzerindeki bütün kategorileri getirir.
 * createProduct V2 servisine yapılacak isteklerde gönderilecek categoryId
 * bilgisi bu servis kullanılarak alınacaktır.
 * 
 * createProduct yapmak için en alt seviyedeki kategori ID bilgisi kullanılmalıdır. 
 * Seçtiğiniz kategorinin alt kategorileri var ise bu kategori bilgisi ile ürün aktarımı yapamazsınız.
 *
 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
 * @return array 
 *
 */
$trendyol->category->getCategoryTree();

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
$trendyol->category->getCategoryAttributes(411);
```

### Ürün Servisi (Product Service)

```php
/**
 *
 * Trendyol üzerindeki ürünleri filtrelemek için kullanılır.
 *
 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
 * @note İsteğe bağlı olarak dizideki alanların istenilen bölümleri eklenmeyebilir veya dizi hiç gönderilmeyebilir.
 * @return array 
 *
 */
$trendyol->product->filterProducts(
	array(
		// Ürün onaylı ya da onaysız kontrolü için kullanılır. Onaylı için true gönderilmelidir	
		'approved'      => true,
		// Tekil barkod sorgulamak için gönderilmelidir	
		'barcode'       => '',
		// Belirli bir tarihten sonraki ürünleri getirir. Timestamp olarak gönderilmelidir.	
		'startDate'     => time() - (86400 * 7),
		//Belirli bir tarihten sonraki önceki getirir. Timestamp olarak gönderilmelidir.	
		'endDate'       => time(),
		//Sadece belirtilen sayfadaki bilgileri döndürür.
		'page'          => 0,
		// Tarih filtresinin çalışacağı tarih CREATED_DATE ya da LAST_MODIFIED_DATE gönderilebilir	
		'dateQueryType' => 'CREATED_DATE',
		// Bir sayfada listelenecek maksimum adeti belirtir.	
		'size'          => 50
	)
);
```

### Sipariş Servisi (Order Service)

```php

/**
 *
 * Trendyol sistemine ilettiğiniz ürünler ile planlanın butik sonrası müşteriler tarafından verilen her siparişin bilgisini
 * bu method yardımıyla alabilirsiniz. Trendyol.com'da müşteriler tarafından verilen siparişler, sistem tarafından otomatik
 * paketlenerek sipariş paketleri oluşturulur. Bu yüzden sistem çektiğiniz bir adet OrderNumber'a karşılık birden fazla
 * shipmentPackageID gelebilir.
 *
 * @note İsteğe bağlı olarak dizideki alanların istenilen bölümleri eklenmeyebilir veya dizi hiç gönderilmeyebilir.
 * @param array
 *
 */
$trendyol->order->orderList(
	array(
		// Belirli bir tarihten sonraki siparişleri getirir. Timestamp olarak gönderilmelidir.	
		'startDate'          => time() - (86400 * 14),
		// Belirtilen tarihe kadar olan siparişleri getirir. Timestamp olarak gönderilmelidir ve startDate ve endDate aralığı en fazla 2 hafta olmalıdır
		'endDate'            => time(),
		// Sadece belirtilen sayfadaki bilgileri döndürür	
		'page'               => 0,
		// Bir sayfada listelenecek maksimum adeti belirtir. (Max 200)
		'size'               => 200,
		// Sadece belirli bir sipariş numarası verilerek o siparişin bilgilerini getirir	
		'orderNumber'        => '',
		// Siparişlerin statülerine göre bilgileri getirir.	(Created, Picking, Invoiced, Shipped, Cancelled, Delivered, UnDelivered, Returned, Repack, UnSupplied)
		'status'             => '',
		// Siparişler neye göre sıralanacak? (PackageLastModifiedDate, CreatedDate)
		'orderByField'       => 'CreatedDate',
		// Siparişleri sıralama türü? (ASC, DESC)
		'orderByDirection'   => 'DESC',
		// Paket numarasıyla sorgu atılır.	
		'shipmentPackagesId' => '',
	)
);

```

### Trendyol Sipariş Bildirimi WebHook (Trendyol Order WebHook)

Trendyol Tarafından sipariş bildirimleri için bir webhook verilmediği için bu işlemi yapmak isteyenler kişiler için yazılmış olan bir webhook dur. Webhook'u kullanabilmeniz için sunucunuzda **sqlite** pdo driver kurulu olması gerekmektedir.

**Not:** Oluşturacağınız bu dosyayı linux tarafında arkaplanda sürekli çalışır halde kalması gerekmektedir. Bunu yapmak için **tmux** veya **servis yazarak** kullanabilirsiniz. **Cronjob ile kullanmayınız!**

```php

include "vendor/autoload.php";

use IS\PazarYeri\Trendyol\TrendyolClient;

$trendyol = new TrendyolClient(); 
$trendyol->setSupplierId(100000);
$trendyol->setUsername("xxxxxxxxxxxxxxxxxxxx");
$trendyol->setPassword("xxxxxxxxxxxxxxxxxxxx");

/**
 *
 * @description Webhook istek hızı
 * @param string 
 * 	  'slow'   => 300 saniye,
 *	  'medium' => 180 saniye (default/taviye edilen),
 * 	  'fast'   => 60 saniye
 * 	  'vfast'  => 30 saniye
 * 	   
 */
$trendyol->webhook->setRequestMode('medium');

/**
 *
 * @description Trendyol sonuçlarında kaç siparişin getirileceği
 * @param string 
 * 	  'vmax'     => 200 adet,
 *	  'max'      => 150 adet,
 * 	  'medium'   => 100 adet (default/taviye edilen),
 * 	  'min'      => 50 adet
 * 	   
 */
$trendyol->webhook->setResultMode('medium');

/* Anonymous function ile siparişleri almak */
$trendyol->webhook->orderConsume(function($order){
	
	echo "Sipariş Bilgileri";
	echo "<pre>";
	print_r($order);
	echo "</pre>";
	
});

/* Class ile siparişleri almak */

Class TrendyolOrders
{
	
	public function consume($order)
	{

		echo "Sipariş Bilgileri";
		echo "<pre>";
		print_r($order);
		echo "</pre>";	

	}

}

$trendyol->webhook->orderConsume(array(new TrendyolOrders(), 'consume'));

```

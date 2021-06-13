<?php

namespace IS\PazarYeri\Trendyol\Helper;

use IS\PazarYeri\Trendyol\Helper\Format;

Class Request
{

    /**
     *
     * Trendyol Api Software Partner (1 Ocak 2021 Güncellemesi)
     * @var string
     *
     */
    public $apiSoftwarePartner = 'SelfIntegration';

    /**
     *
     * Trendyol Api Software Partner Test Mode
     * @var string
     *
     */
    public $apiTestMode = false;

    /**
     *
     * Trendyol Api Url
     * @var string
     *
     */
    public $apiUrl;

    /**
     *
     * Trendyol Api SupplierId
     * @var int
     *
     */
    protected $apiSupplierId;

    /**
     *
     * Trendyol Api Kullanıcı Adı
     * @var string
     *
     */
    protected $apiUsername;

    /**
     *
     * Trendyol Api Şifre
     * @var string
     *
     */
    protected $apiPassword;

    /**
     *
     * Trendyol Api Şifre
     * @var string
     *
     */
    protected $method;

    /**
     *
     * Get veya post için verileri barındırır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @var array
     *
     */
    protected $datas = array();

    /**
     *
     * Service Ayarlarını yapar
     * @param string
     *
     */
    public function __construct($apiUrl, $supplierId, $username, $password,$testmode, $method = 'GET')
    {
        $this->setApiSupplierId($supplierId);
        $this->setApiUsername($username);
        $this->setApiPassword($password);
        $this->setApiUrl($apiUrl);
        $this->setMethod($method);
        $this->setApiTestMode($testmode);
    }

    /**
     *
     * API SupplierId değerini değiştirir.
     *
     * @param string $apiUrl
     *
     */
    public function setApiTestMode($is_test_mode = false)
    {
        $this->apiTestMode = $is_test_mode;
    }

    /**
     *
     * API SupplierId değerini değiştirir.
     *
     * @param string $apiUrl
     *
     */
    public function setApiSupplierId($supplierId)
    {
        $this->apiSupplierId = $supplierId;
    }

    /**
     *
     * API Kullanıcı adını değiştirir.
     *
     * @param string $apiUrl
     *
     */
    public function setApiUsername($username)
    {
        $this->apiUsername = $username;
    }

    /**
     *
     * API Şifresini değiştirir.
     *
     * @param string $apiUrl
     *
     */
    public function setApiPassword($password)
    {
        $this->apiPassword = $password;
    }

    /**
     *
     * Api Linkini ayarlar.
     *
     * @param string $apiUrl
     *
     */
    public function setApiUrl($apiUrl)
    {
        if ($this->apiTestMode) {
           $apiUrl = str_replace('api.trendyol.com/sapigw','stageapi.trendyol.com/stagesapigw',$apiUrl);
        }
        $this->apiUrl = $apiUrl;
    }

    /**
     *
     * Method türünü ayarlama POST|GET...
     *
     * @param string $method
     *
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }
    /**
     *
     * Trendyol için User Agent Döndürür (1 Ocak 2021 Güncellemesi)
     *
     * @author Ali Tayfur <alitayfur@msn.com>
     * @return string
     *
     */
    protected function userAgent()
    {
        return $this->apiSupplierId . ' - ' . $this->apiSoftwarePartner;
    }
    /**
     *
     * Trendyol için basic auth döndürür
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return string
     *
     */
    protected function authorization()
    {
        return base64_encode($this->apiUsername . ':' . $this->apiPassword);
    }

    /**
     *
     * Api url bilgisini döner.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $datas
     *
     */
    public function getApiUrl($requestData)
    {

        $apiUrl = $this->apiUrl;
        foreach (Format::getUrlSpecialParameters($apiUrl) as $key)
        {
            if (isset($requestData[$key]))
            {
                $apiUrl = str_replace('{' . $key . '}',  $requestData[$key], $apiUrl);
                unset($requestData[$key]);
            }
        }

        $apiUrl = str_replace('{supplierId}', $this->apiSupplierId, $apiUrl);
        if ($this->method == 'POST' || !is_array($requestData) || count($requestData) <= 0) {
            return $apiUrl;
        }

        return $apiUrl . '?' . http_build_query($requestData);

    }

    /**
     *
     * Hazırlanan isteği apiye iletir ve yanıtı json olarak döner.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $query
     * @param array $data
     * @param boolean $authorization
     * @return array
     *
     */
    public function getResponse($query, $data, $authorization = true)
    {

        $requestData = Format::initialize($query, $data);
        $ch = curl_init();
        $header = [];
        curl_setopt($ch, CURLOPT_URL, $this->getApiUrl($requestData));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent());

        if ($authorization) {
            $header[] = 'Authorization: Basic ' . $this->authorization();
        }

        if ($this->method == 'POST') {
            $header[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        $response = trim(curl_exec($ch));
        if (empty($response)) {
            throw new TrendyolException("Trendyol boş yanıt döndürdü.");
        }

        $response = json_decode($response);
        curl_close($ch);
        return $response;
    }

}

<?php

namespace IS\PazarYeri\Trendyol;

use IS\PazarYeri\Trendyol\Helper\Gateway;

Class TrendyolClient extends Gateway
{

	/**
	 *
	 * @description Trendyol Api SupplierId
	 * @param string $apiSupplierId
	 *
	 */
	public function setSupplierId($apiSupplierId)
	{
		$this->apiSupplierId = $apiSupplierId;
	}

	/**
	 *
	 * @description Trendyol Api Kullanıcı Adı
	 * @param string $apiUsername
	 *
	 */
	public function setUsername($apiUsername)
	{
		$this->apiUsername = $apiUsername;
	}

	/**
	 *
	 * @description Trendyol Api Şifre
	 * @param string $apiPassword
	 *
	 */
	public function setPassword($apiPassword)
	{
		$this->apiPassword = $apiPassword;
	}

    /**
	 *
	 * @description Trendyol Api Şifre
	 * @param string $apiPassword
	 *
	 */
	public function setTestMode($apiTestMode=false)
	{
		$this->apiTestMode = $apiTestMode;
	}

}

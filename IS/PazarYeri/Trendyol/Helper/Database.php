<?php

namespace IS\PazarYeri\Trendyol\Helper;

Class Database
{

	/**
	 *
	 * SQLite Veritabanı Bağlantısı
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	protected $db = null;

	/**
	 *
	 * SQLite Veritabanı Sınıfı Oluşturucu
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function __construct($firstSetupDate)
	{
		$this->checkSQLiteAndPDODriver();

		$SQLitePath =  __DIR__ . '/../Data/';
		if (!file_exists($SQLitePath)) {
			mkdir($SQLitePath, 0777);
		}

		$this->db = new \PDO("sqlite:" . $SQLitePath . 'trendyol.sqlite');
	    $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->checkAndCreateTables($firstSetupDate);
	}

	/**
	 *
	 * SQLite ve PDO sürücülerini kontrol etme
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	protected function checkSQLiteAndPDODriver()
	{

		$response = \PDO::getAvailableDrivers();
		if (count($response) <= 0 || empty($response)) {
			throw new TrendyolException("Sunucunuzda PDO Aktif Olmalıdır.");
		}

		if (!in_array('sqlite', $response)) {
			throw new TrendyolException("Sunucunuzda SQLite PDO Sürücüsü Aktif Olmalıdır.");
		}

	}

	/**
	 *
	 * SQLite Veritabanı tablolarını kontrol etme ve oluşturma
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function checkAndCreateTables($firstSetupDate)
	{

		$sqlQuerys = array(
			'CREATE TABLE IF NOT EXISTS `orders` ( 
				`orderid` INTEGER NOT NULL , 
				`status` TINYINT NOT NULL DEFAULT \'0\' , 
				`date` INTEGER NOT NULL , 
				PRIMARY KEY (`orderid`)
			);',
			'CREATE TABLE IF NOT EXISTS `settings` ( 
				`lastOrderDate` INTEGER NOT NULL DEFAULT \'0\'
			);',
		);

		foreach ($sqlQuerys as $sql) {
			$this->db->query($sql);
		}

		$settings = $this->selectSettings();
		if (!isset($settings->lastOrderId)) {
			$prepare = $this->db->prepare('INSERT INTO settings (lastOrderDate) VALUES(?)');
			$prepare->execute(array($firstSetupDate));
		}

	}

	/**
	 *
	 * Siparişleri SQLite üzerinde tamamlandı olarak işaretleme
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $orderId
	 * @return bool 
	 *
	 */
	public function updateStartDate($startDate)
	{
		$prepare = $this->db->prepare('UPDATE `settings` SET lastOrderDate = ?');
		return $prepare->execute(array($startDate));
	}

	/**
	 *
	 * Siparişleri SQLite üzerinde tutma
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $orderId
	 * @return int 
	 *
	 */
	public function addOrder($orderId)
	{

		$prepare = $this->db->prepare('INSERT INTO `orders` (orderid, status, date) VALUES(?, ?, ?)');
		$prepare->execute(array($orderId, 0 , time()));
		return $this->db->lastInsertId();

	}

	/**
	 *
	 * Siparişleri SQLite üzerinde kontrol etme
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $orderId
	 * @return object 
	 *
	 */
	public function selectOrder($orderId)
	{

		$prepare = $this->db->prepare('SELECT * FROM `orders` WHERE orderid = ?');
		$prepare->execute(array($orderId));
		return $prepare->fetch(\PDO::FETCH_OBJ);
	}

	/**
	 *
	 * Siparişleri SQLite üzerinde tamamlandı olarak işaretleme
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param int $orderId
	 * @return bool 
	 *
	 */
	public function finishOrder($orderId)
	{

		$prepare = $this->db->prepare('UPDATE `orders` SET status = ? WHERE orderid = ?');
		return $prepare->execute(array(1 , $orderId));
	}

	/**
	 *
	 * WebHookService Ayarlarını getirir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @return object 
	 *
	 */
	public function selectSettings()
	{

		$prepare = $this->db->prepare('SELECT * FROM `settings`');
		$prepare->execute();
		return $prepare->fetch(\PDO::FETCH_OBJ);
	}

}

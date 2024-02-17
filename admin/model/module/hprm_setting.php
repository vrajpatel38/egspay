<?php

class ModelModuleHprmSetting extends Model
{

	public function uninstallTable()
	{
		$error = 0;
		$sqls = [];

		$sqls[] = "DROP TABLE IF EXISTS " . DB_PREFIX . "customer_to_reseller";
		$sqls[] = "DROP TABLE IF EXISTS " . DB_PREFIX . "reseller_notif";
		$sqls[] = "DELETE FROM `" . DB_PREFIX . "setting` WHERE " . DB_PREFIX . "setting.code LIKE '%hpwd_reseller_management%'";
		$default_country = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer` LIKE '%default_country%';");
		if ($default_country->num_rows) {
			$sqls[] = "ALTER TABLE `" . DB_PREFIX . "customer` DROP default_country";
		}
		foreach ($sqls as $sql) {
			if (!$this->db->query($sql)) {
				$error++;
			}
		}

		if ($error < 1) {

			return true;
		} else {

			return false;
		}
	}

	public function getCountries()
	{
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE name = 'Afghanistan'");
		if ($q->num_rows) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country_hpwd");
		}
		return $query->rows;
	}

	public function installTable()
	{
		$error = 0;
		$sqls = [];

		$sqls[] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_to_reseller` (
  `customer_to_reseller_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `no_nik` varchar(20) DEFAULT NULL,
  `social_media_facebook` varchar(50),
  `social_media_twitter` varchar(50) NOT NULL,
  `social_media_instagram` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `lampiran` varchar(128) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`customer_to_reseller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		$sqls[] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "reseller_notif` ( `notif_id` INT(11) NOT NULL AUTO_INCREMENT ,  `customer_id` INT(11) NOT NULL ,  `msg` VARCHAR(255) NOT NULL ,    PRIMARY KEY  (`notif_id`)) ENGINE = InnoDB;";

		$default_country = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer` LIKE '%default_country%';");
		if (!$default_country->num_rows) {
			$sqls[] = "ALTER TABLE `" . DB_PREFIX . "customer` ADD default_country VARCHAR(8) NOT NULL";
		}

		foreach ($sqls as $sql) {
			if (!$this->db->query($sql)) {
				$error++;
			}

		}

		if ($error < 1) {

			return true;
		} else {
			return false;
		}

	}
}

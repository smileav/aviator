<?php
class ModelExtensionModuleMultilangCountriesZones extends Model {

	public function install() {

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "country_description` (
				`country_id` INT(11) NOT NULL,
				`language_id` INT(11) NOT NULL,
				`name` varchar(128),
				PRIMARY KEY (`country_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "country_description`  SELECT country_id,'1',name FROM `" . DB_PREFIX .  "country` ORDER BY country_id;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zone_description` (
				`zone_id` INT(11) NOT NULL,
				`language_id` INT(11) NOT NULL,
				`name` varchar(128),
			  PRIMARY KEY (`zone_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "zone_description`  SELECT zone_id,'1',name FROM `" . DB_PREFIX .  "zone` ORDER BY zone_id;");

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` LIMIT 1");
		if (!isset($query->row['sort_order'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX .  "country` ADD `sort_order` INT(3) NOT NULL AFTER `status`");
		}

		$query = "SELECT * FROM `" . DB_PREFIX . "zone` LIMIT 1";
		if (!isset($query->row['sort_order'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "zone` ADD `sort_order` INT(3) NOT NULL AFTER `status`");
		}

		$this->cache->delete('zone');
		$this->cache->delete('country.catalog');

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/multilangcountrieszones');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/multilangcountrieszones');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "country_description`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX .  "zone_description`;");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` DROP `sort_order`;");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "zone` DROP `sort_order`;");

		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('multilangcountrieszones');

		$this->load->model('setting/modification');
		$row = $this->model_setting_modification->getModificationByCode('multilangcountrieszones');
		$this->model_setting_modification->disableModification($row['modification_id']);
		$data['redirect'] = 'extension/extension/module';
		$this->load->controller('marketplace/modification/refresh', $data);
	}
}
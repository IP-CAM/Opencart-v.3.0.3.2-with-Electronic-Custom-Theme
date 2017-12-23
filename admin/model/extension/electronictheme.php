<?php
class ModelExtensionElectronictheme extends Model {
	public function install() {
	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."newslettertemplate` (
  `newstemplate_id` int(41) NOT NULL AUTO_INCREMENT,
  `name` varchar(41) NOT NULL,
  `sortorder` int(100) NOT NULL,
  `status` int(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`newstemplate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."newstemplate` (
  `newstemplate_id` int(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `language_id` int(10) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}
	public function uninstall() {
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."newslettertemplate`");
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."newstemplate`");
	}
}

<?php
class ModelExtensionTmdNewsletter extends Model {
	
	public function addNewsletter($data) {
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "newslettertemplate set name='". $data['name']."', sortorder='".(int) $data['sortorder']."', status='".(int)$data['status']."', date_added=now()");
		
		$newstemplate_id=$this->db->getLastId();
					
		foreach ($data['newsletter_template'] as $language_id => $value) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "newstemplate SET newstemplate_id ='" . (int)$newstemplate_id . "', language_id = '" . (int)$language_id . "', subject = '" . $this->db->escape($value['subject'])."',description='". $this->db->escape($value['description'])."'"); 
		}

	}
	
	public function EditNewsleter($newstemplate_id ,$data) {
		$this->db->query("UPDATE " . DB_PREFIX . "newslettertemplate set name='".$data['name']."', sortorder='".(int)$data['sortorder']."',	status='".(int)$data['status']."',date_modified=now() where newstemplate_id ='".$newstemplate_id ."'");
		
		$this->db->query("delete from " . DB_PREFIX . "newstemplate where  newstemplate_id  = '" . (int)$newstemplate_id  . "'");
		
		
		foreach ($data['newsletter_template'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newstemplate SET newstemplate_id  = '" . (int)$newstemplate_id  . "', language_id = '" . (int)$language_id . "', subject = '" . $this->db->escape($value['subject']) . "',description  = '" . $this->db->escape($value['description']) . "'");
		}
		
	}
	
	public function deleteNewletter($newstemplate_id){
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "newslettertemplate WHERE newstemplate_id = '" . (int)$newstemplate_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newstemplate WHERE newstemplate_id = '" . (int)$newstemplate_id . "'");

		$this->cache->delete('newstemplate');

	}
		
	public function getNewletter($newstemplate_id)
	{
		$query = $this->db->query("select * from " . DB_PREFIX . "newstemplate n LEFT JOIN ". DB_PREFIX ."newslettertemplate nd ON(n.newstemplate_id = nd.newstemplate_id) where n.newstemplate_id='".$newstemplate_id."' and n.language_id='".(int)$this->config->get('config_language_id')."'");
		
		return $query->row;
	}
		
	public function getNewletterdes($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "newslettertemplate n  LEFT JOIN " . DB_PREFIX . "newstemplate nd ON (n.newstemplate_id = nd.newstemplate_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		
		$sort_data = array(
			'name',
			'sortorder'
		);
		if (isset($data['sortorder']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sortorder";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}		
	public function getNewslaterDescriptions($newstemplate_id) {
		$newslater_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newstemplate WHERE newstemplate_id = '" . (int)$newstemplate_id . "'");

		foreach ($query->rows as $result) {
			$newslater_description_data[$result['language_id']] = array(
				'subject'       => $result['subject'],
				'description' => $result['description']				
			);
		}

		return $newslater_description_data;
	}
	
	public function getTotalNewslaters() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newslettertemplate");

		return $query->row['total'];
	}

}
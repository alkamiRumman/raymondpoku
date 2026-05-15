<?php

class Site_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function getUser($username)
	{
		$this->db->select('*');
		$this->db->from(TABLE_USERS);
		$this->db->where(array('username' => $username, 'deleted' => 0));
		$query = $this->db->get();
		if ($query->num_rows()) {
			return $query->row();
		}
		return false;
	}

	function checkEmail($text)
	{
		$this->db->select('COUNT(*) AS cnt');
		$this->db->from(TABLE_USERS);
		$this->db->where('username', $text);
		$result = $this->db->get();
		return $result->first_row()->cnt;
	}

	function save($arr)
	{
		$this->db->insert(TABLE_USERS, $arr);
	}

	function savePackageHistory($arr)
	{
		$this->db->insert(TABLE_PACKAGEHISTORY, $arr);
	}

	public function getUserBySubId($id)
	{
		$this->db->select('id, sub_id, expireDate');
		$this->db->from(TABLE_USERS);
		$this->db->where('sub_id', $id);
		return $this->db->get()->row();
	}

	function update($arr, $id){
		$this->db->update(TABLE_USERS, $arr, array('id' => $id));
	}
}

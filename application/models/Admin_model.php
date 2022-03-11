<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function islogin($useremail, $password)
	{
		$this->db->where('email =', $useremail);
		$this->db->where('password =', $password);
		$query = $this->db->get('users');
		return $query->row();
	}

	//------------------------------------

	public function getSingleUser($data)
	{
		$this->db->where('email =', $data['email']);
		$this->db->where('userId =', $data['id']);
		$query = $this->db->get('users');
		return $query->row();
	}

	//---------------------------------------

	public function saveNewPassword($data)
	{
		$this->db->where('userId =', $data['id']);
		return $this->db->update('users', ['password' => $data['password'],
											'num' => $data['key'],
											'firstLoginDate' => $data['firstLoginDate']]);
	}

	//--------------------------------------

	public function checkEmail($email)
	{
		$this->db->where('email =', $email);
		$query = $this->db->get('users');
		return $query->row();
	}

	//----------------------------------

	public function updateToken($email, $token, $num)
	{
		$this->db->where('email =', $data['email']);
		return $this->db->update('users', ['token' => $token, 'num' => $num]);
	}

	//-------------------------------------------

	public function verifyToken($data)
	{
		$this->db->where('token =', $data['token']);
		$query = $this->db->get('users');
		return $query->row();
	}

	//-------------------------------------------------

	public function updatePassword($data)
	{
		$this->db->where('token =', $data['token']);
		return $this->db->update('users', ['password' => $data['password'], 'num' => $data['num']]);
	}

	//-----------------------------------------------

	public function updateStaffPassword($data)
	{
		$this->db->where('userId =', $data['userId']);
		return $this->db->update('users', ['password' => $data['password']]);
	}

	//----------------------------------------------

	public function getActiveBranches()
	{
		$this->db->where('status =', 'Active');
		$this->db->order_by('branchName', 'ASC');
		$query = $this->db->get('branches');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//------------------------------------------

	public function getActiveDepartment()
	{
		$this->db->where('status =', 'Active');
		$this->db->order_by('deptName', 'ASC');
		$query = $this->db->get('departments');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-------------------------------------------

	public function getActivePosts($deptId)
	{
		$this->db->where('deptId', $deptId);
		$this->db->order_by('postId', 'ASC');
		$query = $this->db->get('posts');
		$output = '';
		foreach ($query->result() as $row) {
			$output .= '<option value="'.$row->postId.'">'.$row->postName.'</option>';
		}
		return $output;
	}

	//-------------------------------------

	public function checkUser($empiremail, $citymail)
	{
		$this->db->where('email =', $empiremail);
		$this->db->or_where('email =', $citymail);
		$query = $this->db->get('users');
		return $query->row();
	}

	//-----------------------------------

	public function addStaff($data)
	{
		$this->db->trans_begin();

		$this->db->insert('users', ['fname' => $data['fname'],
									'lname' => $data['lname'],
									'email' => $data['email'],
									'branchId' => $data['branch'],
									'deptId' => $data['dept'],
									'postId' => $data['post'],
									'role' => $data['role'],
									'status' => 'Active',
									'password' => $data['password'],
									'createdBy' => $data['createdBy'],
									'dateCreated' => $data['dateCreated']]);

		$insert_id = $this->db->insert_id();
		$this->db->insert('scoretable', ['userId' => $insert_id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}
		else {
			$this->db->trans_commit();
            return  TRUE;
		}
	}

	//----------------------------------
}
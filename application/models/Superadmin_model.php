<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_model extends CI_Model {

	public function getAllBranches()
	{
		$this->db->order_by('branchName', 'ASC');
		$query = $this->db->get('branches');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}	
	}

	//-------------------------------

	public function checkBranch($branchName)
	{
		$this->db->where('branchName =', $branchName);
		$query = $this->db->get('branches');
		return $query->row();
	}

	//---------------------------------

	public function addBranch($branchName)
	{
		return $this->db->insert('branches', ['branchName' => $branchName]);
	}

	//-----------------------------------

	public function getBranch($id)
	{
		$this->db->where('branchId =', $id);
		$query = $this->db->get('branches');
		return $query->row();
	}

	//-----------------------------------

	public function updateBranch($data)
	{
		$this->db->where('branchId =', $data['id']);
		return $this->db->update('branches', ['branchName' => $data['branch'],
											'status' => $data['status']]);
	}

	//------------------------------------

	public function getAllDepartment()
	{
		$this->db->order_by('deptName', 'ASC');
		$query = $this->db->get('departments');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//--------------------------------------

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

	//--------------------------------------

	public function checkDepartment($dept)
	{
		$this->db->where('deptName =', $dept);
		$query = $this->db->get('departments');
		return $query->row();
	}

	//--------------------------------------

	public function addDepartment($dept)
	{
		return $this->db->insert('departments', ['deptName' => $dept]);
	}

	//-------------------------------------

	public function getDept($id)
	{
		$this->db->where('deptId =', $id);
		$query = $this->db->get('departments');
		return $query->row();
	}

	//-----------------------------------

	public function getSingleDeptName($deptId)
	{
		$this->db->where('deptId =', $deptId);
		$query = $this->db->get('departments');
		return $query->row()->deptName;
	}

	//------------------------------------

	public function getOtherDepts($id)
	{
		$this->db->where('deptId !=', $id);
		$this->db->where('status =', 'Active');
		$this->db->order_by('deptName', 'ASC');
		$query = $this->db->get('departments');
		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//--------------------------------------

	public function getOtherBranches($branchId)
	{
		$this->db->where('branchId !=', $branchId);
		$this->db->where('status =', 'Active');
		$this->db->order_by('branchName', 'ASC');
		$query = $this->db->get('branches');
		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-----------------------------------

	public function updateDept($data)
	{
		$this->db->where('deptId =', $data['id']);
		return $this->db->update('departments', ['deptName' => $data['dept'],
											'status' => $data['status']]);
	}

	//----------------------------------

	public function getAllPost()
	{
		$this->db->select('*');
		$this->db->from('departments');
		$this->db->join('posts', 'departments.deptId = posts.deptId');
		$this->db->order_by('posts.postId','ASC');
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//------------------------------------

	public function checkPost($data)
	{
		$this->db->where('deptId =', $data['dept']);
		$this->db->where('postName =', $data['post']);
		$query = $this->db->get('posts');
		return $query->row();
	}

	//------------------------------------

	public function addPost($data)
	{
		return $this->db->insert('posts', ['postName' => $data['post'],
											'deptId' => $data['dept']]);
	}

	//------------------------------------

	public function getPost($id)
	{
		$this->db->where('postId =', $id);
		$query = $this->db->get('posts');
		return $query->row();
	}

	//-----------------------------------

	public function updatePost($data)
	{
		$this->db->where('postId =', $data['id']);
		return $this->db->update('posts', ['postName' => $data['post'],
											'deptId' => $data['dept'],
											'status' => $data['status']]);
	}

	//-----------------------------------

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

	//---------------------------------

	public function getAllStaff()
	{
		$this->db->select('A.userId, fname, lname, A.status, postName, branchName');
		$this->db->from('users as A');
		$this->db->join('branches as B', 'A.branchId = B.branchId', 'INNER');
		$this->db->join('posts as C', 'A.postId = C.postId', 'INNER');
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-----------------------------------

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

	//---------------------------------------

	public function checkUser($empiremail, $citymail)
	{
		$this->db->where('email =', $empiremail);
		$this->db->or_where('email =', $citymail);
		$query = $this->db->get('users');
		return $query->row();
	}

	//---------------------------------------------

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
            return  $insert_id;
		}
	}

	//-------------------------------------------------

	public function getStaffWithId($userId)
	{
		$this->db->select('*');
		$this->db->from('users AS A');
		$this->db->join('posts AS C', 'A.postId = C.postId', 'INNER');
		$this->db->join('departments AS B', 'B.deptId = C.deptId', 'INNER');
		$this->db->join('branches AS D', 'A.branchId = D.branchId', 'INNER');
		$this->db->where('userId =', $userId);
		$query = $this->db->get();
		return $query->row();
	}

	//------------------------------------------------

	public function updateStaff($data)
	{
		$this->db->where('userId =', $data['id']);
		return $this->db->update('users', ['fname' => $data['fname'],
											'lname' => $data['lname'],
											'email' => $data['email'],
											'branchId' => $data['branch'],
											'deptId' => $data['dept'],
											'postId' => $data['post'],
											'role' => $data['role'],
											'status' => $data['status'],
											'lastModifiedBy' => $data['lastModifiedBy'],
											'dateLastModified' => $data['dateLastModified']]);
	}

	//-----------------------------------------------

	public function addQuestion($data)
	{
		$this->db->trans_begin();

		$this->db->insert('questions', ['question' => $data['question'],
										'deptId' => $data['dept'],
										'questiontype' => $data['qtype']]);

		$insertId = $this->db->insert_id();

		$this->db->insert('options', ['option' => $data['option1'],
										'deptId' => $data['dept'],
										'questionId' => $insertId]);
		$this->db->insert('options', ['option' => $data['option2'],
										'deptId' => $data['dept'],
										'questionId' => $insertId]);
		$this->db->insert('options', ['option' => $data['option3'],
										'deptId' => $data['dept'],
										'questionId' => $insertId]);
		$this->db->insert('options', ['option' => $data['option4'],
										'deptId' => $data['dept'],
										'questionId' => $insertId]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		else {
			$this->db->trans_commit();
            return $insertId;
		}
	}

	//---------------------------------------

	public function addMultipleQuestion($data)
	{
		$this->db->trans_begin();

		$this->db->insert('questions', ['question' => $data['question'],
										'deptId' => $data['dept'],
										'status' => 'Active',
										'questiontype' => $data['qtype']]);

		$insertId = $this->db->insert_id();

		$this->db->insert('truefalse', ['questionId' => $insertId, 'answer' => $data['yes'],
										'deptId' => $data['dept']]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}
		else {
			$this->db->trans_commit();
            return TRUE;
		}
	}

	//-----------------------------------------------

	public function fetchPendingQuestion($questionId)
	{
		$this->db->select('*');
		$this->db->from('questions AS A');
		$this->db->join('options AS B', 'A.questionId = B.questionId');
		$this->db->where('A.questionId =', $questionId);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-------------------------------------------------

	public function setAnswer($data)
	{
		$this->db->trans_begin();

		$this->db->where('optionId =', $data['option']);
		$this->db->update('options', ['answer' => 1]);

		$this->db->where('questionId =', $data['id']);
		$this->db->update('questions', ['status' => 'Active']);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		else {
			$this->db->trans_commit();
            return 1;
		}
	}

	//-------------------------------------------

	public function fetchAllPendingQuestion()
	{
		$this->db->select('*');
		$this->db->from('questions AS A');
		$this->db->join('departments AS B', 'A.deptId = B.deptId');
		$this->db->where('A.status =', 'Pending');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-------------------------------------------

	public function checkUrl($data)
	{
		$this->db->where('deptId =', $data['dept']);
		$query = $this->db->get('inductionvideos');
		return $query->row();
	}

	//-------------------------------------------

	public function updateUrl($data)
	{
		$this->db->where('deptId =', $data['dept']);
		return $this->db->update('inductionvideos', ['url' => $data['video']]);
	}

	//---------------------------------------------

	public function insertUrl($data)
	{
		return $this->db->insert('inductionvideos', ['url' => $data['video'],
													'deptId' => $data['dept']]);
	}

	//----------------------------------------------

	public function uploadSlide($data)
	{
		return $this->db->insert('slides', ['slideFile' => $data['filename'],
											'about' => $data['intro'],
											'title' => $data['title'],
											'instructor' => $data['tutor'],
											'date' => $data['trainingdate']]);
	}

	//----------------------------------------------

	public function getPendingStaff()
	{
		$this->db->select('*');
		$this->db->from('users AS A');
		$this->db->join('scoretable AS B', 'A.userId = B.userId', 'INNER');
		$this->db->join('posts AS C', 'C.postId = A.postId', 'INNER');
		$this->db->join('branches AS D', 'A.branchId = D.branchId', 'INNER');
		$this->db->where('A.status =', 'Pending');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//----------------------------------------------

	public function updateStaffStatus($data)
	{
		$this->db->where('userId =', $data['userId']);
		return $this->db->update('users', ['status' => $data['status']]);
	}

	//------------------------------------------------

	public function updateBranchStatus($data)
	{
		$this->db->where('branchId =', $data['branchId']);
		return $this->db->update('branches', ['status' => $data['status']]);
	}

	//----------------------------------------

	public function updateDeptStatus($data)
	{
		$this->db->where('deptId =', $data['deptId']);
		return $this->db->update('departments', ['status' => $data['status']]);
	}

	//----------------------------------------

	public function updatePostStatus($data)
	{
		$this->db->where('postId =', $data['postId']);
		return $this->db->update('posts', ['status' => $data['status']]);
	}

	//----------------------------------------
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	public function getSingleUser($userId)
	{
		$this->db->where('userId =', $userId);
		$query = $this->db->get('users');
		return $query->row();
	}

	//--------------------------------------

	public function getAllQuestions()
	{
		$this->db->where('status =', 'Active');
		$this->db->order_by('questionId', 'RANDOM');
		$query = $this->db->get('questions');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-----------------------------------------

	public function getAllOptions()
	{
		$this->db->order_by('optionId', 'RANDOM');
		$query = $this->db->get('options');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//------------------------------------

	public function confirmMultiChoice($parts)
	{
		$this->db->where('questionId =', $parts[0]);
		$this->db->where('answer =', $parts[1]);
		$query = $this->db->get('truefalse');
		return $query->row();
	}

	//-----------------------------------------

	public function updateStaffCount($count, $userId)
	{
		$this->db->where('userId =', $userId);
		return $this->db->update('users', ['count' => $count]);
	}

	//-------------------------------------------

	public function getScoreRecord($userId)
	{
		$this->db->select('*');
		$this->db->from('scoretable AS A');
		$this->db->join('users AS B', 'A.userId = B.userId', 'INNER');
		$this->db->join('posts AS C', 'C.deptId = B.deptId', 'INNER');
		$this->db->join('branches AS D', 'B.branchId = D.branchId', 'INNER');
		$this->db->where('A.userId =', $userId);
		$query = $this->db->get();
		return $query->row();
	}

	//------------------------------------------

	public function submitFirstAttempt($score, $count, $lastattempt, $userId, $data)
	{
		$this->db->trans_begin();

		foreach($data as $key=>$id){
			//split data get question id & option id
			$parts = explode("@", $id);

           	//insert answer
           	$this->db->insert('replyanswer', ['questionId' => $parts[0],
           										'optionId' => $parts[1],
           										'code' => $lastattempt,
           										'staffId' => $userId]);
		}

		$status = 'Pending';
		if ($score >= 50) {
			$status = 'Active';
		}

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score1' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => $status]);

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

	//------------------------------------------

	public function submitSecondAttempt($score, $count, $lastattempt, $userId, $data)
	{
		$this->db->trans_begin();

		foreach($data as $key=>$id){
			//split data get question id & option id
			$parts = explode("@", $id);

           	//insert answer
           	$this->db->insert('replyanswer', ['questionId' => $parts[0],
           										'optionId' => $parts[1],
           										'code' => $lastattempt,
           										'staffId' => $userId]);
		}

		$status = 'Pending';
		if ($score >= 50) {
			$status = 'Active';
		}

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score2' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => $status]);

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

	//-------------------------------------------

	public function submitThirdAttempt($score, $count, $lastattempt, $userId, $data)
	{
		$this->db->trans_begin();

		foreach($data as $key=>$id){
			//split data get question id & option id
			$parts = explode("@", $id);

           	//insert answer
           	$this->db->insert('replyanswer', ['questionId' => $parts[0],
           										'optionId' => $parts[1],
           										'code' => $lastattempt,
           										'staffId' => $userId]);
		}

		$status = 'Pending';
		if ($score >= 50) {
			$status = 'Active';
		}

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score3' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => $status]);

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

	//-----------------------------------------

	public function submitFirstTest($score, $count, $lastattempt, $userId)
	{
		$this->db->trans_begin();

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score1' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => 'Pending']);

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

	//---------------------------------------

	public function submitSecondTest($score, $count, $lastattempt, $userId)
	{
		$this->db->trans_begin();

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score2' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => 'Pending']);

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

	//----------------------------------------

	public function submitThirdTest($score, $count, $lastattempt, $userId)
	{
		$this->db->trans_begin();

		$this->db->where('userId =', $userId);
		$this->db->update('scoretable', ['score3' => $score, 'lastattempt' => $lastattempt]);

		$this->db->where('userId =', $userId);
		$this->db->update('users', ['count' => $count, 'status' => 'Pending']);

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

	//-------------------------------------------

	public function countRecord()
	{
		$query = $this->db->get('inductionvideos');
		return $query->num_rows();
	}

	//----------------------------------------

	public function fetch_videos($limit, $start)
	{
		$this->db->select('*');
		$this->db->from('inductionvideos AS A');
		$this->db->join('departments AS B', 'A.deptId = B.deptId');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
       	
       	if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
   }

	//------------------------------------------------

	public function getHrEmail()
	{
		$this->db->where('deptId =', 10);
		$this->db->where('postId =', 3);
		$this->db->where('status =', 'Active');
		$query = $this->db->get('users');
		return $query->row();
	}

	//---------------------------------------------

	public function updateCount($staffid) {
		$this->db->trans_begin();

		$this->db->where('userId', $staffid);
		$query = $this->db->get('users');
		$count = $query->row()->count;
		$count = $count + 1;

		$this->db->where('userId', $staffid);
		$this->db->update('users', ['count' => $count]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		else {
			$this->db->trans_commit();
			return $count;
		}
	}

	//---------------------------------------------

	public function getAllSlides()
	{
		$this->db->order_by('slideId', 'DESC');
		$query = $this->db->get('slides');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//---------------------------------------------

	public function updateStaffPassword($data)
	{
		$this->db->where('userId =', $data['userId']);
		return $this->db->update('users', ['password' => $data['password']]);
	}

	//------------------------------------------

	public function getDeptQuestion($deptId)
	{
		$this->db->where('status =', 'Active');
		$this->db->where('deptId =', $deptId);
		$this->db->order_by('questionId', 'RANDOM');
		$this->db->limit(10);
		$query = $this->db->get('questions');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//----------------------------------------

	public function getDeptOptions($deptId)
	{
		$this->db->where('deptId =', $deptId);
		$this->db->order_by('optionId', 'RANDOM');
		$query = $this->db->get('options');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//---------------------------------------

	public function getDeptTrueFalse($deptId)
	{
		$this->db->where('deptId =', $deptId);
		$this->db->order_by('trueId', 'RANDOM');
		$query = $this->db->get('truefalse');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//-------------------------------------

	public function getDeptMultiAnswers($deptId)
	{
		$this->db->where('deptId =', $deptId);
		$query = $this->db->get('options');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//----------------------------------------

	public function getDeptTrueFalseAnswers($deptId)
	{
		$this->db->where('deptId =', $deptId);
		$query = $this->db->get('truefalse');		
		if($query->num_rows() > 0) {
			$record = $query->result();
			return array('rows' => $record, 'num' => count($record));
		}
	}

	//----------------------------------------

	public function updateMode($userId, $mode)
	{
		$this->db->where('userId =', $userId);
		return $this->db->update('users', ['mode' => $mode]);	
	}

	//------------------------------------------
}
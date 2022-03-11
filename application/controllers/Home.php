<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	//---------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('username'))
			redirect('admin/index');
		$this->load->model('home_model');
	}

	//----dashboard------------------

	public function index()
	{
		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('dashboard/dashboard_view');
		$this->load->view('inc/footer_view');
	}

	//-----------------------------------

	public function changepassword()
	{
		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('login/changepassword_view');
		$this->load->view('inc/footer_view');
	}

	//------------------------------------

	public function updatepassword()
	{
		//validate user input
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			$data['password'] = hash('sha256', $data['password'] . KEY);
			$data['userId'] = $this->session->userdata('userId');

			$newPassword = $this->home_model->updateStaffPassword($data);

			if(!$newPassword) {
				$this->session->set_flashdata('msg', 'Password change unsuccessful');
				return redirect('home/changepassword');
			}

			$this->session->set_flashdata('success', 'Password change successful');
			return redirect('home/changepassword');
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('login/changepassword_view');
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function inductiontest()
	{
		$userId = $this->session->userdata('userId');

		//get staff record
		$staff = $this->home_model->getSingleUser($userId);

		if ($staff->status == 'Active' || $staff->count >= 3) {
			$this->session->set_flashdata('success', 'You are not allowed to take the test');
			return redirect('home/index');
		}
		else {

			if ($staff->mode < 120) {
				$this->session->set_flashdata('msg', 'You have to take all the prep test');
				return redirect('home/preporg');
			}
			//set test duration
			$min = 5;
			$sec = 00;
			$testtime = $min . ':' . $sec;

			//$fullname = $this->session->userdata('fullname');

			//fetch questions and options
			$allquestions = $this->home_model->getAllQuestions();
			
			if (!$allquestions) {
				$this->session->set_flashdata('success', 'Contact admin to upload questions');
				return redirect('home/index');
			}
			else {
				$questions = $allquestions['rows'];
				$numofquestions = $allquestions['num'];
			}

			//fetch options
			$alloptions = $this->home_model->getAllOptions();
			$options = '';
			if ($alloptions) {
				$options = $alloptions['rows'];
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/inductiontest_view', ['questions' => $questions,
														'options' => $options,
														'time' => $testtime, 'staff' => $staff,
														'numofquestions' => $numofquestions]);
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

	public function savetest()
	{
		$data = $this->input->post();
		$num = $data['numofquestions']; //total number of questions
		$count = $data['noofattempts']; //number of test attempts
		unset($data['numofquestions']);
		unset($data['noofattempts']);
		$lastattempt = date('Y-m-d H:i:s');
		$userId = $this->session->userdata('userId');
		//get staff record from scores table
		$scoresheet = $this->home_model->getScoreRecord($userId);
		if (!empty($data)) {
			$attemptedquestions = count($data); //total number of attempted question
			$correct = 0;

			foreach($data as $key=>$id){
				//split data get question id & option id
				$parts = explode("@", $id);
				if (count($parts) > 2) { //multichoice question
	           		if ($parts[2] == 1) { //answer is correct
		           		$correct = $correct + 1;
		           	}
	           	}
	           	else { //true or false
	           		//cross check answer in table
	           		$confirmans = $this->home_model->confirmMultiChoice($parts);
	           		if ($confirmans) {
	           			$correct = $correct + 1;
	           		}
	           	}
			}

			$score = (($correct/$num) * 100);
			$score = (round($score, 2));

			$submitans = '';
			if ($scoresheet->score1 == 1000) { //first attempt
				$submitans = $this->home_model->submitFirstAttempt($score, $count,
																	$lastattempt, $userId, $data);
			}

			if ($scoresheet->score1 < 101 && $scoresheet->score2 == 3000) { //2nd attempt
				$submitans = $this->home_model->submitSecondAttempt($score, $count,
																	$lastattempt, $userId, $data);
			}

			if ($scoresheet->score2 < 101 && $scoresheet->score3 == 5000) { //3rd attempt
				$submitans = $this->home_model->submitThirdAttempt($score, $count,
																	$lastattempt, $userId, $data);
			}

			if (!$submitans) {
				// failed to submit, retake test
				$count = $count - 1;
				$updcount = $this->home_model->updateStaffCount($count, $userId);

				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('test/failedsubmit_view');
				$this->load->view('inc/footer_view');
			}
			else {
				//get hr email
				$hrEmail = $this->home_model->getHrEmail();
				$emails = '';
				if ($hrEmail) {
					$hrEmail = $hrEmail->email;
					$emails = array('hr@empiretrustmfb.com', 'hr@citygateglobal.com', $hrEmail);
				}
				else {
					$emails = array('hr@empiretrustmfb.com', 'hr@citygateglobal.com');
				}
				$name = $scoresheet->fname . ' ' . $scoresheet->lname;
				$designation = $scoresheet->postName;
				$branch = $scoresheet->branchName;

				$message = "<p>Dear team,<br>";
				$message .= "Here is to notify you that" . $name . ', ' . $designation . ' at ' . $branch . 'scored ' . $score . 'in his assessment test, thus successful.</p>';

				$config = array(
								'protocol' => 'sendmail',
								'mailpath' => '/usr/sbin/sendmail',
								'charset'	=>	'iso-8859-1',
								'mailtype'	=>	'html',
								'smtp_port'	=>	25,
								'wordwrap'	=>	TRUE
							);

				$this->load->library('email', $config);
				
				foreach ($emails as $value) {
					$this->email->set_newline("\r\n");
					$this->email->from('no-reply@citygate elearning');
					$this->email->to($value);
					$this->email->subject('Success: Assessment Test Passed');
					$this->email->message($message);
					$this->email->send();
				}

				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('test/testsuccess_view', ['name' => $name, 'numofquestions' => $num,
															'score' => $score,
															'attemptedquestions' => $count,
															'correct' => $correct,
															'scoresheet' => $scoresheet]);
				$this->load->view('inc/footer_view');
			}
		}
		else {
			$score = 0;
			$correct = 0;

			$submitans = '';
			if ($scoresheet->score1 == 1000) { //first attempt
				$submitans = $this->home_model->submitFirstTest($score, $count, $lastattempt, $userId);
			}

			if ($scoresheet->score1 < 101 && $scoresheet->score2 == 3000) { //2nd attempt
				$submitans = $this->home_model->submitSecondTest($score, $count, $lastattempt,$userId);
			}

			if ($scoresheet->score2 < 101 && $scoresheet->score3 == 5000) { //3rd attempt
				$submitans = $this->home_model->submitThirdTest($score, $count, $lastattempt, $userId);
			}

			if (!$submitans) {
				// failed to submit, retake test
				$count = $count - 1;
				$updcount = $this->home_model->updateStaffCount($count, $userId);

				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('test/failedsubmit_view');
				$this->load->view('inc/footer_view');
			}

			$user = $this->home_model->getSingleUser($userId);
			$name = $user->fname . ' ' . $user->lname;
			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/testsuccess_view', ['name' => $name, 'numofquestions' => $num,
														'score' => $score,
														'attemptedquestions' => $count,
														'correct' => $correct]);
			$this->load->view('inc/footer_view');
		}
	}

	//------------------------------------------

	public function inductionvideos()
	{
		$this->load->library('pagination');

		$config = array();
       	$config["base_url"] = base_url() . "home/inductionvideos";
       	$config["total_rows"] = $this->home_model->countRecord();
       	$config["per_page"] = 2;
       	$config["uri_segment"] = 3;
       	$this->pagination->initialize($config);
       	$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
       	$allurls = $this->home_model->fetch_videos($config["per_page"], $page);
       	$urls = $allurls['rows'];

       	$links = $this->pagination->create_links();

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('induction/inductionvideos_view', ['urls' => $urls, 'links' => $links]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------------

	public function trainingslides()
	{
		//get all slides
		$allslides = $this->home_model->getAllSlides();
		$slides = $allslides['rows'];

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('induction/trainingslides_view', ['slides' => $slides]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------------

	public function updateCount()
	{
		if ($this->input->post('staffid')) {
			echo $this->home_model->updateCount($this->input->post('staffid'));
		}
	}

	//---------------------------------------------

	public function preporg()
	{
		$userId = $this->session->userdata('userId');
		$staff = $this->home_model->getSingleUser($userId);
		$mode = $staff->mode;

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('induction/prepintro_view', ['mode' => $mode]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------------

	public function orgtest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 14;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/orgtest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view1');
	}

	//---------------------------------------------

	public function saveorgtest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/orgtest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 10;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/orgtest');
			}

			//get dept questions
			$deptId = 14;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/orgresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function hrtest()
	{
		//set test duration
		$min = 10;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 10;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/hrtest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view2');
	}

	//----------------------------------------------

	public function savehrtest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/hrtest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 20;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/hrtest');
			}

			//get dept questions
			$deptId = 10;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/hrresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//-----------------------------------------------

	public function mkttest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 11;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/mkttest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view3');
	}

	//---------------------------------------------

	public function savemkttest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/mkttest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 30;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/mkttest');
			}

			//get dept questions
			$deptId = 11;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/mktresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function rectest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 4;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/rectest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view4');
	}

	//---------------------------------------------

	public function saverectest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/rectest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 40;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/rectest');
			}

			//get dept questions
			$deptId = 4;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/recresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------------

	public function ittest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 1;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/ittest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view5');
	}

	//-----------------------------------------------------

	public function saveittest()
	{
		$data = $this->input->post();
		
		if (count($data) != 6) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/ittest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 50;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/ittest');
			}

			//get dept questions
			$deptId = 1;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/itresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function optest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 6;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/optest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view6');
	}

	//--------------------------------------------

	public function saveoptest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/optest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 60;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/optest');
			}

			//get dept questions
			$deptId = 6;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/opresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

	public function fintest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 2;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/fintest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view7');
	}

	//---------------------------------------------

	public function savefintest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/fintest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 70;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/fintest');
			}

			//get dept questions
			$deptId = 2;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/finresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function itutest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 3;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/itutest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view8');
	}

	//---------------------------------------------

	public function saveitutest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/itutest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 80;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/itutest');
			}

			//get dept questions
			$deptId = 3;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/ituresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function ctrtest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 5;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/ctrtest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view9');
	}

	//-------------------------------------------

	public function savectrtest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/ctrtest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 90;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/ctrtest');
			}

			//get dept questions
			$deptId = 5;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/ctrresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function admtest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 12;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/admtest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view10');
	}

	//--------------------------------------------

	public function saveadmtest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/orgtest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 100;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/orgtest');
			}

			//get dept questions
			$deptId = 12;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/admresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function audtest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 13;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/audtest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view11');
	}

	//---------------------------------------------

	public function saveaudtest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/audtest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 110;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/audtest');
			}

			//get dept questions
			$deptId = 13;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/audresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function legaltest()
	{
		//set test duration
		$min = 5;
		$sec = 00;
		$testtime = $min . ':' . $sec;

		//fetch questions from unit/dept
		$deptId = 9;
		$allquestions = $this->home_model->getDeptQuestion($deptId);
		if (!$allquestions) {
			$this->session->set_flashdata('success', 'Contact admin to upload questions');
			return redirect('home/index');
		}
		else {
			$questions = $allquestions['rows'];
			//$numofquestions = $allquestions['num'];
		}

		//get options of dept
		$alloptions = $this->home_model->getDeptOptions($deptId);
		$options = '';
		if ($alloptions) {
			$options = $alloptions['rows'];
		}

		//true or false answer
		$true_false = $this->home_model->getDeptTrueFalse($deptId);
		$multis = '';
		if ($true_false) {
			$multis = $true_false['rows'];
		}

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/legaltest_view', ['questions' => $questions,
													'options' => $options,
													'time' => $testtime, 'multis' => $multis]);
		$this->load->view('inc/footer_view12');
	}

	//---------------------------------------------

	public function savelegaltest()
	{
		$data = $this->input->post();
		
		if (count($data) != 5) {
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect('home/legaltest');
		}
		else {
			$userId = $this->session->userdata('userId');
			//update staff mode
			$mode = 120;
			$staffmode = $this->home_model->updateMode($userId, $mode);
			if (!$staffmode) {
				$this->session->set_flashdata('msg', 'Technical error. Retake test');
				return redirect('home/legaltest');
			}

			//get dept questions
			$deptId = 9;
			$allquestions = $this->home_model->getDeptQuestion($deptId);
			$questions = $allquestions['rows'];

			//get multichoice answers
			$allmulti = $this->home_model->getDeptMultiAnswers($deptId);
			$multi = '';
			if ($allmulti) {
				$multi = $allmulti['rows'];
			}

			//get correct truefalse answer
			$alltrue = $this->home_model->getDeptTrueFalseAnswers($deptId);
			$true = '';
			if ($alltrue) {
				$true = $alltrue['rows'];
			}

			$values = array();
			$i = 0;
			foreach($data as $key=>$id){
				$values[$i] = $id;
				$i++;
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/legalresult_view', ['questions' => $questions,
														'multi' => $multi,
														'true' => $true, 'values' => $values]);
			$this->load->view('inc/footer_view');
		}
	}

	//-----------------------------------------------
	
}
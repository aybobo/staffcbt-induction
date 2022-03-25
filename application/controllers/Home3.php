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
		$first = strtotime($staff->firstLoginDate);
        $first = date('Y-m-d', $first);
        $first  = date('Y-m-d', strtotime('+3 day', strtotime($first)));
        $today = date('Y-m-d');

		if ($staff->status == 'Active' || $staff->count >= 3 || $first < $today) {
			$this->session->set_flashdata('success', 'You are not allowed to take the test');
			return redirect('home/index');
		}
		else {

			if ($staff->mode < 120) {
				$this->session->set_flashdata('msg', 'You have to take all the prep module');
				return redirect('home/preporg');
			}
			//set test duration
			$min = 5;
			$sec = 00;
			$testtime = $min . ':' . $sec;

			//$fullname = $this->session->userdata('fullname');

			//fetch questions about citygate
			$allorg = $this->home_model->getAllOrganization();
			
			$k = 0;
			$questionids = '';		
			if (!$allorg) {
				$this->session->set_flashdata('msg', 'Contact admin to upload citygate questions');
				return redirect('home/index');
			}
			else {
				$orgs = $allorg['rows'];
				$numorg = $allorg['num'];

				if ($numorg < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload citygate questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($orgs as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//hr questions==========================
			$allhr = $this->home_model->getAllHr();
			if (!$allhr) {
				$this->session->set_flashdata('msg', 'Contact admin to upload hr questions');
				return redirect('home/index');
			}
			else {
				$hrs = $allhr['rows'];
				$numhr = $allhr['num'];

				if ($numhr < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload hr questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($hrs as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//marketing =============================
			$allmkt = $this->home_model->getAllMkt();
			if (!$allmkt) {
				$this->session->set_flashdata('msg', 'Contact admin to upload marketing questions');
				return redirect('home/index');
			}
			else {
				$mkts = $allmkt['rows'];
				$nummkt = $allmkt['num'];

				if ($nummkt < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload marketing questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($mkts as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//recovery ================================
			$allrec = $this->home_model->getAllRecovery();
			if (!$allrec) {
				$this->session->set_flashdata('msg', 'Contact admin to upload recovery questions');
				return redirect('home/index');
			}
			else {
				$recs = $allrec['rows'];
				$numrec = $allrec['num'];

				if ($numrec < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload recovery questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($recs as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//IT ====================================
			$allit = $this->home_model->getAllIt();
			if (!$allit) {
				$this->session->set_flashdata('msg', 'Contact admin to upload it questions');
				return redirect('home/index');
			}
			else {
				$its = $allit['rows'];
				$numit = $allit['num'];

				if ($numit < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload it questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($its as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//Operations ==========================
			$allops = $this->home_model->getAllOperation();
			if (!$allops) {
				$this->session->set_flashdata('msg', 'Contact admin to upload operations questions');
				return redirect('home/index');
			}
			else {
				$ops = $allops['rows'];
				$numop = $allops['num'];

				if ($numop < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload operations questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($ops as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//finance =============================
			$allfin = $this->home_model->getAllFinance();
			if (!$allfin) {
				$this->session->set_flashdata('msg', 'Contact admin to upload finance questions');
				return redirect('home/index');
			}
			else {
				$fins = $allfin['rows'];
				$numfin = $allfin['num'];

				if ($numfin < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload finance questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($fins as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//itu ==============================
			$allitu = $this->home_model->getAllItu();
			if (!$allitu) {
				$this->session->set_flashdata('msg', 'Contact admin to upload itu questions');
				return redirect('home/index');
			}
			else {
				$itus = $allitu['rows'];
				$numitu = $allitu['num'];

				if ($numitu < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload itu questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($itus as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//control ============================
			$allctr = $this->home_model->getAllControl();
			if (!$allctr) {
				$this->session->set_flashdata('msg', 'Contact admin to upload control questions');
				return redirect('home/index');
			}
			else {
				$ctrs = $allctr['rows'];
				$numctr = $allctr['num'];

				if ($numctr < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload control questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($ctrs as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//admin ============================
			$alladm = $this->home_model->getAllAdmin();
			if (!$alladm) {
				$this->session->set_flashdata('msg', 'Contact admin to upload admin questions');
				return redirect('home/index');
			}
			else {
				$adms = $alladm['rows'];
				$numadm = $alladm['num'];

				if ($numadm < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload admin questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($adms as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//audit ===========================
			$allaudit = $this->home_model->getAllAudit();
			if (!$allaudit) {
				$this->session->set_flashdata('msg', 'Contact admin to upload audit questions');
				return redirect('home/index');
			}
			else {
				$audits = $allaudit['rows'];
				$numaudit = $allaudit['num'];

				if ($numaudit < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload audit questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($audits as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//legal =========================
			$alllegal = $this->home_model->getAllLegal();
			if (!$allaudit) {
				$this->session->set_flashdata('msg', 'Contact admin to upload legal questions');
				return redirect('home/index');
			}
			else {
				$legs = $alllegal['rows'];
				$numleg = $alllegal['num'];

				if ($numleg < 5) {
					$this->session->set_flashdata('msg', 'Contact admin to upload legal questions');
					return redirect('home/index');
				}
				//pass questionId into string
				foreach ($legs as $row) {
					$questionids .= $row->questionId;
					if ($k < 59) {
						$questionids .= ', ';
					}
					$k++;
				}
			}

			//fetch options
			$alloptions = $this->home_model->getAllOptions();
			$options = '';
			if ($alloptions) {
				$options = $alloptions['rows'];
			}

			$numofquestions= ($numorg + $numhr + $nummkt + $numrec + $numit + $numop + $numfin + $numitu + $numctr + $numadm + $numaudit + $numleg);

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('test/inductiontest_view', ['orgs' => $orgs, 'hrs' => $hrs, 'mkts' => $mkts,
														'recs' => $recs, 'its' => $its, 'ops' => $ops,
														'fins' => $fins, 'itus' => $itus, 'ctrs' => $ctrs,
														'adms' => $adms, 'audits' => $audits, 'questionids' => $questionids,
														'legs' => $legs, 'numofquestions' => $numofquestions,
														'options' => $options, 'time' => $testtime, 'staff' => $staff]);
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

	public function savetest()
	{
		$data = $this->input->post();
		$num = $data['numofquestions']; //total number of questions
		$count = $data['noofattempts']; //number of test attempts
		$questionids = $data['questionids'];
		$allids = explode(',', $questionids);
		unset($data['questionids']);
		unset($data['numofquestions']);
		unset($data['noofattempts']);

		$lastattempt = date('Y-m-d H:i:s');
		$userId = $this->session->userdata('userId');
		//get staff record from scores table
		$scoresheet = $this->home_model->getScoreRecord($userId);
		if (!empty($data)) {
			$attemptedquestions = count($data); //total number of attempted question
			$correct = 0;

			$kk = 0;
			$splitid = '';
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

	           	$splitid .= $parts[0];
				if ($kk < count($data)-1) {
					$splitid .= ', ';
				}
				$kk++;
			}

			$splitansweredids = explode(',' , $splitid);

			$x = 0;
			if (count($data) < count($allids)) {
				foreach ($allids as $id) {
					if (in_array($id, $splitansweredids)) {
						continue;
					}
					else {
						//unanswered question
						$unaswered = $this->home_model->submitNotAnswered($id, $userId, $lastattempt);
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
		$status = $staff->status;

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('induction/prepintro_view', ['mode' => $mode, 'status' => $status]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------------

	public function orgtest()
	{
		$userId = $this->session->userdata('userId');
        $staff = $this->home_model->getSingleUser($userId);

		if(isset($_POST['val']))
        {
            if ($_POST['deptId'] == '') {
            	$this->session->set_flashdata('msg', 'Choose a department');
				return redirect('home/preporg');
            }
            else {
            	$val = $this->input->post('val');
            	$deptId = $this->input->post('deptId');
            }
        }
        else {
            $val = $this->uri->segment(3);
            $mode = $staff->mode;

            if ($mode < 10) {
            	$deptId = 14; //about citygate
            }
            elseif ($mode > 9 && $mode < 20) {
            	$deptId = 10; //hr
            }
            elseif ($mode > 19 && $mode < 30) {
            	$deptId = 11; //mkt
            }
            elseif ($mode > 29 && $mode < 40) {
            	$deptId = 4; //recovery
            }
            elseif ($mode > 39 && $mode < 50) {
            	$deptId = 1; //IT
            }
            elseif ($mode > 49 && $mode < 60) {
            	$deptId = 6; //operations
            }
            elseif ($mode > 59 && $mode < 70) {
            	$deptId = 2; //finance
            }
            elseif ($mode > 69 && $mode < 80) {
            	$deptId = 3; //itu
            }
            elseif ($mode > 79 && $mode < 90) {
            	$deptId = 5; //control
            }
            elseif ($mode > 89 && $mode < 100) {
            	$deptId = 12; //admin
            }
            elseif ($mode > 99 && $mode < 110) {
            	$deptId = 13; //audit
            }
            else {
            	$deptId = 9; //legal
            }
        }

        //get induction videoId
        $video = $this->home_model->getVideo($deptId);

        //get department name
        $dept = $this->home_model->getDepartmentName($deptId);

        //set test duration
        $min = 1;
        $sec = 00;
        $testtime = $min . ':' . $sec;

        //fetch questions from unit/dept
        $allquestions = $this->home_model->getDeptQuestion($deptId);
        if (!$allquestions) {
            $this->session->set_flashdata('success', 'Contact admin to upload questions');
            return redirect('home/index');
        }
        else {
            $questions = $allquestions['rows'];
            $numofquestions = $allquestions['num'];
        }

        $rowid = '';
		$j = 0;

		foreach ($questions as $row) {
			$rowid .= $row->questionId;
			if ($j < $numofquestions-1) {
				
				$rowid .= ', ';
			}
			$j++;
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
        $this->load->view('test/orgtest_view', ['questions' => $questions, 'staff' => $staff,
                                                    'options' => $options, 'val' => $val, 'numofquestions' => $numofquestions,
                                                    'time' => $testtime, 'multis' => $multis, 'deptId' => $deptId,
                                                	'video' => $video, 'dept' => $dept, 'rowid' => $rowid]);
        $this->load->view('inc/footer_view1');
	}

	//---------------------------------------------

	public function saveorgtest()
	{

		$data = $this->input->post();
		$deptname = $data['deptName'];
		unset($data['deptName']);
		
		if (count($data) != 5) {
			$val = 5;
			$this->session->set_flashdata('msg', 'You are expected to attempt all questions');
			return redirect(base_url()."home/orgtest/".$val);
			//return redirect('home/orgtest');
		}
		else {
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

			$score = (($correct/5) * 100);
			$score = (round($score, 2));
			
			$userId = $this->session->userdata('userId');
			$user = $this->home_model->getSingleUser($userId);
			$mode = $user->mode;
			$testval = ($mode%10);

			if ($testval == 0) { //first attempt
				if ($mode == 0) { //organization
					if ($score == 100) {
						$mode = 10; //check if user got all the questions, go to hr
						$deptId = 10;
					}
					else {
						$mode = 5; //retake same dept
						$deptId = 14;
					}
				}
				elseif ($mode == 10) { //hr
					if ($score == 100) {
						$mode = 20; 
						$deptId = 11;
					}
					else {
						$mode = 15;
						$deptId = 10;
					}
				}

				elseif ($mode == 20) { //mkt
					if ($score == 100) {
						$mode = 30;
						$deptId = 4;
					}
					else {
						$mode = 25;
						$deptId = 11;
					}
				}

				elseif ($mode == 30) { //recovery
					if ($score == 100) {
						$mode = 40;
						$deptId = 1;
					}
					else {
						$mode = 35;
						$deptId = 4;
					}
				}

				elseif ($mode == 40) { //IT
					if ($score == 100) {
						$mode = 50;
						$deptId = 6;
					}
					else {
						$mode = 45;
						$deptId = 1;
					}
				}

				elseif ($mode == 50) { //operations
					if ($score == 100) {
						$mode = 60;
						$deptId = 2;
					}
					else {
						$mode = 55;
						$deptId = 6;
					}
				}

				elseif ($mode == 60) { //finance
					if ($score == 100) {
						$mode = 70;
						$deptId = 3;
					}
					else {
						$mode = 65;
						$deptId = 2;
					}
				}

				elseif ($mode == 70) { //ITU
					if ($score == 100) {
						$mode = 80;
						$deptId = 5;
					}
					else {
						$mode = 75;
						$deptId = 3;
					}
				}

				elseif ($mode == 80) { //ctr
					if ($score == 100) {
						$mode = 90;
						$deptId = 12;
					}
					else {
						$mode = 85;
						$deptId = 5;
					}
				}

				elseif ($mode == 90) { //admin
					if ($score == 100) {
						$mode = 100;
						$deptId = 13;
					}
					else {
						$mode = 95;
						$deptId = 12;
					}
				}
				elseif ($mode == 100) { //audit
					if ($score == 100) {
						$mode = 110;
						$deptId = 9;
					}
					else {
						$mode = 105;
						$deptId = 13;
					}
				}
				else { //legal
					$mode = ($score == 100) ? 120 : 115;
					if ($score == 100) {
						$mode = 120;
						$deptId = 0;
					}
					else {
						$mode = 115;
						$deptId = 9;
					}
				}

				//update mode
				$staffmode = $this->home_model->updateMode($userId, $mode);
				if (!$staffmode) {
					$val = 5;
					$this->session->set_flashdata('msg', 'Technical error. Retake test');
					return redirect(base_url()."home/orgtest/".$val);
				}

				//load view
				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('test/firstattemptresult_view', ['score' => $score, 'deptId' => $deptId, 'mode' => $mode, 'deptname' => $deptname]);
				$this->load->view('inc/footer_view');
			}
			else { //2nd attempt
				if ($mode == 5) { //2nd attempt at organization
					$mode = 10; //next hr
					$deptId = 14;
					$ndeptId = 10;
				}
				elseif ($mode == 15) { //2nd attempt at hr
					$mode = 20; // next mkt
					$deptId = 10;
					$ndeptId = 11;
				}
				elseif ($mode == 25) { //2nd attempt mkt, next recovery
					$mode = 30;
					$deptId = 11;
					$ndeptId = 4;
				}
				elseif ($mode == 35) { //2nd attempt recovery, next IT
					$mode = 40;
					$deptId = 4;
					$ndeptId = 1;
				}
				elseif ($mode == 45) { //2nd attempt IT, next operations
					$mode = 50;
					$deptId = 1;
					$ndeptId = 6;
				}
				elseif ($mode == 55) { //2nd attempt operations, next finance
					$mode = 60;
					$deptId = 6;
					$ndeptId = 2;
				}
				elseif ($mode == 65) { //2nd attempt finance, next ITU
					$mode = 70;
					$deptId = 2;
					$ndeptId = 3;
				}
				elseif ($mode == 75) { //2nd attempt ITU, next control
					$mode = 80;
					$deptId = 3;
					$ndeptId = 5;
				}
				elseif ($mode == 85) { //2nd attempt control, next admin
					$mode = 90;
					$deptId = 5;
					$ndeptId = 12;
				}
				elseif ($mode == 95) { //2nd attempt admin next audit
					$mode = 100;
					$deptId = 12;
					$ndeptId = 13;
				}
				elseif ($mode == 105) { //2nd attempt audit, next legal
					$mode = 110;
					$deptId = 13;
					$ndeptId = 9;
				}
				else { //2nd attempt legal, next final test
					$mode = 120;
					$deptId = 9;
					$ndeptId = 0; //final test
				}
				
				$staffmode = $this->home_model->updateMode($userId, $mode);
				if (!$staffmode) {
					$val = 5;
					$this->session->set_flashdata('msg', 'Technical error. Retake test');
					return redirect(base_url()."home/orgtest/".$val);
				}

				//get dept questions
				$allquestions = $this->home_model->getDeptQuestionNoRandom($deptId);
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
				$this->load->view('test/orgresult_view', ['questions' => $questions, 'deptname' => $deptname,
															'multi' => $multi, 'deptId' => $ndeptId,
															'true' => $true, 'values' => $values]);
				$this->load->view('inc/footer_view');
			}
		}
	}

	//-----------------------------------------------

	public function updateModeOnTimeOut()
	{
		$rowid = $this->input->get('rowid');
		$mode = $this->input->get('mode');
		$deptId = $this->input->get('deptId');
		$deptname = $this->home_model->getDepartmentName($deptId);
		$mode += 5;
		$userId = $this->session->userdata('userId');
		$newMode = $this->home_model->updateMode($userId, $mode);
		$user = $this->home_model->getSingleUser($userId);

		$tempMode = $user->mode;

		if ($tempMode%10 == 5) { //first attempt
			return redirect('admin/logout');
		}
		//$deptId = $this->input->get('dept');

		if ($mode == 10) { //2nd attempt at organization
			$deptId = 14;
			$ndeptId = 10;
		}
		elseif ($mode == 20) { //2nd attempt at hr
			$deptId = 10;
			$ndeptId = 11;
		}
		elseif ($mode == 30) { //2nd attempt mkt, next recovery
			$deptId = 11;
			$ndeptId = 4;
		}
		elseif ($mode == 40) { //2nd attempt recovery, next IT
			$deptId = 4;
			$ndeptId = 1;
		}
		elseif ($mode == 50) { //2nd attempt IT, next operations
			$deptId = 1;
			$ndeptId = 6;
		}
		elseif ($mode == 60) { //2nd attempt operations, next finance
			$deptId = 6;
			$ndeptId = 2;
		}
		elseif ($mode == 70) { //2nd attempt finance, next ITU
			$deptId = 2;
			$ndeptId = 3;
		}
		elseif ($mode == 80) { //2nd attempt ITU, next control
			$deptId = 3;
			$ndeptId = 5;
		}
		elseif ($mode == 90) { //2nd attempt control, next admin
			$deptId = 5;
			$ndeptId = 12;
		}
		elseif ($mode == 100) { //2nd attempt admin next audit
			$deptId = 12;
			$ndeptId = 13;
		}
		elseif ($mode == 110) { //2nd attempt audit, next legal
			$deptId = 13;
			$ndeptId = 9;
		}
		else { //2nd attempt legal, next final test
			$deptId = 9;
			$ndeptId = 0; //final test
		}

		//get dept questions
		$allquestions = $this->home_model->getDeptQuestionNoRandom($deptId);
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

		$values = explode(",", $rowid);

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('test/timeoutresult_view', ['questions' => $questions, 'deptname' => $deptname,
													'multi' => $multi, 'deptId' => $ndeptId,
													'true' => $true, 'values' => $values]);
		$this->load->view('inc/footer_view');
	}

	//----------------------------------------------

	
}
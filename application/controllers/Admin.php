<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	//---------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
	}

	//----login page------------------

	public function index()
	{
		$this->load->view('inc/header_view');
		$this->load->view('login/index_view');
		$this->load->view('inc/footer_view');
	}

	//--------- login user -------------

	public function login ()
	{
		//validate login details
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run()) {
			$useremail = $this->input->post('email');
			$password = $this->input->post('password');
			$password = hash('sha256', $password . KEY);

			//check if user record exists in db
			$res = $this->admin_model->islogin($useremail, $password);
			
			if($res) {
		    	$status = $res->status;
		    	$fname = $res->fname;
		    	$userid = $res->userId;
		    	$role = $res->role;
		    	$dept = $res->deptId;
		    	$fullname = $res->fname . ' ' . $res->lname;

		    	//check if account has been deactivated
		    	if ($status == 'Inactive') {
		    	  $this->session->set_flashdata('msg','Account Suspended. Contact Admin');
					return redirect('admin/index');	
		    	}
					
					//set session variables
					$this->session->set_userdata('username', $fname); //first name
					$this->session->set_userdata('userId', $userid); //userId
					$this->session->set_userdata('role', $role); //user role
					$this->session->set_userdata('dept', $dept); //user role
					$this->session->set_userdata('userEmail', $useremail); //email
					$this->session->set_userdata('fullname', $fullname); //fullname
					return redirect('home/index'); 
			}

			//Login failed
			else {
				$this->session->set_flashdata('msg','Invalid Email/Password');
				return redirect('admin/index'); 
			}
		}

		//validation fails, back to home page
		else {
			$this->session->set_flashdata('msg','Fill in your email and password');
			return redirect('admin/index'); 
		}
	}

	//------------------------------------------

	public function setpassword()
	{
		$id = '';
		if(isset($_GET['id'])) {
          $id = $_GET['id'];
        }
        else {
            $id = $this->uri->segment(3);
        }

		$this->load->view('inc/header_view');
		$this->load->view('login/setpassword_view', ['id' => $id]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------------

	public function activateaccount()
	{
		// get hidden form fields
    	$id = $this->input->post('id');

    	//validate user input
    	$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password',
										'matches[password]|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();
			$data['password'] = hash('sha256', $data['password'] . KEY);
			$data['key'] = 5;
			$chkUsr = $this->admin_model->getSingleUser($data);

			if ($chkUsr) {
				if ($data['key'] == $chkUsr->num) {
					$this->session->set_flashdata('msg','Link already used');
					return redirect('admin/index');
				}
				$data['firstLoginDate'] = date('Y-m-d');
				$savepassword = $this->admin_model->saveNewPassword($data);

				if (!$savepassword) {
					$this->session->set_flashdata('msg','Account activation failed');
					return redirect(base_url()."admin/setpassword/".$id);
				}

				$this->session->set_flashdata('success','Account activation successful');
				return redirect('admin/index');
			}
			else {
				$this->session->set_flashdata('msg','Invalid credentials');
				return redirect(base_url()."admin/setpassword/".$id);
			}
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('login/setpassword_view', ['id' => $id]);
			$this->load->view('inc/footer_view');
		}
	}

	//------------------------------------------

	public function forgotpassword()
	{
		$this->load->view('inc/header_view');
		$this->load->view('login/forgotpassword_view');
		$this->load->view('inc/footer_view');
	}

	//-------------------------------------------

	public function sendpasswordresetlink()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

		if ($this->form_validation->run()) {
			$email = $this->input->post('email');
			$chkemail = $this->admin_model->checkEmail($email);
			
			if (!$chkemail) {
				$this->session->set_flashdata('msg','No account with such email');
				return redirect('admin/index');
			}

			$now = date('Y-m-d H:i:s');
			$token = $email . $now;
			$token = hash('sha256', $token . KEY);
			$num = 4;
			$saveToken = $this->admin_model->updateToken($email, $token, $num);

			if (!$saveToken) {
				$this->session->set_flashdata('msg','Error saving to database. Try again');
				return redirect('admin/forgotpassword');
			}

			$config = array(
							'protocol' => 'sendmail',
							'mailpath' => '/usr/sbin/sendmail',
							'charset'	=>	'iso-8859-1',
							'mailtype'	=>	'html',
							'smtp_port'	=>	25,
							'wordwrap'	=>	TRUE
						);

			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from('no-reply@citygate elearning');
			$this->email->to($email);
			$this->email->subject('Password Reset');

			$message = "Click the link below to reset your password
						<br></p>";
			$message .= '<a href="http:elearning.empiretrustmfb.com/admin/resetpassword/
						?token=' . $token . '">Click here to reset your password</a>';

			$this->email->message($message);
			$this->email->send();
			$this->session->set_flashdata('success','Check your email for the password reset link');
			return redirect('admin/forgotpassword');
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('login/forgotpassword_view');
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function resetpassword()
	{
		$token = '';
		if(isset($_GET['token'])) {
          $token = $_GET['token'];
        }
        else {
            $token = $this->uri->segment(3);
        }

		$this->load->view('inc/header_view');
		$this->load->view('login/resetpassword_view', ['token' => $token]);
		$this->load->view('inc/footer_view');
	}

	//--------------------------------------------

	public function updatepassword()
	{
		//get hidden form field values
		$token = $this->input->post('token');

		//validate user input
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Password2', 'matches[password]|required');

		if($this->form_validation->run()) {
			$data = $this->input->post();
			$data['password']= hash('sha256', $data['password'] . KEY);
			$data['num'] = 10;

			$verifyToken = $this->admin_model->verifyToken($data);

			if ($verifyToken->token != $data['token']) {
				$this->session->set_flashdata('msg', 'Unable to reset password');
				return redirect('admin/index');
			}
			elseif ($verifyToken->num == $data['num']) {
				$this->session->set_flashdata('msg', 'Link has been used');
				return redirect('admin/index');
			}
			else {
				$updPassword = $this->admin_model->updatePassword($data);

				if(!$updPassword) {
					$this->session->set_flashdata('msg','Unable to update password');
					return redirect(base_url()."admin/resetpassword/".$token);
				}

				$this->session->set_flashdata('success', 'Password reset successful');
				return redirect('admin/index');
			}
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('login/resetpassword_view', ['token' => $token]);
			$this->load->view('inc/footer_view');
		}
	}

	//-----------------------------------------

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin/index');
	}

	//-------------------------------------

	public function createstaff()
	{
		//get active branch
		$branches = $this->admin_model->getActiveBranches();
		$branches = $branches['rows'];

		//get active dept
		$depts = $this->admin_model->getActiveDepartment();
		$depts = $depts['rows'];

		$this->load->view('inc/header_view');
		$this->load->view('dashboard/createstaff_view',['branches' => $branches, 'depts' => $depts]);
		$this->load->view('inc/footer_view');

	}

	//-----------------------------------------

	public function fetchposts()
	{
		if ($this->input->post('deptId')) {
			echo $this->admin_model->getActivePosts($this->input->post('deptId'));
		}
	}

	//----------------------------------------

	public function addstaff()
	{
		//set validation rules
		$this->form_validation->set_rules('fname', 'First name', 'required|trim|alpha');
		$this->form_validation->set_rules('lname', 'Last name', 'required|trim|alpha');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('branch', 'Branch', 'required');
		$this->form_validation->set_rules('dept', 'Department', 'required');
		$this->form_validation->set_rules('post', 'Post', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm password', 'matches[password]|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();
			$data['dateCreated'] = date('Y-m-d H:i:s');
			$data['createdBy'] = $data['fname'] . ' ' . $data['lname'];
			$data['password'] = hash('sha256', $data['password'] . KEY);
			$data['role'] = 1;
			if ($data['dept'] == 1 || $data['dept'] == 10) {
				$data['role'] = 2;
			}

			//split email to get domain name
			$parts = explode("@", $data['email']);

			if (($parts[1] == 'empiretrustmfb.com') || ($parts[1] == 'citygateglobal.com')) {
				$empire = '@empiretrustmfb.com';
				$city = '@citygateglobal.com';
				$empiremail = $parts[0] . $empire;
				$citymail = $parts[0] . $city;

				//check if staff record exists
				$chkstaff = $this->admin_model->checkUser($empiremail, $citymail);

				if ($chkstaff) {
					$this->session->set_flashdata('msg','Staff record already exists');
					return redirect('admin/createstaff');
				}

				//add staff record
				$addstaff = $this->admin_model->addStaff($data);

				if (!$addstaff) {
					$this->session->set_flashdata('success','Unable to add staff');
					return redirect('admin/createstaff');
				}
				
				$this->session->set_flashdata('success', 'Staff successfully added');
				return redirect('admin/index');
			}
			else {
				$this->session->set_flashdata('msg','Only official emails are allowed');
				return redirect('admin/createstaff');
			}
		}
		else {
			//get active branch
			$branches = $this->admin_model->getActiveBranches();
			$branches = $branches['rows'];

			//get active dept
			$depts = $this->admin_model->getActiveDepartment();
			$depts = $depts['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('dashboard/createstaff_view',['branches' => $branches, 'depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

}
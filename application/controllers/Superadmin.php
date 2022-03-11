<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends CI_Controller {

	//---------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		$role = $this->session->userdata('role');
		if($role != 2) 
			redirect('home/index');
		$this->load->model('superadmin_model');
	}

	//-----------------------

	public function loadBranch()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get all branches
			$result = $this->superadmin_model->getAllBranches();

			$records = $result['rows'];
			$num = $result['num'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('branch/branchpage_view', ['rows' => $records, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//-----------------------------------

	public function addbranch()
	{
		//validate login details
		$this->form_validation->set_rules('branch', 'Branch name', 'trim|required');

		if ($this->form_validation->run()) {
			$branchName = $this->input->post('branch');

			//check if branch name does not exist
			$chk = $this->superadmin_model->checkBranch($branchName);

			if ($chk) {
				$this->session->set_flashdata('msg', 'Branch name exists');
				return redirect('superadmin/loadbranch');
			}
			else {
				//add branch
				$addbranch = $this->superadmin_model->addBranch($branchName);

				if (!$addbranch) {
					$this->session->set_flashdata('msg', 'Unable to add branch');
					return redirect('superadmin/loadbranch');
				}

				$this->session->set_flashdata('success', 'Branch successfully added');
				return redirect('superadmin/loadbranch');
			}
	
		}
		else {
			//get all branches
			$result = $this->superadmin_model->getAllBranches();

			$records = $result['rows'];
			$num = $result['num'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('branch/branchpage_view', ['rows' => $records, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//-----------------------------------

	public function editbranch()
	{
		if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        else {
        	$id = $this->uri->segment(3);
        }

		//fetch branch with id
		$getbranch = $this->superadmin_model->getBranch($id);

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('branch/editbranch_view', ['row' => $getbranch]);
		$this->load->view('inc/footer_view');
	}

	//-------------------------------------

	public function updatebranch()
	{
		$id = $this->input->post('id');
		
		//set validation rules
		$this->form_validation->set_rules('branch', 'Branch name', 'trim|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			if ($data['branch'] == $data['oName']) { //same branch name
				$updbranch = $this->superadmin_model->updateBranch($data);

				$this->session->set_flashdata('success','Branch update successful');
				return redirect('superadmin/loadbranch');
			}
			else {
				//check if branch name exists
				$branchName = $data['branch'];
				$chk = $this->superadmin_model->checkBranch($branchName);

				if ($chk) {
					$this->session->set_flashdata('msg','Branch name exists');
					return redirect(base_url()."superadmin/editbranch/".$id);
				}

				$updbranch = $this->superadmin_model->updateBranch($data);

				if ($updbranch) {
					$this->session->set_flashdata('success','Branch update successful');
					return redirect('superadmin/loadbranch');
				}
				else {
					$this->session->set_flashdata('msg','Update failed');
					return redirect(base_url()."superadmin/editbranch/".$id);
				}
			}
		}
		else {
			//fetch branch with id
			$getbranch = $this->superadmin_model->getBranch($id);

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('branch/editbranch_view', ['row' => $getbranch]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------

	public function loadDept()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get all branches
			$result = $this->superadmin_model->getAllDepartment();

			$records = $result['rows'];
			$num = $result['num'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('department/deptpage_view', ['rows' => $records, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function adddept()
	{
		//validate login details
		$this->form_validation->set_rules('dept', 'Department name', 'trim|required');

		if ($this->form_validation->run()) {
			$dept = $this->input->post('dept');

			//check if branch name does not exist
			$chk = $this->superadmin_model->checkDepartment($dept);

			if ($chk) {
				$this->session->set_flashdata('msg', 'Department name exists');
				return redirect('superadmin/loaddept');
			}
			else {
				//add branch
				$adddept = $this->superadmin_model->addDepartment($dept);

				if (!$adddept) {
					$this->session->set_flashdata('msg', 'Unable to add department');
					return redirect('superadmin/loaddept');
				}

				$this->session->set_flashdata('success', 'Department successfully added');
				return redirect('superadmin/loaddept');
			}
	
		}
		else {
			//get all branches
			$result = $this->superadmin_model->getAllDepartment();

			$records = $result['rows'];
			$num = $result['num'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('department/deptpage_view', ['rows' => $records, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function editdept()
	{
		if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        else {
        	$id = $this->uri->segment(3);
        }

		//fetch branch with id
		$getdept = $this->superadmin_model->getDept($id);

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('department/editdept_view', ['row' => $getdept]);
		$this->load->view('inc/footer_view');
	}

	//-------------------------------------

	public function updatedept()
	{
		$id = $this->input->post('id');
		
		//set validation rules
		$this->form_validation->set_rules('dept', 'Department name', 'trim|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			if ($data['dept'] == $data['oName']) { //same branch name
				$upddept = $this->superadmin_model->updateDept($data);

				$this->session->set_flashdata('success','Department update successful');
				return redirect('superadmin/loaddept');
			}
			else {
				//check if branch name exists
				$dept = $data['dept'];
				$chk = $this->superadmin_model->checkDepartment($dept);

				if ($chk) {
					$this->session->set_flashdata('msg','dept name exists');
					return redirect(base_url()."superadmin/editdept/".$id);
				}

				$upddept = $this->superadmin_model->updateDept($data);

				if ($upddept) {
					$this->session->set_flashdata('success','Department update successful');
					return redirect('superadmin/loaddept');
				}
				else {
					$this->session->set_flashdata('msg','Update failed');
					return redirect(base_url()."superadmin/editdept/".$id);
				}
			}
		}
		else {
			//fetch branch with id
			$getdept = $this->superadmin_model->getDept($id);

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('department/editdept_view', ['row' => $getdept]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------

	public function loadpost()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get posts
			$allposts = $this->superadmin_model->getAllPost();
			$posts = '';
			$num = 0;
			if ($allposts) {
				$posts = $allposts['rows'];
				$num = $allposts['num'];
			}

			//get departments
			$depts = $this->superadmin_model->getActiveDepartment();
			if (!$depts) {
				$this->session->set_flashdata('msg','No department found');
				return redirect('superadmin/loaddept');
			}
			else {
				$depts = $depts['rows'];
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('post/postpage_view',
							['posts' => $posts, 'num' => $num, 'depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------

	public function addpost()
	{
		//set validation rules
		$this->form_validation->set_rules('post', 'Post name', 'trim|required');
		$this->form_validation->set_rules('dept', 'Department name', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			//check if post exists
			$chk = $this->superadmin_model->checkPost($data);
			if ($chk) {
				$this->session->set_flashdata('msg','Post already exists');
				return redirect('superadmin/loadpost');
			}

			//add branch
			$addpost = $this->superadmin_model->addPost($data);

			if (!$addpost) {
				$this->session->set_flashdata('msg', 'Unable to add post');
				return redirect('superadmin/loadpost');
			}

			$this->session->set_flashdata('success', 'Post successfully added');
			return redirect('superadmin/loadpost');
		}
		else { //validation fails
			//get posts
			$allposts = $this->superadmin_model->getAllPost();
			$posts = '';
			$num = 0;
			if ($allposts) {
				$posts = $allposts['rows'];
				$num = $allposts['num'];
			}

			//get departments
			$depts = $this->superadmin_model->getActiveDepartment();
			if (!$depts) {
				$this->session->set_flashdata('msg','No department found');
				return redirect('superadmin/loaddept');
			}
			else {
				$depts = $depts['rows'];
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('post/postpage_view',
							['posts' => $posts, 'num' => $num, 'depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------

	public function editpost()
	{
		if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        else {
        	$id = $this->uri->segment(3);
        }

		//get post with id
		$post = $this->superadmin_model->getPost($id);

		$id = $post->deptId;

		//get dept with id
		$dept = $this->superadmin_model->getDept($id);

		//get all other department
		$depts = $this->superadmin_model->getOtherDepts($id);
		$depts = $depts['rows'];


		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('post/editpost_view',
						['post' => $post, 'dept' => $dept, 'depts' => $depts]);
		$this->load->view('inc/footer_view');
	}

	//----------------------------------------

	public function updatepost()
	{
		$id = $this->input->post('id');
		//$deptId = $this->input->post('deptId');
		
		//set validation rules
		$this->form_validation->set_rules('post', 'Post name', 'trim|required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			//chk post
			$chkpost = $this->superadmin_model->checkPost($data);

			if ($chkpost) {
				if ($data['dept'] == $data['deptId']) {
					$updatepost = $this->superadmin_model->updatePost($data);
					$this->session->set_flashdata('success','Post name updated');
					return redirect('superadmin/loadpost');
				}
				else {
					$this->session->set_flashdata('msg','Post name exists');
					return redirect(base_url()."superadmin/editpost/".$id);
				}
			}
			else {
				$updatepost = $this->superadmin_model->updatePost($data);
				$this->session->set_flashdata('success','Post name updated');
				return redirect('superadmin/loadpost');
			}
		}
		else {
			//get post with id
			$post = $this->superadmin_model->getPost($id);

			$id = $post->deptId;

			//get dept with id
			$dept = $this->superadmin_model->getDept($id);

			//get all other department
			$depts = $this->superadmin_model->getOtherDepts($id);
			$depts = $depts['rows'];


			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('post/editpost_view',
							['post' => $post, 'dept' => $dept, 'depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function loadstaff()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get active branch
			$branches = $this->superadmin_model->getActiveBranches();
			if (!$branches) {
				$this->session->set_flashdata('success','Upload branch');
				return redirect('superadmin/loadbranch');
			}
			else {
				$branches = $branches['rows'];
			}

			//get active dept
			$depts = $this->superadmin_model->getActiveDepartment();
			if (!$depts) {
				$this->session->set_flashdata('success','Upload department');
				return redirect('superadmin/loaddept');
			}
			else {
				$depts = $depts['rows'];
			}

			//get all staff
			$allstaffs = $this->superadmin_model->getAllStaff();
			$num = 0;
			$staffs = '';
			if ($allstaffs) {
				$staffs = $allstaffs['rows'];
				$num = $allstaffs['num'];
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('staff/staffpage_view',['branches' => $branches, 'depts' => $depts,
													'staffs' => $staffs, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function fetchposts()
	{
		if ($this->input->post('deptId')) {
			echo $this->superadmin_model->getActivePosts($this->input->post('deptId'));
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
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();
			$data['dateCreated'] = date('Y-m-d H:i:s');
			$data['createdBy'] = $this->session->userdata('fullname');

			//split email to get domain name
			$parts = explode("@", $data['email']);

			if (($parts[1] == 'empiretrustmfb.com') || ($parts[1] == 'citygateglobal.com')) {
				$empire = '@empiretrustmfb.com';
				$city = '@citygateglobal.com';
				$empiremail = $parts[0] . $empire;
				$citymail = $parts[0] . $city;

				//check if staff record exists
				$chkstaff = $this->superadmin_model->checkUser($empiremail, $citymail);

				if ($chkstaff) {
					$this->session->set_flashdata('msg','Staff record already exists');
					return redirect('superadmin/loadstaff');
				}

				//add staff record
				$addstaff = $this->superadmin_model->addStaff($data);

				if (!$addstaff) {
					$this->session->set_flashdata('success','Unable to add staff');
					return redirect('superadmin/loadstaff');
				}
				
				//notify staff
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
				$this->email->to($data['email']);
				$this->email->subject('Citygate E-learning Portal - Activate Your Account');

				$message = "<p>Hello " . $data['fname'] . ", <br>
						 an account has been created for on the Citygate e-learning portal. Click on the link below to activate your account.<br></p>";

				$message .= '<a href="http:elearning.empiretrustmfb.com/
							admin/setpassword/?id=' . $addstaff . 
						'">Click here to activate your account</a>';

				$this->email->message($message);
				$this->email->send();
				$this->session->set_flashdata('success', 'Staff successfully added');
				return redirect('superadmin/loadstaff');
			}
			else {
				$this->session->set_flashdata('msg','Only official emails are allowed');
				return redirect('superadmin/loadstaff');
			}
		}
		else { //validation fails
			//get active branch
			$branches = $this->superadmin_model->getActiveBranches();
			if (!$branches) {
				$this->session->set_flashdata('success','Upload branch');
				return redirect('superadmin/loadbranch');
			}
			else {
				$branches = $branches['rows'];
			}

			//get active dept
			$depts = $this->superadmin_model->getActiveDepartment();
			if (!$depts) {
				$this->session->set_flashdata('success','Upload department');
				return redirect('superadmin/loaddept');
			}
			else {
				$depts = $depts['rows'];
			}

			//get all staff
			$allstaffs = $this->superadmin_model->getAllStaff();
			$num = 0;
			$staffs = '';
			if ($allstaffs) {
				$staffs = $allstaffs['rows'];
				$num = $allstaffs['num'];
			}

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('staff/staffpage_view',['branches' => $branches, 'depts' => $depts,
													'staffs' => $staffs, 'num' => $num]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------

	/*public function getstaffid()
	{
		$id = $_GET['id'];
		return redirect(base_url()."superadmin/editstaff/".$id);
	}*/

	//------------------------------------

	public function editstaff()
	{
		if(isset($_GET['id'])) {
            $userId = $_GET['id'];
        }
        else {
        	$userId = $this->uri->segment(3);
        }

		$staff = $this->superadmin_model->getStaffWithId($userId);

		//get other departments
		$id = $staff->deptId;
		$depts = $this->superadmin_model->getOtherDepts($id);
		$depts = $depts['rows'];

		//get other branches
		$branchId = $staff->branchId;
		$branches = $this->superadmin_model->getOtherBranches($branchId);
		$branches = $branches['rows'];

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('staff/editstaff_view',['branches' => $branches, 'depts' => $depts,
												'staff' => $staff]);
		$this->load->view('inc/footer_view');
	}

	//---------------------------------------

	public function updatestaff()
	{
		$userId = $this->input->post('id');

		//set validation rules
		$this->form_validation->set_rules('fname', 'First name', 'required|trim|alpha');
		$this->form_validation->set_rules('lname', 'Last name', 'required|trim|alpha');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('branch', 'Branch', 'required');
		$this->form_validation->set_rules('dept', 'Department', 'required');
		$this->form_validation->set_rules('post', 'Post', 'required');
		$this->form_validation->set_rules('role', 'Role', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();
			$data['dateLastModified'] = date('Y-m-d H:i:s');
			$data['lastModifiedBy'] = $this->session->userdata('fullname');

			//split email
			$parts = explode("@", $data['email']);

			if (($parts[1] == 'empiretrustmfb.com') || ($parts[1] == 'citygateglobal.com')) {
				$empire = '@empiretrustmfb.com';
				$city = '@citygateglobal.com';
				$empiremail = $parts[0] . $empire;
				$citymail = $parts[0] . $city;

				// check if staff record exists, to prevent overwriting
				$chkstaff = $this->superadmin_model->checkUser($empiremail, $citymail);

				if ($chkstaff) {
					if ($chkstaff->userId == $data['id']) { //same person
						// update staff record
						$updstaff = $this->superadmin_model->updateStaff($data);
						$this->session->set_flashdata('success','Staff record updated');
						return redirect('superadmin/loadstaff');
					}
					else {
						//record already exist
						$id = $userId;
						$this->session->set_flashdata('msg','Email not available');
						return redirect(base_url()."superadmin/editstaff/".$id);
					}
				}
				else {
					//update
					$updstaff = $this->superadmin_model->updateStaff($data);

					//notify staff
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
					$this->email->to($data['email']);
					$this->email->subject('Citygate E-learning Portal - Activate Your Account');

					$message = "<p>Hello " . $data['fname'] . ", <br>
							 an account has been created for on the Citygate e-learning portal. Click on the link below to activate your account.<br></p>";

					$message .= '<a href="http:elearning.empiretrustmfb.com/
								admin/setpassword/?id=' . $userId . 
							'">Click here to activate your account</a>';

					$this->email->message($message);
					$this->email->send();
					$this->session->set_flashdata('success', 'Staff successfully updated');
					return redirect('superadmin/loadstaff');
				}
			}
			else {
				$id = $userId;
				$this->session->set_flashdata('msg','Only official emails are allowed');
				return redirect(base_url()."superadmin/editstaff/".$id);
			}
		}
		else { //validation fails
			$staff = $this->superadmin_model->getStaffWithId($userId);

			//get other departments
			$id = $staff->deptId;
			$depts = $this->superadmin_model->getOtherDepts($id);
			$depts = $depts['rows'];

			//get other branches
			$branchId = $staff->branchId;
			$branches = $this->superadmin_model->getOtherBranches($branchId);
			$branches = $branches['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('staff/editstaff_view',['branches' => $branches, 'depts' => $depts,
													'staff' => $staff]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------

	public function uploadquestion()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get all departments
			$depts = $this->superadmin_model->getActiveDepartment();
			$depts = $depts['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('question/uploadquestion_view',['depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------

	public function setquestion()
	{
		//validate input
		$this->form_validation->set_rules('dept', 'Department', 'required');

		if ($this->form_validation->run()) {
			$deptId = $this->input->post('dept');
			return redirect(base_url()."superadmin/appendquestion/".$deptId);
		}
		else {
			//get all departments
			$depts = $this->superadmin_model->getActiveDepartment();
			$depts = $depts['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('question/uploadquestion_view',['depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function appendquestion()
	{
		$deptId = $this->uri->segment(3);
		//get department name
		$deptname = $this->superadmin_model->getSingleDeptName($deptId);

		$this->load->view('inc/header_view');
		$this->load->view('inc/nav_view');
		$this->load->view('question/setquestion_view',['dept' => $deptname, 'id' => $deptId]);
		$this->load->view('inc/footer_view');
	}

	//--------------------------------------

	public function addquestion()
	{
		$deptId = $this->input->post('dept');
		
		$this->form_validation->set_rules('question', 'Question', 'required');
		$this->form_validation->set_rules('qtype', 'Question type', 'required');
		/*$this->form_validation->set_rules('option2', 'Option 2', 'required');
		$this->form_validation->set_rules('option3', 'Option 3', 'required');
		$this->form_validation->set_rules('option4', 'Option 4', 'required');*/

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			if ($data['qtype'] == 1) { //multichoice
				if ($data['option1'] == '' || $data['option2'] == '' || $data['option3'] == '' || $data['option4'] == '') {
					$this->session->set_flashdata('msg', 'Kindly ensure all required options are provided');
					return redirect(base_url()."superadmin/appendquestion/".$deptId);
				}
				else {
					//add question
					$questionId = $this->superadmin_model->addQuestion($data);

					if ($questionId > 0) {
						$options = $this->superadmin_model->fetchPendingQuestion($questionId);

						if ($options) {
							$options = $options['rows'];

							$this->load->view('inc/header_view');
							$this->load->view('inc/nav_view');
							$this->load->view('question/setanswer_view',['options' => $options, 'deptId' => $deptId]);
							$this->load->view('inc/footer_view');
						}
						else {
							//redirect to function that fetches pending questions
							$this->session->set_flashdata('msg', 'Complete question set-up');
							return redirect('superadmin/pendingquestion');
						}
					}
					else {
						$this->session->set_flashdata('msg', 'Failed to add question');
						return redirect(base_url()."superadmin/appendquestion/".$deptId);
					}
				}
			}
			else { //true or false
				if ($data['yes'] == '') {
					$this->session->set_flashdata('msg', 'Kindly ensure all required options are provided');
					return redirect(base_url()."superadmin/appendquestion/".$deptId);
				}
				else {
					$addquestion = $this->superadmin_model->addMultipleQuestion($data);

					if ($addquestion) {
						$this->session->set_flashdata('success', 'Question upload successful');
						return redirect(base_url()."superadmin/appendquestion/".$deptId);
					}
					$this->session->set_flashdata('msg', 'Failed to add question');
					return redirect(base_url()."superadmin/appendquestion/".$deptId);
				}
			}
		}
		else {
			//get department name
			$deptname = $this->superadmin_model->getSingleDeptName($deptId);

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('question/setquestion_view',['dept' => $deptname, 'id' => $deptId]);
			$this->load->view('inc/footer_view');
		}
	}

	//----------------------------------------

	public function setanswer()
	{
		$questionId = $this->input->post('id');
		$deptId = $this->input->post('deptId');

		$this->form_validation->set_rules('option', 'Option', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			//set answer
			$answer = $this->superadmin_model->setAnswer($data);

			if ($answer) {
				$this->session->set_flashdata('success', 'Question and answer successfully set');
				return redirect(base_url()."superadmin/appendquestion/".$deptId);
			}
			else {
				//redirect to function that fetches pending questions
				$this->session->set_flashdata('msg', 'Complete question set-up');
				return redirect('superadmin/pendingquestion');
			}
		}
		else {
			$options = $this->superadmin_model->fetchPendingQuestion($questionId);

			if ($options) {
				$options = $options['rows'];

				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('question/setanswer_view',['options' => $options, 'deptId' => $deptId]);
				$this->load->view('inc/footer_view');
			}
			else {
				//redirect to function that fetches pending questions
				$this->session->set_flashdata('msg', 'Complete question set-up');
				return redirect('superadmin/pendingquestion');
			}
		}
	}

	//------------------------------------------

	public function pendingquestion()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//select pending questions
			$questions = $this->superadmin_model->fetchAllPendingQuestion();

			if ($questions) {
				$questions = $questions['rows'];
				//$num = $questions['num'];

				$this->load->view('inc/header_view');
				$this->load->view('inc/nav_view');
				$this->load->view('question/pendingquestion_view',['questions' => $questions]);
				$this->load->view('inc/footer_view');
			}
			else {
				$this->session->set_flashdata('success', 'No pending question');
				return redirect('home/index');
			}
		}
	}

	//------------------------------------------

	public function uploadvideo()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			//get all departments
			$depts = $this->superadmin_model->getActiveDepartment();
			$depts = $depts['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('induction/uploadvideo_view',['depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function addvideo()
	{
		$this->form_validation->set_rules('dept', 'Department', 'required');
		$this->form_validation->set_rules('video', 'Video url', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();

			//check if department has url already
			$chkUrl = $this->superadmin_model->checkUrl($data);

			if ($chkUrl) {
				// update
				$updurl = $this->superadmin_model->updateUrl($data);
			}
			else {
				//insert new record
				$updurl = $this->superadmin_model->insertUrl($data);
			}

			if ($updurl) {
				// success
				$this->session->set_flashdata('success', 'Video url successfully inserted/updated');
				return redirect('superadmin/uploadvideo');
			}
			else {
				//failure, redirect to retry
				$this->session->set_flashdata('msg', 'Unable to update');
				return redirect('superadmin/uploadvideo');
			}
		}
		else {
			//get all departments
			$depts = $this->superadmin_model->getActiveDepartment();
			$depts = $depts['rows'];

			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('induction/uploadvideo_view',['depts' => $depts]);
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

	public function uploadslide()
	{
		if ($this->session->userdata('dept') != 1) {
			$this->session->set_flashdata('msg', 'Access Denied');
			return redirect('home/index');
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('induction/uploadslide_view');
			$this->load->view('inc/footer_view');
		}
	}

	//-------------------------------------------

	public function addslide()
	{
		$data = $this->input->post();
		
		//load file upload library
		$config['upload_path']          = './slides/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 5000000;
        $this->load->library('upload', $config);

		//validation rules
		$this->form_validation->set_rules('title', 'Training title', 'required');
		$this->form_validation->set_rules('intro', 'Introduction', 'required');
		$this->form_validation->set_rules('tutor', 'Instructor', 'required');
		$this->form_validation->set_rules('trainingdate', 'Training date', 'required');

		if ($this->form_validation->run()) {
			$data = $this->input->post();
			$data['filename'] = '';

			if (!$this->upload->do_upload('slide')) {
				//large image or wrong file type
				$this->session->set_flashdata('msg', 'Failed to upload: File too large or wrong filetype');
                return redirect('superadmin/uploadslide');
			}
			else {
				$upload_data = $this->upload->data();
                $data['filename'] = $upload_data['file_name'];
			}
			
			$now = date('Y-m-d');

			if ($data['trainingdate'] > $now) {
				$this->session->set_flashdata('msg', 'Invalid date');
                return redirect('superadmin/uploadslide');
			}
			$addslide = $this->superadmin_model->uploadSlide($data);

			if ($addslide) {
				$this->session->set_flashdata('success', 'Training slide uploaded');
				return redirect('superadmin/uploadslide');
			}
			else {
				$this->session->set_flashdata('msg', 'Unable to upload training slide');
				return redirect('superadmin/uploadslide');
			}
		}
		else {
			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('induction/uploadslide_view');
			$this->load->view('inc/footer_view');
		}
	}

	//--------------------------------------------

	public function pendingstaff()
	{
		//fetch pending staff
		$allpendingstaff = $this->superadmin_model->getPendingStaff();

		if (!$allpendingstaff) {
			$this->session->set_flashdata('success', 'No pending staff');
			return redirect('home/index');
		}
		else {
			$pendingstaffs = $allpendingstaff['rows'];
			$this->load->view('inc/header_view');
			$this->load->view('inc/nav_view');
			$this->load->view('staff/pendingstaff_view', ['staffs' => $pendingstaffs]);
			$this->load->view('inc/footer_view');
		}
	}

	//---------------------------------------------

	public function editstaffstatus()
	{
		if ($this->input->post('userId')) {
			$data = array(); $x = '';
			foreach ($_POST['userId'] as $key => $value) {
				$data['userId'] = $_POST['userId'][$key];
				$data['status'] = $_POST['status'][$key];

				//update staff status
				$updatestaff = $this->superadmin_model->updateStaffStatus($data);
			}
			$this->session->set_flashdata('success', 'Staff status updated');
			return redirect('superadmin/loadstaff');
		}
		else {
			$this->session->set_flashdata('success', 'Nothing selected');
			return redirect('superadmin/loadstaff');
		}
	}

	//-------------------------------------------

	public function editbranchstatus()
	{
		if ($this->input->post('branchId')) {
			$data = array(); $x = '';
			foreach ($_POST['branchId'] as $key => $value) {
				$data['branchId'] = $_POST['branchId'][$key];
				$data['status'] = $_POST['status'][$key];

				//update staff status
				$updatestaff = $this->superadmin_model->updateBranchStatus($data);
			}
			$this->session->set_flashdata('success', 'Staff status updated');
			return redirect('superadmin/loadbranch');
		}
		else {
			$this->session->set_flashdata('success', 'Nothing selected');
			return redirect('superadmin/loadbranch');
		}
	}

	//-----------------------------------------------

	public function editdeptstatus()
	{
		if ($this->input->post('deptId')) {
			$data = array(); $x = '';
			foreach ($_POST['deptId'] as $key => $value) {
				$data['deptId'] = $_POST['deptId'][$key];
				$data['status'] = $_POST['status'][$key];

				//update staff status
				$updatestaff = $this->superadmin_model->updateDeptStatus($data);
			}
			$this->session->set_flashdata('success', 'Staff status updated');
			return redirect('superadmin/loaddept');
		}
		else {
			$this->session->set_flashdata('success', 'Nothing selected');
			return redirect('superadmin/loaddept');
		}
	}

	//-----------------------------------------------

	public function editpoststatus()
	{
		if ($this->input->post('postId')) {
			$data = array(); $x = '';
			foreach ($_POST['postId'] as $key => $value) {
				$data['postId'] = $_POST['postId'][$key];
				$data['status'] = $_POST['status'][$key];

				//update staff status
				$updatestaff = $this->superadmin_model->updatePostStatus($data);
			}
			$this->session->set_flashdata('success', 'Staff status updated');
			return redirect('superadmin/loadpost');
		}
		else {
			$this->session->set_flashdata('success', 'Nothing selected');
			return redirect('superadmin/loadpost');
		}
	}

	//-----------------------------------------------

}
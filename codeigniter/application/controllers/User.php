<?php

class User extends CI_controller{

	public  function __construct(){
		parent:: __construct();
		if (!isset($_SESSION['user_logged'])) {
			$this->session->set_flashdata("error","Please login first to view this page.");
			redirect("auth/login");
		}
	}

	public function profile(){
		$this->load->model('Auth_model');
		$records= $this->Auth_model->getRecords();
		$this->load->view('profile',['records'=>$records]);
	}

	// public function create(){
	// 	$this->load->view('create');
	// }	

	public function create(){

			if(isset($_POST['create'])){
 	
			$this->form_validation->set_rules('username','Username','required');
			$this->form_validation->set_rules('fname','First name','required');
			$this->form_validation->set_rules('lname','Last name','required');
			$this->form_validation->set_rules('dob','Date of birth','required');
			$this->form_validation->set_rules('email','Email','required');
			$this->form_validation->set_rules('password','Password','required|min_length[5]');
			$this->form_validation->set_rules('phone','Phone','required');
			//if form validation true
			if($this->form_validation->run()==TRUE) {
				$data = array(
					'username'=>$_POST['username'],
					'fname'=>$_POST['fname'],
					'lname'=>$_POST['lname'],
					'email'=>$_POST['email'],
					'password'=>md5($_POST['password']),
					'dob'=>$_POST['dob'],
					'phone'=>$_POST['phone']
				);
				$this->db->insert('users',$data);
				redirect("user/profile","refresh");
				// $this->session->set_flashdata("success", "Your account has been registered.");
			}
		}
		$this->load->view('create');
	}
	
		public function edit($id){

			$this->load->model('Auth_model');

			if(isset($_POST['update'])){
				if($this->Auth_model->updateUser($id)){
					$this->session->set_flashdata("success", "User updated successfully.");
					redirect('user/profile','refresh');
				}else{
					$this->session->set_flashdata("error", "Failed to update... error!!.");
					redirect('user/edit'.$id,'refresh');
				}
			}
			$data['user'] = $this->Auth_model->getRecord($id);
			$this->load->view('update', $data);

		}

		public function delete( $id ){
			$this->load->model("Auth_model");
			$this->Auth_model->deleteUser($id);
			$this->session->set_flashdata("success", "Deleted successfully.");
			redirect('user/profile','refresh');

		}
}

?>
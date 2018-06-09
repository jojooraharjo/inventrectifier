<?php
class login extends CI_Controller {
	public function index()
	{
		$this->load->view('login');
	}
}
public function check_login()
{
    $username = $_POST['username'];
    $pass = $_POST['password'];

    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[15]');

    if($this->form_validation->run() == FALSE)
    {
        $this->load->view('login');
    }
    else
    {
        $res = $this->user_model->check_user($username , $pass);
        if(!empty($res))
        {
            if($res[0]['status'] == '1')
            {
                $data['user'] = $res[0]['fname'];
                $this->setSession($res[0]['id'],$res[0]['fname']);
                $this->load->view('profile', $data);
            }
            else
            {
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Verify your email address first to login...</div>');
        		redirect(base_url().'login');
            }
        }
        else
        {
            $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">email/password not found</div>');
        	redirect(base_url().'login');
        }
    }
}

function setSession($userId,$userName) {
    
    $userSession = array('userId'=>$userId,
                         'userName'=>$userName,
                         'loggedIn'=>TRUE );
    $this->session->set_userdata($userSession);
}

function logout(){
    $this->session->sess_destroy();
    redirect(base_url().'user/login', 'refresh');
}
?>
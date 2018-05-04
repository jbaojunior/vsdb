<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->helper('url');
   $this->load->helper('url_helper');
   $this->load->helper('form');
   $this->load->helper('html');
   $this->load->library('form_validation');
   $this->form_validation->set_error_delimiters('<div class="error_verde">', '</div><br>');
 }
 
 function index()
 {
   $this->form_validation->set_rules('username', 'Username', 'trim|required');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
 
   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('login_view');
   }
   else
   {
     //Go to private area
     $data = $this->logged->login();  # Login

     if ( $data['team_user'] == 'A' )
     {
        redirect('analysts/view/'.$data['id_user'],'refresh');
     }
     else
     {
        redirect('evolutions', 'refresh');
     };
   }
 
 }
 
 function check_database($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
 
   //query the database
   $result = $this->analysts_model->login($username, $password);

   if($result)
   {
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'id'              => $row->id,
         'username'        => $row->login,
         'team'            => $row->team,
         'nrows'           => $row->number_rows,
         'last_evolutions' => $row->last_evolutions,
         'get_search'      => null
       );
       $this->session->set_userdata('logged_in',$sess_array);
     }
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalido login ou password');
     return false;
   }
 }
}
?>

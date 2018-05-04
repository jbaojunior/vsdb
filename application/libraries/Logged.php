<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logged {

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->database();
    }

	function login()
	{

        if($this->CI->session->userdata('logged_in'))
		{
/*
            echo br(5);
            echo 'GET: '; 
            print_r($_GET);
            echo br(1);
*/
			$session_data            = $this->CI->session->userdata('logged_in');
/*
            echo br(1) . 'Session_data: ';
            print_r($session_data);
            echo br(1);
*/

			$data['login']           = $session_data['username'];
			$data['id_user']         = $session_data['id'];
			$data['team_user']       = $session_data['team'];
			$data['nrows']           = $session_data['nrows'];
			$data['last_evolutions'] = $session_data['last_evolutions'];

            if ( isset($session_data['get_search']) )
            {
    			$data['get_search']  = $session_data['get_search']; 
            };

            if ( isset($session_data['sortby']) )
            {
                $data['sortby'] = $session_data['sortby'];
            };

            if ( isset($_GET['sortby']) )
            {
                $data['sortby'] = $_GET['sortby'];
                $_SESSION['logged_in']['sortby'] = $_GET['sortby'];
            };

            if ( isset($session_data['sorder']) )
            {
                $data['sorder'] = $session_data['sorder'];
            };

            if ( isset($_GET['sorder']) )
            {
                $data['sorder'] = $_GET['sorder'];
                $_SESSION['logged_in']['sorder'] = $_GET['sorder'];
            };

            
            if ( isset($data['sortby']) and isset($data['sorder']) )
            {
                $_GET['sortby'] = $data['sortby'];
                $_GET['sorder'] = $data['sorder'];
            };
/*
            echo br(1) . 'Data: ';
            print_r($data);
*/ 
            return $data;
		}
		else
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}
	}

	function logout()
	{
		$this->CI->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}

    function permission($team)
    {
       # $analyst = $this->CI->analysts_model->get_analysts_type($id);

       #if ($analyst['Team'] === 'A')
        if ($team === 'A')
        {
            echo '<h1 class="error"><p>Sem permissão de acesso"</p></h1>';
            exit(789);
        };
    }
}

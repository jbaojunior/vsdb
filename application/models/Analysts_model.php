<?php
class Analysts_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

		public function get_analysts($id = FALSE,  $per_page = FALSE, $page = 0 )
		{
            $this->db->select('an.id "ID" ,an.name "Analyst", an.login "Login", an.team "Team", an.last_evolutions "Last_Evolutions"');
            $this->db->from('analysts an');
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);
            $this->db->limit($per_page,$page);
            $this->db->order_by('an.name');

            if ($id == FALSE)
        	{
            	$query = $this->db->get();
                return $query->result_array();
        	};	

        	$this->db->where('an.id',$id);
        	$query = $this->db->get();
	        return $query->row_array();
		}

        public function count_analysts()
        {
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);

            return $this->db->count_all_results('analysts');
        }


        public function search_analysts($search_term, $per_page = FALSE)
        {
            if (!isset($search_term['per_page']))
            {
                $page = 0;
            }
            else
            {
                $page = $search_term['per_page'];
            };

            $this->db->select('an.id "ID" ,an.name "Analyst", an.login "Login", an.team "Team"');
            $this->db->from('analysts an');
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);

            $this->db->limit($per_page,$page);
            $this->db->order_by('an.name');

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            foreach ($search_term as $var => $result)
            {
                if ( substr($var,0,5) === 'field' )
                {
                   $result = '\'' . $result . '\'';
                };

                $search_where = $search_where . ' ' . $result;
            };

            $this->db->where($search_where);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function count_analysts_search($search_term, $per_page = FALSE)
        {
            $this->db->select('an.id "ID" ,an.name "Analyst", an.login "Login", an.team "Team"');
            $this->db->from('analysts an');
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            foreach ($search_term as $var => $result)
            {
                 if ( substr($var,0,5) === 'field' )
                 {
                    $result = '\'' . $result . '\'';
                 };

                 $search_where = $search_where . ' ' . $result;
            };

            $this->db->where($search_where);

            $query = $this->db->get();
            return $this->db->count_all_results();
        }

		public function get_evolutions($id = FALSE, $team = FALSE, $per_page = FALSE, $page = 0)
        {
            # Call the library with select statement
            $this->evoltable->select(1);

            if ( $team === 'D' )
            {
                $this->db->where('anD.id',$id);
            } else
            {
                $this->db->where('an.id',$id);
            }; 

            $this->db->limit($per_page,$page);
            $this->db->order_by('ev.dt_create', 'DESC');

			#$sql = $this->db->get_compiled_select('evolutions');
			#print_r($sql);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function search_evolutions($id = FALSE, $team = FALSE, $search_term, $per_page = FALSE)
        {
            if (!isset($search_term['per_page']))
            {
                $page = 0;
            }
            else
            {
                $page = $search_term['per_page'];
            };

            # Call the library with select statement
            $this->evoltable->select(1);

            if ( $team === 'D' )
            {
                $this->db->where('anD.id',$id);
            } else
            {
                $this->db->where('an.id',$id);
            }; 

            $this->db->limit($per_page,$page);
            $this->db->order_by('ev.dt_create', 'DESC');

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            foreach ($search_term as $var => $result)
            {   
                if ( $result == 'ev.dt_create' or $result == 'ev.dt_evolution' )
                {
                    $result = 'date_format('. $result  .',\'%d/%m/%y\')';
                };

                if ( substr($var,0,5) === 'field' )
                {
                   $result = '\'' . $result . '\'';
                };

                $search_where = $search_where . ' ' . $result;
            };

            $this->db->where($search_where);

            $query = $this->db->get();
            return $query->result_array();
        }

		public function count_evolutions($id = FALSE, $team = FALSE)
        {
            # Call the library with select statement
            $this->evoltable->select(1);

            if ( $team === 'D' )
            {
                $this->db->where('anD.id',$id);
            } else
            {
                $this->db->where('an.id',$id);
            }; 

			$query = $this->db->count_all_results();
	
            return $query;
        }

        public function count_evolutions_search($id = FALSE, $team = FALSE, $search_term)
        {
            # Call the library with select statement
            $this->evoltable->select(1);

            if ( $team === 'D' )
            {
                $this->db->where('anD.id',$id);
            } else
            {
                $this->db->where('an.id',$id);
            }; 

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            unset($search_term['per_page']);
            foreach ($search_term as $var => $result)
            {
                 if ( substr($var,0,5) === 'field' )
                 {
                    $result = '\'' . $result . '\'';
                 };

                 $search_where = $search_where . ' ' . $result;
            };

            $this->db->where($search_where);
            return $this->db->count_all_results();
        }

        public function add_analysts()
        {
            $this->load->helper('url');

            if ( $this->input->post('number_rows') == '' )
            {
                $nrows = 20;
            }
            else
            {
                $nrows = $this->input->post('number_rows');
            };

            $existLogin = $this->analysts_model->get_analysts_login_all($this->input->post('login'));
            if ( isset($existLogin) )
            {
                $this->load->helper('url');

                $analyst_update = array(
                    'is_excluded' => 0,
                    'dt_excluded' => null,
                    'team' => $this->input->post('team'),
                    'password' => md5($this->input->post('password')),
                    'number_rows' => $this->input->post('number_rows'),
                    'dt_create' => date('Y-m-d H:i:s')
                );
                $this->db->where('id',$existLogin['ID']);

#                print_r($existLogin);
#                $query = $this->db->set($analyst_update)->get_compiled_update('analysts');
#                print_r($query);
#                exit();
 
                return $this->db->update('analysts', $analyst_update);
       
            } else {
                $analyst_insert = array(
                    'name' => $this->input->post('analyst'),
                    'login' => $this->input->post('login'),
                    'team' => $this->input->post('team'),
                    'password' => md5($this->input->post('password')),
                    'number_rows' => $this->input->post('number_rows'),
                    'dt_create' => date('Y-m-d H:i:s')
                );
			    return $this->db->insert('analysts', $analyst_insert);
            };

			#$sql = $this->db->set($analyst_insert)->get_compiled_insert('analysts');
			#echo $sql;
			#exit();

        }

		public function get_analysts_name($name = FALSE)
		{
            $this->db->select('id "ID" ,name "Analyst", team "Team"');
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);
       	
            if ($name == FALSE)
        	{
            	$query = $this->db->get('analysts');
                return $query->result_array();
        	}	
	     
            $this -> db -> where('name', $name);
        	$query = $this->db->get('analysts');
	        return $query->row_array();
		}

		public function get_analysts_login($login = FALSE)
		{
            $this->db->select('id "ID" ,name "Analyst", login "Login", team "Team"');
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);
       	
            if ($login == FALSE)
        	{
            	$query = $this->db->get('analysts');
                return $query->result_array();
        	}	
	     
            $this -> db -> where('login', $login);
        	$query = $this->db->get('analysts');
	        return $query->row_array();
		}

		public function get_analysts_login_all($login = FALSE)
		{
            $this->db->select('id "ID" ,name "Analyst", login "Login", team "Team"');
       	
            if ($login == FALSE)
        	{
            	$query = $this->db->get('analysts');
                return $query->result_array();
        	}	
	     
            $this -> db -> where('login', $login);
        	$query = $this->db->get('analysts');
	        return $query->row_array();
		}

		public function get_analysts_type($id = FALSE)
		{
            $this->db->select('id "ID" ,name "Analyst",login "Login", team "Team"');
        	
        	$query = $this->db->get_where('analysts', array('id' => $id));
	        return $query->row_array();
		}

        public function delete( $id )
        {
            $this->load->helper('url');

            # Apagando o schema
            $analyst_update = array(
                'is_excluded' => 1,
                'dt_excluded' => date('Y-m-d H:i:s')
            );
            $this->db->where('id',$id);

            #$query = $this->db->set($account_update)->get_compiled_update('accounts');
            #print_r($query);

            return $this->db->update('analysts', $analyst_update);
       }

	   public function login($username, $password)
	   {
	     $this -> db -> select('id, name, login, password, team, number_rows, last_evolutions');
	     $this -> db -> from('analysts');
	     $this -> db -> where('login', $username);
	     $this -> db -> where('password', MD5($password));
	     $this -> db -> limit(1);
		 
	     $query = $this -> db -> get();
		 
	     if($query -> num_rows() == 1)
	     {
	       return $query->result();
	     }
	     else
	     {
	       return false;
	     }
	  }

      public function change_password( $id, $password )
      {
        $this->load->helper('url');

        $analyst_update = array(
            'password' => $password,
        );
        
        $this->db->where('id',$id);
        return $this->db->update('analysts', $analyst_update);
      }

      public function configuration( $id,$change_name=NULL,$change_login=NULL )
      {
        $this->load->helper('url');
        
        if ( $this->input->post('last_evolutions') == 1 )
        {
            $levolutions = 1;
        }
        else
        {
            $levolutions = 0;
        };


        $analyst_update = array(
            'number_rows'     => $this->input->post('number_rows'),
            'team'            => $this->input->post('team'),
            'last_evolutions' => $levolutions
        );

        if ( $change_name = 1 )
        {
            $analyst_update['name'] = $this->input->post('analyst');
        };
 
        if ( $change_login = 1 )
        {
            $analyst_update['login'] = $this->input->post('login');
        };

        $this->db->where('id',$id);

#        $query = $this->db->set($analyst_update)->get_compiled_update('analysts');
#        print_r($query);
        return $this->db->update('analysts', $analyst_update);
      }

      public function configuration_account( $id,$change_name=NULL,$change_login=NULL )
      {
        $this->load->helper('url');
        
        if ( $this->input->post('last_evolutions') == 1 )
        {
            $levolutions = 1;
        }
        else
        {
            $levolutions = 0;
        };


        $analyst_update = array(
            'number_rows'     => $this->input->post('number_rows'),
            'last_evolutions' => $levolutions
        );

        if ( $change_name = 1 )
        {
            $analyst_update['name'] = $this->input->post('analyst');
        };
 
        if ( $change_login = 1 )
        {
            $analyst_update['login'] = $this->input->post('login');
        };

        $this->db->where('id',$id);

		# Update the configuration	
		$newconf = $this->session->userdata('logged_in');
		$newconf['nrows'] = $this->input->post('number_rows');
		$newconf['login'] = $this->input->post('login');
		$newconf['last_evolutions'] = $levolutions;
		
		$this->session->set_userdata('logged_in',$newconf);

#        $query = $this->db->set($analyst_update)->get_compiled_update('analysts');
#        print_r($query);
        return $this->db->update('analysts', $analyst_update);
      }

}

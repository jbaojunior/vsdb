<?php
class Environments_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

		public function get_environments($id = FALSE, $per_page = FALSE, $page = 0 )
		{
            $this->db->select('en.id "ID" ,en.name "Environment"');
			$this->db->from('environments en');
            $where = "(en.is_excluded is null or en.is_excluded = 0)";
            $this->db->where($where);
        	$this->db->limit($per_page,$page);

            if ($id == FALSE)
        	{
            	$query = $this->db->get();
				
				#$sql = $this->db->get_compiled_select();
				#print_r($sql);			
	
                return $query->result_array();
        	}	

        	$this->db->where('en.id' , $id);
            $query = $this->db->get();
	        return $query->row_array();
		}

        public function count_environments()
        {
            $where = "(is_excluded is null or is_excluded = 0)";
            $this->db->where($where);

            return $this->db->count_all_results('environments');
        }

		public function search_environments($search_term, $per_page = FALSE)
		{
            if (!isset($search_term['per_page']))
            {
                $page = 0;
            }
            else
            {
                $page = $search_term['per_page'];
            };
        
            $this->db->select('en.id "ID" ,en.name "Environment"');
			$this->db->from('environments en');
            $where = "(en.is_excluded is null or en.is_excluded = 0)";
            $this->db->where($where);
        	
            $this->db->limit($per_page,$page);

            $this->db->order_by('ev.dt_create','desc');

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);
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

		public function count_environments_search($search_term, $per_page = FALSE)
		{
            $this->db->select('en.id "ID" ,en.name "Environment"');
			$this->db->from('environments en');
            $where = "(en.is_excluded is null or en.is_excluded = 0)";
            $this->db->where($where);
        	
            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            echo br(5);
            echo 'estou aqui';

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);

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

		public function get_evolutions($id = FALSE,$per_page = FALSE, $page = 0)
        {
            # Call the library with select statement
            $this->evoltable->select(1);
	
            if ($id != FALSE )
            {
                $this->db->where('en.id',$id);
            };

            $this->db->limit($per_page,$page);
            $this->db->order_by('ev.dt_create', 'DESC');

            $query = $this->db->get();
            return $query->result_array();
        }

        public function search_evolutions($id = FALSE, $search_term, $per_page = FALSE)
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

			if ($id != FALSE )
			{
				$this->db->where('en.id',$id);
			};

            $this->db->limit($per_page,$page);
            
            $sortby = '';
            $sorder = '';
            if ( isset($_GET['sortby']) and isset($_GET['sorder']) )
            {
                $sortby = $this->input->get('sortby');
                $sorder = $this->input->get('sorder');

                $this->db->order_by($sortby, $sorder);
            };

            $this->db->order_by('ev.dt_create','desc');

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);
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

		public function count_evolutions($id = FALSE)
        {
            # Call the library with select statement
            $this->evoltable->select(1);

            if ($id != FALSE )
            {
                $this->db->where('en.id',$id);
            };

			$query = $this->db->count_all_results();
	
            return $query;
        }

        public function count_evolutions_search($id = FALSE, $search_term)
        {
            # Call the library with select statement
            $this->evoltable->select(1);
            
            if ($id != FALSE )
            {
                $this->db->where('en.id',$id);
            };

            $search_where='';
            if ( $search_term == null )
            {
                $search_where='1=1';
            };


            # Retirando o per_page da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);

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


        public function add_environments()
        {
            $this->load->helper('url');

            $environment_insert = array(
                'name' => $this->input->post('environment'),
                'dt_create' => date('Y-m-d H:i:s')
            );

#			$sql = $this->db->set($account_insert)->get_compiled_insert('accounts');
#			echo $sql;
#			exit();

			return $this->db->insert('environments', $environment_insert);
        }

		public function get_environments_name($name = FALSE)
		{
            $this->db->select('id "ID" ,name "Environment"');
        	
            if ($name == FALSE)
        	{
            	$query = $this->db->get('environments');
                return $query->result_array();
        	}	

        	$query = $this->db->get_where('environments', array('name' => $name));
	        return $query->row_array();
		}

        public function delete( $id )
        {
            $this->load->helper('url');

            # Selecinando ID's que serão apagados na tabela evolution
            $this->db->select('ev.id "ID"');
            $this->db->from('evolutions ev');
            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->db->where($where);
            $this->db->where('ev.environment_id',$id);
            $evol = $this->db->get();

            foreach ($evol->result_array() as $var => $result)
            {
                $evolution_update = array(
                    'is_excluded' => 1,
                    'dt_excluded' => date('Y-m-d H:i:s')
                );
                $this->db->where('id',$result['ID']);

                #$query = $this->db->set($evolution_update)->get_compiled_update('evolutions');
                #print_r($query);
                $this->db->update('evolutions', $evolution_update);

            };       

            # Apagando o schema
            $environment_update = array(
                'is_excluded' => 1,
                'dt_excluded' => date('Y-m-d H:i:s')
            );
            $this->db->where('id',$id);

            #$query = $this->db->set($account_update)->get_compiled_update('accounts');
            #print_r($query);

            return $this->db->update('environments', $environment_update);
       }

}

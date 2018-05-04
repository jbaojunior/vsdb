<?php
date_default_timezone_set('America/Sao_Paulo');

class Evolutions_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        public function count_evolutions()
        {
            $this->evoltable->select(1);
            return $this->db->count_all_results();
        }

        public function count_evolutions_search($search_term)
        {

            # Call the library with select statement
            $this->evoltable->select(1);

            # Retirando o per_page e o sortby da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);

            $search_where='';
            if ( count($search_term) == 0 )
            {
                $search_where='1=1';
            };

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

        public function get_evolutions($id = FALSE, $per_page = FALSE, $search_term = FALSE )
        {
            # Call the library with select statement
            $this->evoltable->select(1);

            if (!isset($search_term['per_page']))
            {
                $page = 0;
            } 
            else
            {
                $page = $search_term['per_page'];
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
            
            if ($id == FALSE)
            {/* 
               $query = $this->db->get_compiled_select();
               echo br(5);
               echo 'query: ' . br(1);
               print_r($query);
               exit(); */
               $query = $this->db->get();
               return $query->result_array();
            }

            $this->db->where('ev.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        }

        public function search_evolutions($search_term, $per_page = FALSE)
        {
            if (!isset($search_term['per_page']))
            {
                $page = 0;
            } 
            else
            {
                $page = $search_term['per_page'];
            }

            # Call the library with select statement
            $this->evoltable->select(1);

            $this->db->limit($per_page,$page);
#           $this->db->order_by('ev.dt_create', 'DESC');

            $sortby = '';
            $sorder = '';
            if ( isset($_GET['sortby']) and isset($_GET['sorder']) )
            {
                $sortby = $this->input->get('sortby');
                $sorder = $this->input->get('sorder');
            
                $this->db->order_by($sortby, $sorder);
            };
                
            $this->db->order_by('ev.dt_create','desc');

            # Retirando o per_page da busca
            unset($search_term['per_page']);
            unset($search_term['sortby']);
            unset($search_term['sorder']);
            
            $search_where='';
            if ( count($search_term) == 0 )
            {
                $search_where='1=1';
            };

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
                   
                $before_result = $result;

                $search_where = $search_where . ' ' . $result;
            };
         
            $this->db->where($search_where);
/*
          $query = $this->db->get_compiled_select();
            echo br(5);
            echo 'query: ' . br(1);
            print_r($query);
//          return $query->result();
*/
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_environments()
        {
            $this->db->select('en.id "Environment_ID", en.name "Environment"');
            $this->db->from('environments en');
            $this->db->order_by('en.name');
            $query = $this->db->get();
           return $query->result_array();
        }

        public function get_environments_name($name)
        {
            $this->db->select('en.id "Environment_ID", en.name "Environment"');
            $this->db->from('environments en');
            $this->db->where_in('en.name',$name);
            $this->db->order_by('en.name');
            $query = $this->db->get();
           return $query->result_array();
        }

        public function get_schemas($schema_id = FALSE)
        {
            $this->db->select('ac.id "Schema_ID", ac.name "Schema"');
            $this->db->from('accounts ac');

			if ( $schema_id != FALSE )
			{
				$this->db->where('ac.id', $schema_id);
			};
	
            $this->db->order_by('ac.name');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_analysts($id = FALSE)
        {
            $this->db->select('an.ID "Analyst_ID", an.name "Analyst"');
            $this->db->from('analysts an');
            $this->db->where('team','A');
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

        public function get_analysts_DBA()
        {
            $this->db->select('an.ID "Analyst_DBA_ID", an.name "Analyst"');
            $this->db->from('analysts an');
            $this->db->where('team','D');
            $this->db->order_by('an.name');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_scripts($script_id = FALSE)
        {
            $this->db->select('sc.id "Script_ID",sc.name "Script_Name",sc.script "Script",sc.observation "Observation"');
            $this->db->from('scripts sc');
        
			if ( $script_id != FALSE )
			{
				$this->db->where('sc.id', $script_id);
			};

            $query = $this->db->get();
            return $query->result_array();
		}

        public function add_evolution()
		{
        	$this->load->helper('url');

			$max_sequence = $this->db->query('SELECT MAX(sequence_id) AS `sequence_id` FROM `evolutions`')->row_array();
            $sequence = $max_sequence['sequence_id']+1;

            $script_insert = array (
				'version' => 1,
				'glpi' => $this->input->post('glpi'),
				'name' => $this->input->post('glpi') . '_' . $this->input->post('schema') . '_' . $sequence . '_1',
				'script' => $this->input->post('script')
			);

			$this->db->insert('scripts',$script_insert);
			$last_script = $this->db->insert_id();
		
	    	$evolution_insert = array(
        		'glpi' => $this->input->post('glpi'),
        		'environment_id' => $this->input->post('environment'),
        		'account_id' => $this->input->post('schema'),
				'script_id' => $last_script,
				'sequence_id' => $sequence,
        		'analyst_id' => $this->input->post('analyst'),
				'analyst_dba_id' => $this->input->post('analyst_DBA'),
				'description' => $this->input->post('description')
    		);

    		return $this->db->insert('evolutions', $evolution_insert);
		}

        public function evolve( $evolution_id,$account_id,$script_id,$sequence_id )
		{
        	$this->load->helper('url');

	    	$evolution_insert = array(
        		'glpi' => $this->input->post('glpi'),
        		'description' => $this->input->post('description'),
        		'environment_id' => $this->input->post('environment'),
        		'account_id' => $account_id,
				'script_id' => $script_id,
				'sequence_id' => $sequence_id,
        		'analyst_id' => $this->input->post('analyst'),
				'analyst_dba_id' => $this->input->post('analyst_DBA'),
                'event' => 'N',
    		);

            $this->db->insert('evolutions', $evolution_insert);

            $script_insert = '';
			$script_insert = array (
				'observation' => $this->input->post('observation')
			);

            #$query = $this->db->set($script_insert)->get_compiled_insert('scripts');
            #print_r($query);
            #echo br(2);
            #exit();
            $this->db->where('id',$script_id);
            $this->db->update('scripts',$script_insert);
 
            $evolution_update = array(
                'evolution_glpi' => $this->input->post('glpi'),
                'event' => 'E',
                'evolution_environment_id' => $this->input->post('environment'),
                'dt_evolution' => date('Y-m-d H:i:s')
            );
            $this->db->where('id',$evolution_id);
    		return $this->db->update('evolutions', $evolution_update);
		}

        public function edit( $environment_id,$account_id,$script_id,$script_version,$sequence_id,$environment )
		{
        	$this->load->helper('url');

            if ($environment != 'Desenvolvimento')
            {
                echo 'Ação não permitida';
                exit();
            };

            $script_insert = '';
            $version = $script_version + 1;
			$script_insert = array (
				'version' => $version,
				'glpi' => $this->input->post('glpi'),
				'name' => $this->input->post('glpi') . '_' . $account_id . '_' . $sequence_id . '_' . $version,
				'script' => $this->input->post('script'),
				'observation' => $this->input->post('observation')
			);

            #$query = $this->db->set($script_insert)->get_compiled_insert('scripts');
            #print_r($query);
            #echo br(2);
            #exit();

            $this->db->insert('scripts',$script_insert);
			$last_script = $this->db->insert_id();
		
            $evolution_insert = '';
	    	$evolution_insert = array(
        		'glpi' => $this->input->post('glpi'),
        		'environment_id' => $environment_id,
        		'account_id' => $account_id,
				'script_id' => $last_script,
				'sequence_id' => $sequence_id,
        		'analyst_id' => $this->input->post('analyst'),
				'analyst_dba_id' => $this->input->post('analyst_DBA'),
				'description' => $this->input->post('description')
    		);

    		return $this->db->insert('evolutions', $evolution_insert);
		}

        public function delete( $evolution_id, $evolution_parent_id, $evolution_environment_id)
		{
        	$this->load->helper('url');

            $evolution_update = array(
                'is_excluded' => 1,
                'dt_excluded' => date('Y-m-d H:i:s')
            );
            $this->db->where('id',$evolution_id);
            
            $query = $this->db->update('evolutions', $evolution_update);

            #echo br(5);
            #$query = $this->db->set($evolution_update)->get_compiled_update('evolutions');
            #print_r($query);
            #exit();

            if ($query == 1)
            {
                $evolution_parent = array (
                'evolution_glpi' => NULL,
                'dt_evolution'   => NULL,
                'evolution_environment_id' => NULL,
                'event' => 'N'
                );               
                $this->db->where('id',$evolution_parent_id);

                return $this->db->update('evolutions', $evolution_parent);
            }
            else
            {
                return 0; 
            };
        }

        public function get_sequences($sequence_id)
        {
            # Call the library with select statement
            $this->evoltable->select();

            $this->db->where('ev.sequence_id',$sequence_id);
            $this->db->order_by('sc.version','desc');
            #$this->db->order_by('ev.environment_id','desc');
            $this->db->order_by('en.name','desc');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_last_version($sequence_id)
        {
            $this->db->select('ev.script_id "Script_ID", max(sc.version) "Version"',false);
			$this->db->from('scripts sc');
			$this->db->join('evolutions ev','sc.id = ev.script_id');
            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->db->where($where);
            $this->db->where('ev.sequence_id',$sequence_id);

            $query = $this->db->get();
            #$query = $this->db->get_compiled_select();

            return $query->row_array();
        }

        public function last_version($sequence_id,$last)
        {
            $this->db->select('distinct ev.id "Evolution", sc.id "Script_ID", ev.sequence_id "Sequence", sc.version "Version", sc.script "Script"',false);
			$this->db->from('evolutions ev');
			$this->db->join('scripts sc','sc.id = ev.script_id');
            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->db->where($where);
            $this->db->where('ev.sequence_id',$sequence_id);
            $this->db->where('sc.version',$last);
            $this->db->order_by('sc.version','desc');

            $query = $this->db->get();
            #$query = $this->db->get_compiled_select();

            return $query->result_array();
        }
        public function get_evolutions_glpi($glpi,$environment)
        {
            $this->db->select('ev.id "Evolution", ev.sequence_id "Sequence", ev.environment_id "Environment_ID"');
			$this->db->from('evolutions ev');
            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->db->where($where);
            $this->db->where('ev.evolution_glpi',$glpi);
            $this->db->where('ev.evolution_environment_id',$environment);

            $query = $this->db->get();

            #$query = $this->db->get_compiled_select();
            #echo br(5);
            #echo 'query: ' . br(1);
            #print_r($query);
            #exit();

            return $query->row_array();
        }

        public function scripts_desenvolvimento()
        {
            # Call the library with select statement
            $this->evoltable->make_scripts('desenvolvimento');
            
            $query = $this->db->get();
            return $query->result_array();
        }

        public function scripts_homologacao()
        {
            # Call the library with select statement
            $this->evoltable->make_scripts('homologacao');

#            $query = $this->db->get_compiled_select();
#            echo br(5);
#            echo 'query: ' . br(1);
#            print_r($query);
#            exit();
           
            $query = $this->db->get();
            return $query->result_array();
        }

        public function scripts_qualidade()
        {
            # Call the library with select statement
            $this->evoltable->make_scripts('qualidade');
            
            $query = $this->db->get();
            return $query->result_array();
        }

}

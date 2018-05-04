<?php
class Scripts_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        public function get_scripts($id = FALSE)
        {
            $this->db->select('sc.name "Name", sc.version "Version", sc.script "Script", sc.observation "Observation"');
            $this->db->from('scripts sc');

            if ($id == FALSE)
            { 
                $this->db->select('sc.name "Name", sc.version "Version", sc.script "Script", sc.observation "Observation"');
                $this->db->from('scripts sc');
#               $query = $this->db->get('evolutions');
                $query = $this->db->get();
                return $query->result_array();
            }

            $this->db->where('id',$id);
            $query = $this->db->get();
            return $query->row_array();
        }

        public function search_scripts($search_term)
        {
            $this->db->select('ev.id "ID" ,ev.dt_create "Date",ev.glpi "GLPI",en.name "Environment" ,ac.name "Schema" ,sc.name "Script",ev.sequence_id "Sequence",sc.version "Version", an.name "Analyst",  
                               anD.name "Analyst_DBA"');
            $this->db->from('scripts sc');
            $this->db->join('evolutions ev','ev.script_id = sc.id');
            $this->db->join('accounts ac','ac.id = ev.account_id');
            $this->db->join('environments en','en.id = ev.environment_id');
            $this->db->join('analysts an','an.id = ev.analyst_id');
            $this->db->join('analysts anD','anD.id = ev.analyst_dba_id');
           
            $search_where='';
            if ( $search_term == null )
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

            $query = $this->db->get();
//          return $query->result();
            return $query->result_array();
        }
/*
        public function get_evolutions()
        {
            $this->db->select('ev.name "Evolution"');
            $this->db->from('evolutions ev');
            $query = $this->db->get();
#           return $query->result();
            return $query->result_array();
        }
*/
        public function get_environments()
        {
            $this->db->select('en.name "Environment"');
            $this->db->from('environments en');
            $query = $this->db->get();
#           return $query->result();
            return $query->result_array();
        }

        public function get_schemas()
        {
            $this->db->select('ac.name "Schema"');
            $this->db->from('accounts ac');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_analysts()
        {
            $this->db->select('an.name "Analyst"');
            $this->db->from('analysts an');
            $query = $this->db->get();
            return $query->result_array();
        }

 		public function get_evolutions($script_id)
        {
            # Call the library with select statement
            $this->evoltable->select();

            $this->db->where('ev.script_id',$script_id);
            $this->db->order_by('sc.version','desc');
            #$this->db->order_by('ev.environment_id','desc');
            $this->db->order_by('en.name','desc');

            $query = $this->db->get();

            return $query->result_array();
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

}

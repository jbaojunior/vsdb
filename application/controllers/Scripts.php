<?php
class Scripts extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('html');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        }

        public function index()
        {
            $data = $this->logged->login(); # Login

            $data['scripts'] = $this->scripts_model->get_scripts();
		    $data['title'] = 'Scripts';
		    $data['active_search'] = 0;
            $data['search_term'] = array();

#            print_r(array_keys($_POST));
#            var_dump($_POST);
#            exit;
        
            $field_fill = $this->input->post('field0');

            if ( $this->input->post('reset_search') != null )
            {
                $field_fill = null ;
            };

            if ( $field_fill != null )
            {
                $data['active_search'] = 1;

                foreach ($_POST as $var => $post)
                {
                    if ( $var !== 'submit_search' )
                    {
                        $data['search_term'][$var] = $post;
                    };
                };

                $query = $this->scripts_model->search_scripts($data['search_term']) ;
 			
			} else {
                $query = $data['scripts'];
            };

            $fields = $this->db->query($this->db->last_query())->list_fields();
            $this->table->set_heading($fields);

            $template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="sortable">');
            $this->table->set_template($template);
    
            $data['script_table'] = $this->table->generate( $query );

		    $this->load->view('templates/header', $data);
        	$this->load->view('scripts/index', $data);
		    $this->load->view('templates/footer');
        }

        public function view( $id )
        {
            $data = $this->logged->login(); # Login

            $database_query = $this->scripts_model->get_evolutions($id);

            unset($version);
            foreach ($database_query as $query => $var )
            {
                $version['ID'] = $var['ID'];
                $version['Version'] = $var['Version'];
                $version['Name'] = $var['Script'];
                $version['Schema'] = $var['Schema'];
                $version['Sequence'] = $var['Sequence'];
                $version['Description'] = $var['Description'];
                $version['Linha'][] =  anchor('environments/view/'.$var['Environment_ID'],$var['Environment'])
                                                     . '<br> GLPI: ' . $var['GLPI']
                                                     . ' - ' . $var['Date'];

                #$version[$var['Version']]['Date'][] = $var['Date'];
            };

            $last_query = $this->scripts_model->get_sequences($version['Sequence']);
            $last_version = max(array_column($last_query,'Version'));

            $data['last_version'] = $last_version;
            $data['version'] = $version;

            $script = $this->scripts_model->get_scripts($id);
            $data['version']['script'] = $script['Script'];
            $data['version']['observation'] = $script['Observation'];

            $this->load->view('templates/header',$data);
            $this->load->view('scripts/view',$data);
            $this->load->view('templates/footer');
        }


}

<?php
class Environments extends CI_Controller {

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

            $config['attributes'] = array('class' => 'pagination');
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';
            $config['cur_tag_open'] = '<b class=pagination>';
            $config['cur_tag_close'] = '</b>';
            $config['page_query_string'] = TRUE;
            $config['base_url'] = site_url().'/environments/index';
            $config['per_page'] = $data['nrows'];
 
			$data['title'] = 'Environment';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('environments/index','auto','301');
             };

            if ( $field_fill != null )
            {
                $data['active_search'] = 1;

                foreach ($_GET as $var => $get)
                {
                    if ( $var !== 'submit_search' )
                    {
                        $data['search_term'][$var] = $get;
                    };
                };
				
				$config['reuse_query_string'] = TRUE;

                $row_total = $this->environments_model->count_environments_search($data['search_term']);
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->environments_model->search_environments($data['search_term'],$config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->environments_model->count_environments();
                $config['total_rows'] = $row_total;

                #echo br(5). 'Total de linhas' . br(1);
                #print_r($row_total);

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                if (isset($_GET['per_page']))
                {
                    $number_rows = $_GET['per_page'];
                }
                else
                {
                    $number_rows = 0;
                };

                $data['environments'] = $this->environments_model->get_environments($id=null, $config['per_page'],$number_rows);
                $query = $data['environments'];
            };


            #$data['environments'] = $this->environments_model->get_environments();
            #$query = $data['environments'];
			#echo br(10);
			#print_r($query);

            $analyst = $this->analysts_model->get_analysts_type($data['id_user']);

            foreach ($query as $key => $value)
            {
	            $query[$key]['ID'] = anchor('environments/view/'.$value['ID'],$value['ID']);
    	        $query[$key]['Environment'] = anchor('environments/view/'.$value['ID'],$value['Environment']);
                $query[$key]['Delete'] = array( 'data' => anchor('environments/delete/'.$value['ID'],' ','class="fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');
    
                if ( $analyst['Team'] === 'A' )
                {
                    $query[$key]['Delete'] = array( 'data' => anchor('environments/delete/'.$value['ID'],' ','class="disabled_link fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');
                };
			};


            #$fields = $this->db->query($this->db->last_query())->list_fields();
            #$fields[] = 'Delete';

	        $fields[0] = 'ID';
	        $fields[1] = 'Ambiente';
            $fields[] = array('data' => '','class' => 'sorttable_nosort');
	    
            $this->table->set_heading($fields);

            $template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="sortable">');
            $this->table->set_template($template);

            $data['environments_table'] = $this->table->generate( $query );

		    $this->load->view('templates/header', $data);
        	$this->load->view('environments/index', $data);
		    $this->load->view('templates/footer');
        }

        public function view($id = NULL)
        {
            $data = $this->logged->login(); # Login

            if (is_null($id))
            {
                show_404();
            };

			$data['id'] = $id;

            $environment = $this->environments_model->get_environments($id);
            $data['environment'] = $environment['Environment'];

            $config['attributes'] = array('class' => 'pagination');
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';
            $config['cur_tag_open'] = '<b class=pagination>';
            $config['cur_tag_close'] = '</b>';
            $config['page_query_string'] = TRUE;
            $config['base_url'] = site_url().'/environments/view/'.$id;
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Environments';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('environments/view/'.$id,'auto','301');
            };

            if ( $field_fill != null )
            {
                $data['active_search'] = 1;

                foreach ($_GET as $var => $get)
                {
                    if ( $var !== 'submit_search' )
                    {
                        $data['search_term'][$var] = $get;
                    };
                };

                $config['reuse_query_string'] = TRUE;

                $row_total = $this->environments_model->count_evolutions_search($id,$data['search_term']);

                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->environments_model->search_evolutions($id,$data['search_term'],$config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->environments_model->count_evolutions($id);
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                if (isset($_GET['per_page']))
                {
                    $number_rows = $_GET['per_page'];
                }
                else
                {
                    $number_rows = 0;
                };

                $data['environments'] = $this->environments_model->get_evolutions($id , $config['per_page'],$number_rows);
                $query = $data['environments'];
            };

            # Gerando os campos
            $data['environments_table'] = $this->evoltable->table($query,$data['id_user']);

            #echo print_r($query);

            $this->load->view('templates/header', $data);
            $this->load->view('environments/view', $data);
            $this->load->view('templates/footer');
        }

        public function add()
        {
            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);

            $this->form_validation->set_rules('environment', 'Ambiente', 'required|is_unique[environments.name]',  
            array(
                'required'      => 'É preciso inserir um nome de Ambiente.',
                'is_unique'     => 'Este ambiente (%s) já existe.'
        	));

            if ($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header',$data);
                $this->load->view('environments/add');
                $this->load->view('templates/footer');
            }
            else
            {
                $this->environments_model->add_environments();
                redirect('environments/index','auto','301');
            };                    
          
        }

		public function delete($id = NULL)
        {
            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);
 
            $query = $this->environments_model->get_environments($id);

            $data['name'] = $query['Environment'];
			$data['id'] = $id;

         	if (!is_null($this->input->post('submit_delete')))
            {
                $this->environments_model->delete($id);
                redirect('environments/index','auto','301');
            };

            if  (!is_null($this->input->post('submit_cancel')))
            {
                redirect('environments/index','auto','301');
            };

            $data['count'] = $this->environments_model->count_evolutions($id);

            $this->load->view('templates/header',$data);
		    $this->load->view('environments/delete',$data);
    		$this->load->view('templates/footer');

       }
}

<?php
class Accounts extends CI_Controller {

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
            $config['base_url'] = site_url().'/accounts/index';
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Schema';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('accounts/index','auto','301');
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

                $row_total = $this->accounts_model->count_accounts_search($data['search_term']);
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->accounts_model->search_accounts($data['search_term'],$config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->accounts_model->count_accounts();
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

                $data['accounts'] = $this->accounts_model->get_accounts($id=null, $data['nrows'],$number_rows);

                $query = $data['accounts'];
            };

            #$data['accounts'] = $this->accounts_model->get_accounts();
            #$query = $data['accounts'];
			#echo br(10);
			#print_r($query);

            $analyst = $this->analysts_model->get_analysts_type($data['id_user']);

            foreach ($query as $key => $value)
            {
                $query[$key]['ID'] = anchor('accounts/view/'.$value['ID'],$value['ID']);
    	        $query[$key]['Schema'] = anchor('accounts/view/'.$value['ID'],$value['Schema']);
                $query[$key]['Delete'] = array('data' => anchor('accounts/delete/'.$value['ID'],' ','class="fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');

                if ( $analyst['Team'] === 'A' )
                {
                    $query[$key]['Delete'] = array('data' => anchor('accounts/delete/'.$value['ID'],' ','class="disabled_link fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');
                };
			};

            $fields[0] = 'ID';
            $fields[1] = 'Schema';

            $fields[] = array('data' => '','class' => 'sorttable_nosort');

            $this->table->set_heading($fields);

            $template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="sortable">');
            $this->table->set_template($template);

            $data['accounts_table'] = $this->table->generate( $query );

		    $this->load->view('templates/header',$data);
        	$this->load->view('accounts/index',$data);
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

            $account = $this->accounts_model->get_accounts($id);
            $data['schema'] = $account['Schema'];

            $config['attributes'] = array('class' => 'pagination');
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';
            $config['cur_tag_open'] = '<b class=pagination>';
            $config['cur_tag_close'] = '</b>';
            $config['page_query_string'] = TRUE;
            $config['base_url'] = site_url().'/accounts/view/'.$id;
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Accounts';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('accounts/view/'.$id,'auto','301');
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

                $row_total = $this->accounts_model->count_evolutions_search($id,$data['search_term']);

                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->accounts_model->search_evolutions($id,$data['search_term'],$config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->accounts_model->count_evolutions($id);
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

                $data['accounts'] = $this->accounts_model->get_evolutions($id , $config['per_page'],$number_rows);
                $query = $data['accounts'];
            };

            # Gerando os campos
            $data['accounts_table'] = $this->evoltable->table($query,$data['id_user']);

            #echo print_r($query);

            $this->load->view('templates/header', $data);
            $this->load->view('accounts/view', $data);
            $this->load->view('templates/footer');
        }

        public function add()
        {
            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);

            $this->form_validation->set_rules('schema', 'Schema', 'required|is_unique[accounts.name]',  
            array(
                'required'      => 'É preciso inserir um nome de Schema.',
                'is_unique'     => 'Este schema (%s) já existe.'
        	));

            if ($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header', $data);
                $this->load->view('accounts/add');
                $this->load->view('templates/footer');
            }
            else
            {
                $this->accounts_model->add_account();
                redirect('accounts/index','auto','301');
            };                    
          
        }

		public function delete($id = NULL)
        {
            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);
		    
            $query = $this->accounts_model->get_accounts($id);

            $data['name'] = $query['Schema'];
			$data['id'] = $id;

         	if (!is_null($this->input->post('submit_delete')))
            {
                $this->accounts_model->delete($id);
                redirect('accounts/index','auto','301');
            };

            if  (!is_null($this->input->post('submit_cancel')))
            {
                redirect('accounts/index','auto','301');
            };

            $data['count'] = $this->accounts_model->count_evolutions($id);

            $this->load->view('templates/header',$data);
		    $this->load->view('accounts/delete',$data);
    		$this->load->view('templates/footer');

       }
}

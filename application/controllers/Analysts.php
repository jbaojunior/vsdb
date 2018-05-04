<?php
class Analysts extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('html');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div></br>');
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
            $config['base_url'] = site_url().'/analysts/index';
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Environment';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('analysts/index','auto','301');
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

                $row_total = $this->analysts_model->count_analysts_search($data['search_term']);
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->analysts_model->search_analysts($data['search_term'],$config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->analysts_model->count_analysts();
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

                $data['analysts'] = $this->analysts_model->get_analysts($id=null, $config['per_page'],$number_rows);
                $query = $data['analysts'];
            };

            #$data['analysts'] = $this->analysts_model->get_analysts();
            #$query = $data['analysts'];
			#echo br(10);
			#print_r($query);

            $analyst = $this->analysts_model->get_analysts_type($data['id_user']);
            
            foreach ($query as $key => $value)
            {
	        $query[$key]['ID'] = anchor('analysts/view/'.$value['ID'],$value['ID']);
    	        $query[$key]['Analyst'] = anchor('analysts/view/'.$value['ID'],$value['Analyst']);

                if( $value['Team'] === 'D')
                {
                    $team = 'DBA/AD';
                }
                else
                {
                    $team = 'Analista';
                };                    

    	        $query[$key]['Team'] = '<p title=" '. $team . '">'. $value['Team'] . '</p>';


                $query[$key]['Edit'] = array('data' => anchor('analysts/edit/'.$value['ID'],' ','class="fa fa-pencil-square-o fa-2x", title="Editar"'),'class' => 'button_control');
                $query[$key]['Delete'] = array('data' => anchor('analysts/delete/'.$value['ID'],' ','class="fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');

                if ( $analyst['Team'] === 'A' )
                {
                    $query[$key]['Edit'] = array('data' => anchor('analysts/edit/'.$value['ID'],' ','class="disabled_link fa fa-pencil-square-o fa-2x", title="Editar"'),'class' => 'button_control');
                    $query[$key]['Delete'] = array('data' => anchor('analysts/delete/'.$value['ID'],' ','class="disabled_link fa fa-times fa-2x", title="Remover"'),'class' => 'button_control');
                };
                
                unset($query[$key]['Last_Evolutions']);
			};
        
            #$fields = $this->db->query($this->db->last_query())->list_fields();
            $fields[0] = 'ID';     
            $fields[1] = 'Analista';          
            $fields[2] = 'Login';          
            $fields[3] = 'Tipo';          
            $fields[] = '';          
	
            $fields[] = array('data' => '','class' => 'sorttable_nosort');
            $this->table->set_heading($fields);

            $template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="sortable">');
            $this->table->set_template($template);
    
            $data['analysts_table'] = $this->table->generate( $query );

		    $this->load->view('templates/header', $data);
        	$this->load->view('analysts/index', $data);
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

	        $team = $this->analysts_model->get_analysts_type($id);
            $data['login_analyst'] = $team['Login'];

            $config['attributes'] = array('class' => 'pagination');
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';
            $config['cur_tag_open'] = '<b class=pagination>';
            $config['cur_tag_close'] = '</b>';
            $config['page_query_string'] = TRUE;
            $config['base_url'] = site_url().'/analysts/view/'.$id;
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Environments';
            $data['active_search'] = 0;
            $data['search_term'] = array();

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                redirect('analysts/view/'.$id,'auto','301');
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

                $row_total = $this->analysts_model->count_evolutions_search($id, $team['Team'], $data['search_term']);

                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->analysts_model->search_evolutions($id, $team['Team'],$data['search_term'], $config['per_page']);
            }
            else
            {
                $number_row = explode("/",uri_string());

                $row_total = $this->analysts_model->count_evolutions($id,$team['Team']);
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
				
                $data['analysts'] = $this->analysts_model->get_evolutions($id , $team['Team'], $config['per_page'], $number_rows);
                $query = $data['analysts'];
            };

            # Gerando os campos
            $data['analysts_table'] = $this->evoltable->table($query,$data['id_user']);

		    $this->load->view('templates/header', $data);
        	$this->load->view('analysts/view', $data);
		    $this->load->view('templates/footer');
        }

        public function add($fill = NULL)
        {
#            echo br(5);
#            print_r($fill);

            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);

            $this->form_validation->set_rules('analyst', 'Analista', 'trim|required|callback_userCheck|min_length[5]|max_length[50]',  
            array(
                'required'  => 'É preciso inserir um nome de Analista.',
                'userCheck' => 'Este analista (%s) já existe.'
        	));

            $this->form_validation->set_rules('login', 'Login', 'trim|required|regex_match[/^[a-z0-9]+([._-][a-z0-9]+)?$/]|callback_loginCheck|min_length[4]|max_length[30]',  
            array(
                'required'      => 'É preciso inserir um Login.',
                'loginCheck'    => 'Este login (%s) já existe.',
                'regex_match'   => 'Login fora do padrão. O login utiliza apenas letras minusculas, numeros e ponto.'
        	));

            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[20]',
		    array(
			    'required'    => 'Por favor preencha o campo senha.',
			    'min_length'  => 'A senha deve ter no mínimo 5 caracteres.'
			));

    		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]',
			array(
			    'required'   => 'Por favor preencha o campo de confirmação da senha.',
				'matches'    => 'As senhas estão diferentes. Por favor verifique.'
			));

            $this->form_validation->set_rules('number_rows', 'Number Rows', 'trim|required|max_length[2]',
			array(
			    'required'   => 'O item "Exibir" não pode ser vazio'
			));

            if ($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header',$data);
                $this->load->view('analysts/add',$fill);
                $this->load->view('templates/footer');
            }
            else
            {
                $this->analysts_model->add_analysts();
                redirect('analysts/index','auto','301');
            };                    
          
        }

        public function userCheck($name)
        {           
            $resultName = $this->analysts_model->get_analysts_name($name);

            if ( isset($resultName) )
            {
                $change = strcmp($name,$resultName['Analyst']);
                if ( $change != 0 )
                {
                    return TRUE;
                }
                else 
                {  
                    return FALSE;
                };
            }
            else
            {
                return TRUE;
            };
        }

        public function loginCheck($login)
        {           
            $resultLogin = $this->analysts_model->get_analysts_login($login);

            if ( isset($resultLogin) )
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            };
        }

        public function _loginRegex($login) 
        {
            if (preg_match('/^[a-z0-9]+$/', $login ) ) 
            {
                return TRUE;
            } 
            else 
            {
                return FALSE;
            };
        }

		public function delete($id = NULL)
        {
            $data = $this->logged->login(); # Login
            $this->logged->permission($data['team_user']);

            $query = $this->analysts_model->get_analysts($id);

            $data['name'] = $query['Analyst'];
			$data['id'] = $id;

         	if (!is_null($this->input->post('submit_delete')))
            {
                $this->analysts_model->delete($id);
                redirect('analysts/index','auto','301');
            };

            if  (!is_null($this->input->post('submit_cancel')))
            {
                redirect('analysts/index','auto','301');
            };

            $this->load->view('templates/header',$data);
		    $this->load->view('analysts/delete',$data);
    		$this->load->view('templates/footer');
       }

	public function conta($id = NULL)
	{
		$data = $this->logged->login(); # Login
		$query = $this->analysts_model->get_analysts($id);

		foreach ($query as $type => $element)
		{
			$data[$type] = $element;
		};

        $change_pass = 0;
        $change_conf = 0;

        if (!is_null($this->input->post('submit_password')))
        {
            $change_pass = 1;
		    $this->form_validation->set_rules('password', 'Password', 'required',
								array(
						                'required'   => 'Por favor preencha o campo senha.'
			        			));

    		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]',
								array(
						                'required'   => 'Por favor preencha o campo de confirmação da senha.',
						                'matches'    => 'As senhas estão diferentes. Por favor verifique.'
			        			));
        };                               

        if ( !is_null($this->input->post('submit_conf')))
        {
            $change_conf = 1;
		    $this->form_validation->set_rules('number_rows', 'Nrows', 'required',
								array(
						                'required'   => 'O item "Exibir" não pode ser vazio'
			        			));

        };                                

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('templates/header',$data);
			$this->load->view('analysts/conta',$data);
			$this->load->view('templates/footer');
		}
		else
		{
            if ($change_pass === 1)
            {
    			$saida = $this->analysts_model->change_password($id,md5($this->input->post('password')));

                if ($saida == 1)
                {
                    $data['msg'] = 'Senha atualizada com sucesso';
                };
            };

            if ($change_conf === 1)
            {
    			$saida = $this->analysts_model->configuration_account($id);

                if ($saida == 1)
                {
                    $data['msg'] = 'Configuração atualizada com sucesso';
                };
            };

            $this->load->view('templates/header',$data);
	    	$this->load->view('analysts/conta',$data);
		    $this->load->view('templates/footer');
	   };
	}

	public function edit($id = NULL)
	{
		$data = $this->logged->login(); # Login
		$query = $this->analysts_model->get_analysts($id);

        $this->logged->permission($data['team_user']);

		foreach ($query as $type => $element)
		{
			$data[$type] = $element;
		};

        $change_pass = 0;
        $change_conf = 0;
        $change_name = 0;
        $change_login = 0;

        if (!is_null($this->input->post('submit_password')))
        {
            $change_pass = 1;
		    $this->form_validation->set_rules('password', 'Password', 'required',
								array(
						                'required'   => 'Por favor preencha o campo senha.'
			        			));

    		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]',
								array(
						                'required'   => 'Por favor preencha o campo de confirmação da senha.',
						                'matches'    => 'As senhas estão diferentes. Por favor verifique.'
			        			));
        };                               

        if ( !is_null($this->input->post('submit_conf')))
        {
            $change_conf = 1;
		    $this->form_validation->set_rules('number_rows', 'Nrows', 'required',
								array(
						                'required'   => 'O item "Exibir" não pode ser vazio'
			        			));

            if ( $this->input->post('analyst') != $data['Analyst'] )
            {
                $change_name = 1;
                $this->form_validation->set_rules('analyst', 'Analista', 'trim|required|callback_userCheck|min_length[5]|max_length[50]',  
                                array(
                                        'required'  => 'É preciso inserir um nome de Analista.',
                                        'userCheck' => 'Este analista (%s) já existe.'
                                ));
            };

           if ( $this->input->post('login') != $data['Login'] )
           {
                $change_login = 1;
                $this->form_validation->set_rules('login', 'Login', 'trim|required|regex_match[/^[a-z0-9]+([._-][a-z0-9]+)?$/]|callback_loginCheck|min_length[4]|max_length[30]',  
                                 array(
                                        'required'      => 'É preciso inserir um Login.',
                                        'loginCheck'    => 'Este login (%s) já existe.',
                                        'regex_match'   => 'Login fora do padrão. O login utiliza apenas letras minusculas, numeros e ponto.'
                                ));
           };
        };                                

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('templates/header',$data);
			$this->load->view('analysts/edit',$data);
			$this->load->view('templates/footer');
		}
		else
		{
            if ($change_pass === 1)
            {
    			$saida = $this->analysts_model->change_password($id,md5($this->input->post('password')));

                if ($saida == 1)
                {
                    $data['msg'] = 'Senha atualizada com sucesso';
                };
            };

            if ($change_conf === 1)
            {
    			$saida = $this->analysts_model->configuration($id,$change_name,$change_login);

                if ($saida == 1)
                {
                    $data['msg'] = 'Configuração atualizada com sucesso';
                };
            };

            
            $this->load->view('templates/header',$data);
         	$this->load->view('analysts/edit',$data);
    	    $this->load->view('templates/footer');
        };
    }
}

<?php
class Evolutions extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('html');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div><br>');
        }

        public function index()
        {
            $data = $this->logged->login();	 # Login

            if ( isset($data['sortby']) and isset($data['sorder']) )
            {
                $_GET['sortby'] = $data['sortby'];
                $_GET['sorder'] = $data['sorder'];
            };

            $config['attributes'] = array('class' => 'pagination');
    	    $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';
	        $config['cur_tag_open'] = '<b class=pagination>';
    	    $config['cur_tag_close'] = '</b>';
            $config['page_query_string'] = TRUE;
            $config['base_url'] = site_url().'/evolutions/index';
            $config['per_page'] = $data['nrows'];

            $data['title'] = 'Evolutions';
	        $data['active_search'] = 0;
            $data['search_term'] = array();

            if ( empty($_GET) and !empty($_SESSION['logged_in']['get_search']) )
            {
                $_GET = $_SESSION['logged_in']['get_search'];
                unset($_GET['per_page']);
            }
            else
            {
                $_SESSION['logged_in']['get_search'] = $_GET;
            };

            $field_fill = $this->input->get('field0');

            if ( $this->input->get('reset_search') != null )
            {
                $field_fill = null;
                $data['get_search'] = null;
                unset($_SESSION['logged_in']['get_search']);
                $_GET = null;
             };

            $data['get_search'] = $_GET;
            $search = $data['get_search'];
            unset($search['sortby']);
            unset($search['sorder']);
            unset($search['per_page']);

            if ( $field_fill != null or !empty($search) )
            {
                $data['active_search'] = 1;
                #$_SESSION['logged_in']['get_search'] = $data['get_search'];

                foreach ($search as $var => $get)
                {
                    if ( $var !== 'submit_search' )
                    {
                        $data['search_term'][$var] = $get;
                    };
                };
   
                $config['reuse_query_string'] = TRUE;

                $row_total = $this->evolutions_model->count_evolutions_search($data['search_term']);
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $query = $this->evolutions_model->search_evolutions($data['search_term'],$config['per_page']);
            } 
            else 
            {
                $row_total = $this->evolutions_model->count_evolutions();
                $config['total_rows'] = $row_total;

                $this->pagination->initialize($config);
                $data['pager'] = $this->pagination->create_links();

                $data['evolutions'] = $this->evolutions_model->get_evolutions($id=null, $data['nrows'],$data['get_search']);
                $query = $data['evolutions'];
            };

            # Gerando os campos
            $data['evolution_table'] = $this->evoltable->table($query,$data['id_user']);

	    $this->load->view('templates/header', $data);
        $this->load->view('evolutions/index', $data);
	    $this->load->view('templates/footer');
        }

        public function view($id = NULL)
        {
			$data = $this->logged->login();	# Login

            $data['evolutions_item'] = $this->evolutions_model->get_evolutions($id);

	        if (empty($data['evolutions_item']))
    	    {
       	        show_404();
	        }

    	    $data['title'] = $data['evolutions_item']['id'];
	
    	    $this->load->view('templates/header', $data);
	        $this->load->view('evolutions/view',  $data);
    	    $this->load->view('templates/footer');

        }

        public function add()
        {
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            foreach ($this->evolutions_model->get_environments_name('Desenvolvimento') as $key) 
            {
                   $data['form_environment'][$key['Environment_ID']] = $key['Environment'];
            };

            foreach ($this->evolutions_model->get_schemas() as $key) 
            {
                   $data['form_schema'][$key['Schema_ID']] = $key['Schema'];
            };

            foreach ($this->evolutions_model->get_analysts() as $key) 
            {
                   $data['form_analyst'][$key['Analyst_ID']] = $key['Analyst'];
            };

            foreach ($this->evolutions_model->get_analysts_DBA() as $key) 
            {
                   $data['form_analyst_DBA'][$key['Analyst_DBA_ID']] = $key['Analyst'];
            };
            
            $this->form_validation->set_rules('glpi', 'GLPI', 'required|trim|numeric',
                        array(
                            'required'   => 'O item "GLPI" não pode ser vazio'
                        ));

            $this->form_validation->set_rules('script', 'Script', 'required',
                        array(
                            'required'   => 'O item "Script" não pode ser vazio'
                        ));

		    if ($this->form_validation->run() === FALSE)
	    	{
            	$this->load->view('templates/header',$data);
		        $this->load->view('evolutions/add',$data);
    		    $this->load->view('templates/footer');
		    }
    		else
    		{
        		$this->evolutions_model->add_evolution();
				redirect('evolutions/index','auto','301');
    		};

        }

        public function evol($id = NULL)
        {
            $data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            if ( isset($data['get_search']) and !empty($_GET) )
            {
                $urldata = '?';
                foreach ($data['get_search'] as $key => $value)
                {
                    $value = htmlspecialchars_decode($value);
                    $urldata = $urldata . $key . '=' . $value . '&';
                };
            }
            else 
            {
                $data['get_search'] = '';
            };

            $database_query['evolutions_item'] = $this->evolutions_model->get_evolutions($id);

            $data['id']             = $database_query['evolutions_item']['ID'];
            $data['script_name']    = $database_query['evolutions_item']['Script'];
            $data['description']    = $database_query['evolutions_item']['Description'];
            $data['script_id']      = $database_query['evolutions_item']['Script_ID'];
            $data['sequence_id']    = $database_query['evolutions_item']['Sequence'];
            $data['schema_id']      = $database_query['evolutions_item']['Schema_ID'];
            $data['schema']         = $database_query['evolutions_item']['Schema'];
            $data['environment_id'] = $database_query['evolutions_item']['Environment_ID'];
            $data['environment']    = $database_query['evolutions_item']['Environment'];
            $data['event']          = $database_query['evolutions_item']['Event'];

            $data['form_schema'][$data['schema_id']] = $data['schema'];

            $data['msg'] = '';
            if ($data['event'] === 'E')
            {
                $data['msg'] = 'Não é possível evoluir um item que já foi evoluido';
            }

            foreach ($this->evolutions_model->get_scripts($database_query['evolutions_item']['Script_ID']) as $key) 
            {
                   $data['script'] = $key['Script'];
                   $data['observation'] = $key['Observation'];
            };

            if ( $data['environment'] === 'Desenvolvimento')
            {
                $name_environment = array ('Homologação');
                $query=$this->evolutions_model->get_environments_name($name_environment);
                $data['form_environment'][$query[0]['Environment_ID']] = $query[0]['Environment'];
            }
            elseif ( $data['environment'] === 'Homologação' )
            {
                $name_environment = array ('Qualidade');
                foreach ($this->evolutions_model->get_environments_name($name_environment) as $key) 
                {
                    $data['form_environment'][$key['Environment_ID']] = $key['Environment'];
                };

            }
            elseif ( $data['environment'] === 'Qualidade' )
            {
                $name_environment = array ('Produção');
                $query=$this->evolutions_model->get_environments_name($name_environment);
                $data['form_environment'][$query[0]['Environment_ID']] = $query[0]['Environment'];
            };
 
            foreach ($this->evolutions_model->get_analysts() as $key) 
            {
               	   $data['form_analyst'][$key['Analyst_ID']] = $key['Analyst'];
            };

            $data['analyst_default'] = $this->evolutions_model->get_analysts($database_query['evolutions_item']['Analyst_ID']);

            foreach ($this->evolutions_model->get_analysts_DBA() as $key) 
            {
       		   $data['form_analyst_DBA'][$key['Analyst_DBA_ID']] = $key['Analyst'];
            };
            
            $this->form_validation->set_rules('glpi', 'GLPI', 'required|trim|numeric',
                        array(
				'required'   => 'O item "GLPI" não pode ser vazio'
                        ));

	        if ($this->form_validation->run() === FALSE or $data['msg'] != '')
	    	{
                $this->load->view('templates/header',$data);
		        $this->load->view('evolutions/evol',$data);
    		    $this->load->view('templates/footer');
		    }
    		else
    		{
        		$this->evolutions_model->evolve($data['id'],$data['schema_id'],$data['script_id'],$data['sequence_id']);
				redirect('evolutions/index' . $urldata ,'auto','301');
            }
        }

        public function edit($id = NULL)
        {
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            if ( isset($data['get_search']) )
            {
                $urldata = '?';
                foreach ($data['get_search'] as $key => $value)
                {
                    $value = htmlspecialchars_decode($value);
                    $urldata = $urldata . $key . '=' . $value . '&';
                };
            };

            $database_query['evolutions_item'] = $this->evolutions_model->get_evolutions($id);

            $data['id']               = $database_query['evolutions_item']['ID'];
            $data['script_name']      = $database_query['evolutions_item']['Script'];
            $data['description']      = $database_query['evolutions_item']['Description'];
            $data['script_id']        = $database_query['evolutions_item']['Script_ID'];
            $data['sequence_id']      = $database_query['evolutions_item']['Sequence'];
            $data['schema_id']        = $database_query['evolutions_item']['Schema_ID'];
            $data['schema']           = $database_query['evolutions_item']['Schema'];
            $data['environment_id']   = $database_query['evolutions_item']['Environment_ID'];
            $data['environment']      = $database_query['evolutions_item']['Environment'];
            $data['version']          = $database_query['evolutions_item']['Version'];
            
            $data['form_schema'][$data['schema_id']] = $data['schema'];
            $data['form_environment'][$data['environment_id']] = $data['environment'];

            foreach ($this->evolutions_model->get_scripts($database_query['evolutions_item']['Script_ID']) as $key) 
            {
                   $data['script'] = $key['Script'];
                   $data['observation'] = $key['Observation'];
            };

            foreach ($this->evolutions_model->get_analysts() as $key) 
            {
                   $data['form_analyst'][$key['Analyst_ID']] = $key['Analyst'];
            };

            foreach ($this->evolutions_model->get_analysts_DBA() as $key) 
            {
                   $data['form_analyst_DBA'][$key['Analyst_DBA_ID']] = $key['Analyst'];
            };
            
            $data['analyst_default'] = $this->evolutions_model->get_analysts($database_query['evolutions_item']['Analyst_ID']);

            $this->form_validation->set_rules('glpi', 'GLPI', 'required',
                 array(
                    'required'   => 'O item "GLPI" não pode ser vazio'
                 ));

			if ($this->form_validation->run() === FALSE)
	    	{
                $this->load->view('templates/header',$data);
		        $this->load->view('evolutions/edit',$data);
    		    $this->load->view('templates/footer');
		    }
    		else
    		{
                $this->evolutions_model->edit($data['environment_id'],$data['schema_id'],$data['script_id'],$data['version'],$data['sequence_id'],$data['environment']);
				redirect('evolutions/index' . $urldata,'auto','301');
    		}

        }

        public function delete($id = NULL)
        {
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            if  (!is_null($this->input->post('submit_cancel')))
            {
                redirect('evolutions/index','auto','301');
            };

            $database_query['evolutions_item'] = $this->evolutions_model->get_evolutions($id);

            $data['id']               = $database_query['evolutions_item']['ID'];
            $data['glpi']      		  = $database_query['evolutions_item']['GLPI'];
            $data['script_name']      = $database_query['evolutions_item']['Script'];
            $data['description']      = $database_query['evolutions_item']['Description'];
            $data['script_id']        = $database_query['evolutions_item']['Script_ID'];
            $data['sequence_id']      = $database_query['evolutions_item']['Sequence'];
            $data['schema_id']        = $database_query['evolutions_item']['Schema_ID'];
            $data['schema']           = $database_query['evolutions_item']['Schema'];
            $data['environment_id']   = $database_query['evolutions_item']['Environment_ID'];
            $data['environment']      = $database_query['evolutions_item']['Environment'];
            $data['version']          = $database_query['evolutions_item']['Version'];

            $row_glpi = $this->evolutions_model->get_evolutions_glpi($data['glpi'],$data['environment_id']);

            if (!is_null($this->input->post('submit_delete')))
            {
                print_r($row_glpi);
                $this->evolutions_model->delete($id,$row_glpi['Evolution']);
                redirect('evolutions/index','auto','301');
            };

            $this->load->view('templates/header',$data);
		    $this->load->view('evolutions/delete',$data);
    		$this->load->view('templates/footer');

        }

        public function sequence( $sequence_id )
        {
			$data = $this->logged->login();	# Login

            $database_query = $this->evolutions_model->get_sequences($sequence_id);
            
            $last_version = max(array_column($database_query,'Version'));
            $last_version = $this->evolutions_model->last_version($sequence_id,$last_version);
            $data['last'] = $last_version;
            
            unset($version);
            foreach ($database_query as $query => $var )
            {
                $version[$var['Version']]['Script'] = $var['Script_ID']; 
                $version[$var['Version']]['Linha'][] =  anchor('environments/view/'.$var['Environment_ID'],$var['Environment'])
                                                     . '<br> GLPI: ' . $var['GLPI']
                                                     . ' - ' . $var['Date'];

                #$version[$var['Version']]['Date'][] = $var['Date'];
            };
         
            $data['version'] = $version;

            $this->load->view('templates/header',$data);
            $this->load->view('evolutions/sequence',$data);
            $this->load->view('templates/footer');
        }

        public function get_scripts_desenvolvimento()
        {
            $this->load->helper('file');
            $this->load->helper('download');
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            $database_query = $this->evolutions_model->scripts_desenvolvimento();

            $directory='/tmp/vsdb';

            if ( !file_exists($directory) )
            {
                mkdir($directory);
            }
            else
            {
                delete_files($directory . '/');
            };

            if ( !is_writable($directory) )
            {
                echo 'O diretório '. $directory . ' não está com permissão de escrita';
                exit();
            };

            $script_file = $directory . '/script_desenvolvimento_' . date('ymdHisu') . '_' . $data['login'] . '.sql';
            $line = ' SCRIPT GERADO EM '. date('d/m/y H:i:s') . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data);
            
            $line = ' NUMERO DE SCRIPTS: ' . count($database_query) . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data,'a');

            foreach ($database_query as $query => $value)
            {
                $data = "\n" . "\n" . str_pad('',110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = ' SCRIPT ';
                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $data = 'prompt "' . str_pad('',110,'-',STR_PAD_BOTH) . '";'  . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');
            
                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');
            
                $line = ' SCRIPT ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = $value['Script'] . "\n";
                write_file($script_file,$data,'a');

                $line = ' FINAL SCRIPT - SEQUENCE '. $value['Sequence'] . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');
            };

            force_download($script_file,NULL);
        }

        public function get_scripts_homologacao()
        {
            $this->load->helper('file');
            $this->load->helper('download');
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            $database_query = $this->evolutions_model->scripts_homologacao();

            $directory='/tmp/vsdb';

            if ( !file_exists($directory) )
            {
                mkdir($directory);
            }
            else
            {
                delete_files($directory . '/');
            };

            if ( !is_writable($directory) )
            {
                echo 'O diretório '. $directory . ' não está com permissão de escrita';
                exit();
            };

            $script_file = $directory . '/script_homologacao_' . date('ymdHisu') . '_' . $data['login'] . '.sql';
            $line = ' SCRIPT GERADO EM '. date('d/m/y H:i:s') . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data);
            
            $line = ' NUMERO DE SCRIPTS: ' . count($database_query) . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data,'a');

            foreach ($database_query as $query => $value)
            {
                $data = "\n" . "\n" . str_pad('',110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = ' SCRIPT ';
                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $data = 'prompt "' . str_pad('',110,'-',STR_PAD_BOTH) . '";'  . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');
            
                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');
            
                $line = ' SCRIPT ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = $value['Script'] . "\n";
                write_file($script_file,$data,'a');

                $line = ' FINAL SCRIPT - SEQUENCE '. $value['Sequence'] . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');
            };

            force_download($script_file,NULL);
        }

        public function get_scripts_qualidade()
        {
            $this->load->helper('file');
            $this->load->helper('download');
			$data = $this->logged->login();	# Login
            $this->logged->permission($data['team_user']);

            $database_query = $this->evolutions_model->scripts_qualidade();

            $directory='/tmp/vsdb';

            if ( !file_exists($directory) )
            {
                mkdir($directory);
            }
            else
            {
                delete_files($directory . '/');
            };

            if ( !is_writable($directory) )
            {
                echo 'O diretório '. $directory . ' não está com permissão de escrita';
                exit();
            };

            $script_file = $directory . '/script_qualidade_' . date('ymdHisu') . '_' . $data['login'] . '.sql';
            $line = ' SCRIPT GERADO EM '. date('d/m/y H:i:s') . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data);
            
            $line = ' NUMERO DE SCRIPTS: ' . count($database_query) . ' ';
            $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
            write_file($script_file,$data,'a');

            foreach ($database_query as $query => $value)
            {
                $data = "\n" . "\n" . str_pad('',110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = str_pad($line,110,'-',STR_PAD_RIGHT) . "\n";
                write_file($script_file,$data,'a');

                $line = ' SCRIPT ';
                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' GLPI: '. $value['GLPI'] . '  SCHEMA: ' . $value['Schema'] . '  SEQUENCE: ' . $value['Sequence'] . '  VERSION: ' . $value['Version'] . '  DATE: ' . $value['Date'] . ' ';
                $data = 'prompt "' . str_pad('',110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $line = '---------- '. ' DESCRIPTION: '. substr($value['Description'],0,71) . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');

                $line = ' SCRIPT ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_RIGHT) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = $value['Script'] . "\n";
                write_file($script_file,$data,'a');

                $line = ' FINAL SCRIPT - SEQUENCE '. $value['Sequence'] . ' ';
                $data = 'prompt "' . str_pad($line,110,'-',STR_PAD_BOTH) . '";' . "\n";
                write_file($script_file,$data,'a');

                $data = str_pad($line,110,'-',STR_PAD_BOTH) . "\n";
                write_file($script_file,$data,'a');
            };

            force_download($script_file,NULL);
        }
}

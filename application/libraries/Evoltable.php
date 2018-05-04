<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evoltable {

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
		$this->CI->load->library('table');
    }

	function select( $last_version = NULL )
	{
            $this->CI->db->select('ev.glpi "GLPI",
                               en.name "Environment",
                               ac.name "Schema", 
                               ev.sequence_id "Sequence",
                               sc.version "Version",
                               ev.description "Description", 
                               date_format(ev.dt_create,\'%d/%m/%y %H:%i\') "Date",
                               an.name "Analyst",
                               anD.name "Analyst_DBA", 
                               ev.event "Event",
                               date_format(ev.dt_evolution,\'%d/%m/%y %H:%i\') "Evolution_Date",
                               ev.evolution_glpi "Evolution_GLPI", 
                               enV.name "Ev_Environment",
                               ev.id "ID",
                               en.id "Environment_ID",
                               ac.id "Schema_ID", 
                               sc.id "Script_ID",
                               sc.name "Script", 
                               an.id "Analyst_ID",
                               anD.id "Analyst_DBA_ID",
                               enV.id "Ev_Environment_ID",
                               an.login "Analyst_Login",
                               anD.login "Analyst_DBA_Login", 
                            ');

            $this->CI->db->from('evolutions ev');
            $this->CI->db->join('accounts ac','ac.id = ev.account_id');
            $this->CI->db->join('environments en','en.id = ev.environment_id');
            $this->CI->db->join('environments enV','ev.evolution_environment_id = enV.id','left');
            $this->CI->db->join('analysts an','an.id = ev.analyst_id');
            $this->CI->db->join('analysts anD','anD.id = ev.analyst_dba_id');
            $this->CI->db->join('scripts sc','sc.id = ev.script_id');


            if ( !is_null($last_version) )
            {
                #echo br(5);
                #echo 'last_version is not null';
                $this->CI->db->join('sequences_versions sv','ev.sequence_id = sv.sequence_id and sc.version = sv.version');
            };

            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->CI->db->where($where);

            $data_logged = $this->CI->logged->login();           

            if ( $data_logged['last_evolutions'] == 1 )
            {
                $where = "(ev.event = 'N')";
                $this->CI->db->where($where);
            };
	}

	function make_scripts($env = NULL)
	{
            $this->CI->db->select('ev.glpi "GLPI",
                               en.name "Environment",
                               ac.name "Schema", 
                               ev.sequence_id "Sequence",
                               sc.version "Version",
                               ev.description "Description", 
                               date_format(ev.dt_create,\'%d/%m/%y %H:%i\') "Date",
                               an.name "Analyst",
                               anD.name "Analyst_DBA", 
                               ev.event "Event",
                               date_format(ev.dt_evolution,\'%d/%m/%y %H:%i\') "Evolution_Date",
                               ev.evolution_glpi "Evolution_GLPI", 
                               enV.name "Ev_Environment",
                               ev.id "ID",
                               en.id "Environment_ID",
                               ac.id "Schema_ID", 
                               sc.id "Script_ID",
                               an.id "Analyst_ID",
                               anD.id "Analyst_DBA_ID",
                               enV.id "Ev_Environment_ID",
                               an.login "Analyst_Login",
                               anD.login "Analyst_DBA_Login", 
                               sc.script "Script"
                            ');

            $this->CI->db->from('evolutions ev');
            $this->CI->db->join('accounts ac','ac.id = ev.account_id');
            $this->CI->db->join('environments en','en.id = ev.environment_id');
            $this->CI->db->join('environments enV','ev.evolution_environment_id = enV.id','left');
            $this->CI->db->join('analysts an','an.id = ev.analyst_id');
            $this->CI->db->join('analysts anD','anD.id = ev.analyst_dba_id');
            $this->CI->db->join('scripts sc','sc.id = ev.script_id');
            $this->CI->db->join('sequences_versions sv','ev.sequence_id = sv.sequence_id and sc.version = sv.version');
            
            $where = "(ev.is_excluded is null or ev.is_excluded = 0)";
            $this->CI->db->where($where);

            $where = "(ev.event = 'N')";
            $this->CI->db->where($where);

            $this->CI->db->order_by('ev.dt_create','asc');

            if ($env == 'desenvolvimento')
            {
                $where = "(en.name <> 'Produção')";
                $this->CI->db->where($where);
            };

            if ($env == 'homologacao')
            {
                $where = "(en.name <> 'Produção')";
                $this->CI->db->where($where);

                $where = "(en.name <> 'Desenvolvimento')";
                $this->CI->db->where($where);

            };

            if ($env == 'qualidade')
            {
                $where = "(en.name = 'Qualidade')";
                $this->CI->db->where($where);
            };

	}

    function table($query,$id)
    {
#        print_r($query);

        $analyst = $this->CI->analysts_model->get_analysts_type($id);

		foreach ($query as $key => $value)
		{

            $last_version = $this->CI->evolutions_model->get_last_version($value['Sequence']);

            $glpi_link = $this->CI->config->item('glpi_link');
            $query[$key]['GLPI'] = anchor_popup($glpi_link . $value['GLPI'],$value['GLPI']);            

			$query[$key]['Sequence'] = array('data' => anchor('evolutions/sequence/'.$value['Sequence'],$value['Sequence']),'class' => 'center');

            if ( $value['Environment'] == 'Produção' )
            {
                $color='red';
            };

            if ( $value['Environment'] == 'Qualidade' )
            {
                $color='darkorchid';
            };

            if ( $value['Environment'] == 'Homologação' )
            {
                $color='darkgoldenrod';
            };

            if ( $value['Environment'] == 'Desenvolvimento' )
            {
                $color='darkcyan';
            };

			$query[$key]['Environment'] = anchor('environments/view/'.$value['Environment_ID'],
				'<p style="color: '. $color . '; font-weight: bold;"  title="' .  $value['Environment'] . '">' . $value['Environment'] . '</p>');

			$query[$key]['Schema'] = anchor('accounts/view/'.$value['Schema_ID'],$value['Schema']);

/*
            if ($value['Version'] != $last_version['Version'])
            {
    			$query[$key]['Version'] = anchor('scripts/view/'.$value['Script_ID'],$value['Version']);
            }
            else
            {
    			$query[$key]['Version'] = anchor('scripts/view/'.$value['Script_ID'],
                    '<p class=aviso_vermelho title="Última Versão">'. $value['Version']. '</p>');
            };
*/            			
    	    $query[$key]['Version'] = array('data' => anchor('scripts/view/'.$value['Script_ID'],
                    '<p class=aviso_vermelho title="Última Versão">'. $value['Version']. '</p>'), 'class' => 'center') ;

			$query[$key]['Analyst'] = anchor('analysts/view/'.$value['Analyst_ID'],
				'<p title="' . $value['Analyst'] . '">' . $value['Analyst_Login'] . '</p>');

			$query[$key]['Analyst_DBA'] = anchor('analysts/view/'.$value['Analyst_DBA_ID'],
				'<p title="' . $value['Analyst_DBA'] . '">' . $value['Analyst_DBA_Login'] . '</p>');
			
			if (!is_null($value['Ev_Environment_ID']))
			{
#				$query[$key]['Ev_Environment'] = array('data' => anchor('environments/view/'.$value['Ev_Environment_ID'],
#					'<p title="' . $value['Ev_Environment'] . '">' . substr($value['Ev_Environment'],0,5) . '</p>'),
#                    'class' => 'center');

				$query[$key]['Ev_Environment'] = array('data' => anchor('environments/view/'.$value['Ev_Environment_ID'],
					'<p title="' . $value['Ev_Environment'] . '">' . $value['Ev_Environment'] . '</p>'),
                    'class' => 'center');
			};

			if ($value['Description'] != '')
			{
				if ( strlen($value['Description']) > 20 )
				{
					$query[$key]['Description'] = '<p title="' . $value['Description']. '">' . substr($value['Description'],0,20) . '...' . '</p>';
				}
				else
				{
					$query[$key]['Description'] = '<p title="' . $value['Description']. '">' . $value['Description'] . '</p>';
				};
			};
   
			$date_explode = explode(' ',$value['Date']);
			$query[$key]['Date'] = '<p title="' . $value['Date'] . '">' . $date_explode[0] . '</p>';

			if ($value['Evolution_Date'] != '')
			{
				$date_explode = explode(' ',$value['Evolution_Date']);
				$query[$key]['Evolution_Date'] = '<p title="' . $value['Evolution_Date'] . '">' . $date_explode[0] . '</p>';
			}; 

			if ( $value['Environment'] === 'Produção' )
			{
				$query[$key]['Evol'] = array( 'data' => anchor('evolutions/evol/'.$value['ID'],' ','class="disabled_link fa fa-level-up fa-2x", title="Evolução"'), 'class' => 'button_control');
				$query[$key]['Edit'] = array( 'data' => anchor('evolutions/edit/'.$value['ID'],' ','class="disabled_link fa fa-pencil-square-o fa-2x", title="Editar"'),'class' => 'button_control');                  
			}
			else
			{

				if ($value['Event'] === 'E' or $value['Version'] != $last_version['Version'])
				{
                        $query[$key]['Evol'] = array( 'data' => anchor('evolutions/evol/'.$value['ID'],' ','class="disabled_link fa fa-level-up fa-2x", title="Evolução"'),'class' => 'button_control');
				}
                else
				{
					$query[$key]['Evol'] = array( 'data' => anchor('evolutions/evol/'.$value['ID'],' ','class="fa fa-level-up fa-2x", title="Evolução"'), 'class' => 'button_control');
				};

				if ($value['Environment'] === 'Desenvolvimento')
				{
                    if (($value['Event'] === 'N' and $value['Version'] == $last_version['Version']) or $value['Version'] == $last_version['Version'] )
                    {
        				$query[$key]['Edit'] = array( 'data' => anchor('evolutions/edit/'.$value['ID'],' ','class="fa fa-pencil-square-o fa-2x", title="Editar"'), 'class' => 'button_control');
                              
                    }
                    else
                    {
    					$query[$key]['Edit'] = array( 'data' => anchor('evolutions/edit/'.$value['ID'],' ','class="disabled_link fa fa-pencil-square-o fa-2x", title="Editar"'), 'class' => 'button_control');
                    };
				}
				else
				{
					$query[$key]['Edit'] = array( 'data' => anchor('evolutions/edit/'.$value['ID'],' ','class="disabled_link fa fa-pencil-square-o fa-2x", title="Editar"'), 'class' => 'button_control');
				};
			
			};

			if ($value['Event'] === 'N')
			{
				$query[$key]['Event'] = '<p title="Nova Versão">' . $value['Event'] . '</p>';  
			    $query[$key]['Delete'] = array( 'data' => anchor('evolutions/delete/'.$value['ID'],' ','class="fa fa-times fa-2x", title="Remover"'), 'class' => 'button_control');
			}
			else if ($value['Event'] === 'E')
			{   
				$query[$key]['Event'] = '<p title="Versão Evoluida">' . $value['Event'] . '</p>';
			    $query[$key]['Delete'] = array( 'data' => anchor('evolutions/delete/'.$value['ID'],' ','class="disabled_link fa fa-times fa-2x", title="Remover"'), 'class' => 'button_control');
			};

            if ( $analyst['Team'] === 'A' )
            {
                $query[$key]['Evol'] = array( 'data' => anchor('evolutions/evol/'.$value['ID'],' ','class="disabled_link fa fa-level-up fa-2x", title="Evolução"'),'class' => 'button_control');
                $query[$key]['Edit'] = array( 'data' => anchor('evolutions/edit/'.$value['ID'],' ','class="disabled_link fa fa-pencil-square-o fa-2x", title="Editar"'), 'class' => 'button_control');
			    $query[$key]['Delete'] = array( 'data' => anchor('evolutions/delete/'.$value['ID'],' ','class="disabled_link fa fa-times fa-2x", title="Remover"'), 'class' => 'button_control');
            };

			# Removendo os campos do array
			unset($query[$key]['ID']);
			unset($query[$key]['Environment_ID']);
			unset($query[$key]['Ev_Environment_ID']);
			unset($query[$key]['Schema_ID']);
			unset($query[$key]['Script_ID']);
			unset($query[$key]['Analyst_ID']);
			unset($query[$key]['Analyst_Login']);
			unset($query[$key]['Analyst_DBA_ID']);
			unset($query[$key]['Analyst_DBA_Login']);
			unset($query[$key]['Script']);
		}

        $listget = '?';
        if (isset($_GET))
        {
            $mod_get = $_GET;
            unset($mod_get['sortby']);
            unset($mod_get['sorder']);
            foreach ($mod_get as $key => $value)
            {
                $value = htmlspecialchars_decode($value);
                $listget = $listget . $key . '=' . $value . '&';
            };
           
            if ( isset($_GET['sortby']) and isset($_GET['sorder']) )
            {
                $_SESSION['logged_in']['sortby'] = $_GET['sortby'];
                $_SESSION['logged_in']['sorder'] = $_GET['sorder'];
            };
 
        };

		#$fields = $this->db->query($this->db->last_query())->list_fields();
        $ev_glpi_asc = '';
        $ev_glpi_desc = '';
        if ( isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.glpi')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_glpi_asc = 'sort_red';
            }
            else
            {
                $ev_glpi_desc = 'sort_red';
            };
        };

		$fields[0]  = 'GLPI <a href="' . current_url() . $listget . 'sortby=ev.glpi&sorder=asc"><i  class="fa fa-sort-asc '  . $ev_glpi_asc  . '"  title=Asc  aria-hidden="true"></i></a>
                            <a href="' . current_url() . $listget . 'sortby=ev.glpi&sorder=desc"><i class="fa fa-sort-desc ' . $ev_glpi_desc . '" title=Desc aria-hidden="true"></i></a>';

        $en_name_asc = '';
        $en_name_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'en.name')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $en_name_asc = 'sort_red';
            }
            else
            {
                $en_name_desc = 'sort_red';
            };
        };

		$fields[1]  = 'Ambiente <a href="' . current_url() . $listget . 'sortby=en.name&sorder=asc"><i class="fa  fa-sort-asc ' . $en_name_asc . '"  title=Asc  aria-hidden="true"></i></a>
                                <a href="' . current_url() . $listget . 'sortby=en.name&sorder=desc"><i class="fa fa-sort-desc ' . $en_name_desc . '" title=Desc aria-hidden="true"></i></a>';

        $ac_name_asc = '';
        $ac_name_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ac.name')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ac_name_asc = 'sort_red';
            }
            else
            {
                $ac_name_desc = 'sort_red';
            };
        };

		$fields[2]  = 'Schema <a href="' . current_url() . $listget . 'sortby=ac.name&sorder=asc"><i class="fa  fa-sort-asc ' . $ac_name_asc . '"  title=Asc  aria-hidden="true"></i></a>
                              <a href="' . current_url() . $listget . 'sortby=ac.name&sorder=desc"><i class="fa fa-sort-desc ' . $ac_name_desc . '" title=Desc aria-hidden="true"></i></a>';

        $ev_sequence_asc = '';
        $ev_sequence_desc = '';
        if (isset($_GET['sortby']) and $_GET['sortby'] == 'ev.sequence_id')
        {  
            if ($_GET['sorder'] == 'asc')
            {
                $ev_sequence_asc = 'sort_red';
            }
            else
            {
                $ev_sequence_desc = 'sort_red';
            };
        };

		$fields[3]  = 'Sequencia <a href="' . current_url() . $listget . 'sortby=ev.sequence_id&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_sequence_asc .'"  title=Asc  aria-hidden="true"></i></a>
                                 <a href="' . current_url() . $listget . 'sortby=ev.sequence_id&sorder=desc"><i class="fa fa-sort-desc '. $ev_sequence_desc . '" title=Desc aria-hidden="true"></i></a>';

        $sc_version_asc = '';
        $sc_version_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'sc.version')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $sc_version_asc = 'sort_red';
            }
            else
            {
                $sc_version_desc = 'sort_red';
            };
        };

		$fields[4]  = 'Versão <a href="' . current_url() . $listget . 'sortby=sc.version&sorder=asc"><i class="fa  fa-sort-asc ' . $sc_version_asc . '"  title=asc  aria-hidden="true"></i></a>
                              <a href="' . current_url() . $listget . 'sortby=sc.version&sorder=desc"><i class="fa fa-sort-desc ' . $sc_version_desc . '" title=Desc aria-hidden="true"></i></a>';

        $ev_description_asc = '';
        $ev_description_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.description')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_description_asc = 'sort_red';
            }
            else
            {
                $ev_description_desc = 'sort_red';
            };
        };

		$fields[5]  = 'Descrição <a href="' . current_url() . $listget . 'sortby=ev.description&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_description_asc . '"  title=Asc  aria-hidden="true"></i></a>
                                 <a href="' . current_url() . $listget . 'sortby=ev.description&sorder=desc"><i class="fa fa-sort-desc ' . $ev_description_desc . '" title=Desc aria-hidden="true"></i></a>';

        $ev_dt_create_asc = '';
        $ev_dt_create_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.dt_create')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_dt_create_asc = 'sort_red';
            }
            else
            {
                $ev_dt_create_desc = 'sort_red';
            };
        };

		$fields[6]  = 'Data <a href="' . current_url() . $listget . 'sortby=ev.dt_create&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_dt_create_asc . '"  title=Asc  aria-hidden="true"></i></a>
                            <a href="' . current_url() . $listget . 'sortby=ev.dt_create&sorder=desc"><i class="fa fa-sort-desc ' . $ev_dt_create_desc . '"  title=Des  aria-hidden="true"></i></a>';

        $an_login_asc = '';
        $an_login_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'an.login')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $an_login_asc = 'sort_red';
            }
            else
            {
                $an_login_desc = 'sort_red';
            };
        };

		$fields[7]  = 'Analista <a href="' . current_url() . $listget . 'sortby=an.login&sorder=asc"><i class="fa  fa-sort-asc ' . $an_login_asc . '"  title=Asc  aria-hidden="true"></i></a>
                                <a href="' . current_url() . $listget . 'sortby=an.login&sorder=desc"><i class="fa fa-sort-desc ' . $an_login_desc . '"  title=Desc  aria-hidden="true"></i></a>';

        $and_login_asc = '';
        $and_login_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'anD.login')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $and_login_asc = 'sort_red';
            }
            else
            {
                $and_login_desc = 'sort_red';
            };
        };

		$fields[8]  = 'Dba/Ad <a href="' . current_url() . $listget . 'sortby=anD.login&sorder=asc"><i class="fa  fa-sort-asc ' . $and_login_asc . '"  title=Asc  aria-hidden="true"></i></a>
                              <a href="' . current_url() . $listget . 'sortby=anD.login&sorder=desc"><i class="fa fa-sort-desc ' . $and_login_desc . '"  title=Desc  aria-hidden="true"></i></a>';

        $ev_event_asc = '';
        $ev_event_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.event')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_event_asc = 'sort_red';
            }
            else
            {
                $ev_event_desc = 'sort_red';
            };
        };

		$fields[9]  = 'Evento <a href="' . current_url() . $listget . 'sortby=ev.event&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_event_asc . '"  title=Asc  aria-hidden="true"></i></a>
                              <a href="' . current_url() . $listget . 'sortby=ev.event&sorder=desc"><i class="fa fa-sort-desc ' . $ev_event_desc . '" title=Desc  aria-hidden="true"></i></a>';

        $ev_dt_evolution_asc = '';
        $ev_dt_evolution_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.dt_evolution')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_dt_evolution_asc = 'sort_red';
            }
            else
            {
                $ev_dt_evolution_desc = 'sort_red';
            };
        };

		$fields[10] = 'Data Evol <a href="' . current_url() . $listget . 'sortby=ev.dt_evolution&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_dt_evolution_asc . '"  title=Asc  aria-hidden="true"></i></a>
                                 <a href="' . current_url() . $listget . 'sortby=ev.dt_evolution&sorder=desc"><i class="fa fa-sort-desc ' . $ev_dt_evolution_desc . '"  title=Desc  aria-hidden="true"></i></a>';

        $ev_evolution_glpi_asc = '';
        $ev_evolution_glpi_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'ev.evolution_glpi')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $ev_evolution_glpi_asc = 'sort_red';
            }
            else
            {
                $ev_evolution_glpi_desc = 'sort_red';
            };
        };

		$fields[11] = 'Glpi Evol <a href="' . current_url() . $listget . 'sortby=ev.evolution_glpi&sorder=asc"><i class="fa  fa-sort-asc ' . $ev_evolution_glpi_asc . '"  title=Asc  aria-hidden="true"></i></a>
                                 <a href="' . current_url() . $listget . 'sortby=ev.evolution_glpi&sorder=desc"><i class="fa fa-sort-desc ' . $ev_evolution_glpi_desc . '"  title=Desc  aria-hidden="true"></i></a>';

        $env_name_asc = '';
        $env_name_desc = '';
        if (isset($_GET['sortby']) and  $_GET['sortby'] == 'enV.name')
        {   
            if ($_GET['sorder'] == 'asc')
            {
                $env_name_asc = 'sort_red';
            }
            else
            {
                $env_name_desc = 'sort_red';
            };
        };

		$fields[12] = 'Ambiente Evol <a href="' . current_url() . $listget . 'sortby=enV.name&sorder=asc"><i class="fa  fa-sort-asc ' . $env_name_asc . '" title=Asc  aria-hidden="true"></i></a>
                                	 <a href="' . current_url() . $listget . 'sortby=enV.name&sorder=desc"><i class="fa fa-sort-desc ' . $env_name_desc . '"  title=Desc  aria-hidden="true"></i></a>';

		# Removendo os campos do array da lista da tabela
		unset($fields[13]); # Evolution_ID
		unset($fields[14]); # Environment_ID
		unset($fields[15]); # Schema_ID
		unset($fields[16]); # Script_ID
		unset($fields[17]); # Script Name
		unset($fields[18]); # Analyst_ID
		unset($fields[19]); # Analyst_DBA_ID
		unset($fields[20]); # Ev_Environment_ID
		unset($fields[21]); # Ev_Environment_ID
		unset($fields[22]); # Ev_Environment_ID
		#$fields[] = 'Evoluir';
		#$fields[] = 'Editar';
		#$fields[] = 'Remover';
		$fields[] = array('data' => '','class' => 'sorttable_nosort');
		$fields[] = array('data' => '','class' => 'sorttable_nosort');
		$fields[] = array('data' => '','class' => 'sorttable_nosort');

		$fields = array_values($fields);
		$query = array_values($query);
		
		$this->CI->table->set_heading($fields);
		
		$template = array('table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="sortable">');
		$this->CI->table->set_template($template);
	
       return $evolution_table = $this->CI->table->generate( $query );
	
   } 
}

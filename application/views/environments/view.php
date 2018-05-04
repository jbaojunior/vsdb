<?php
    echo '<script src="'. base_url('js/search_evol.js') . '"></script>';
    echo validation_errors();

    include 'lateral_bar.php';

    echo '<div class=title_form>';
    echo '<h1> Ambiente - ' . $environment . '</h1>';
    echo '</div>';


    echo '<div class="search" id="search_div">' ;
    echo '<div class="search" id="search_box">' ;
	$attributes = array('class' => 'search', 'id' => 'search', 'method' => 'GET');
	echo form_open('environments/view/'.$id, $attributes);

    if ( $active_search === 0  )
    {
        $search_term['search0'] = null; 
        $search_term['comparation0'] = null;
        $search_term['field0'] = null;

    	$fields = array( 'ev.glpi' => 'GLPI', 'ac.name' => 'Schema', 'ev.sequence_id' => 'Sequencia', 'sc.script' => 'Script', 'sc.version' => 'Versão', 'an.login' => 'Analista', 'anD.name' => 'Dba/Ad',
                         'ev.dt_create' => 'Data', 'ev.dt_evolution' => 'Data Evol', 'ev.event' => 'Evento', 'enV.name' => 'Ambiente Evol');

	    echo form_dropdown('search0',$fields,$search_term['search0'],'id=search0');
	
    	$comparation = array('=' => '=', 'like' => 'like', '<>' => 'is not', 'not like' => 'is not like');
	    echo form_dropdown('comparation0',$comparation,$search_term['comparation0'],'id=comparation0');
	
    	$field_data = array('name' => 'field0','id'=> 'field0','maxlength' => '50','size' => '40','style' => 'width:20%');
        echo form_input($field_data,$search_term['field0']);
    
        $more_search=array('name' => 'add_button', 'id' => 'add_button', 'type' => 'button', 'content' => '+');
        $js_add = 'onClick="addRow(\'search\')"';
        echo form_button('more_button','+',$js_add);

        $more_search=array('name' => 'remove_button', 'id' => 'remove_button', 'type' => 'button', 'content' => '-');
        $js_remove = 'onClick="removeRow(\'search\')"';
        echo form_button('remove_button','-',$js_remove);

        echo '<br id="break0">';
    }
    else
    {
        $size_search = count($search_term);

        foreach ($search_term as $var => $term)
        {
            $count=substr($var,-1);
        
            $attribute = 'id=' . $var;

            if (substr($var,0,6) === 'search')
            {
    	        $fields = array( 'ev.glpi' => 'GLPI', 'ac.name' => 'Schema', 'ev.sequence_id' => 'Sequencia', 'sc.script' => 'Script', 'sc.version' => 'Versão', 'an.login' => 'Analista', 'anD.name' => 'Dba/Ad',
                                 'ev.dt_create' => 'Data', 'ev.dt_evolution' => 'Data Evol', 'ev.event' => 'Evento', 'enV.name' => 'Ambiente Evol');
                echo form_dropdown('search' . $count ,$fields,$search_term[$var],$attribute);
            };

            if (substr($var,0,11) === 'comparation')
            {
                $comparation = array('=' => '=', 'like' => 'like', '<>' => 'is not', 'not like' => 'is not like');
                echo form_dropdown('comparation' . $count,$comparation,$search_term[$var],$attribute);
            };

            if (substr($var,0,5) === 'field')
            {
                $field_data = array('name' => 'field' . $count ,'id'=> 'field' . $count, 'maxlength' => '100','size' => '50','style' => 'width:20%');
                echo form_input($field_data,$search_term[$var]);
                
                if ( $count > 0 )
                {
                    echo '<br id="break' . $count . '">';
                };
            };

            if (substr($var,0,5) === 'andOr')
            {
                $andOr = array('and' => 'and', 'or' => 'or');

                if ( $count > 0 )
                {
                    $attribute = 'id=' . $var . ' class="search_space"';
                };

                echo form_dropdown('andOr' . $count,$andOr,$search_term[$var],$attribute);
            };

            if ($count == 0 and substr($var,0,5) === 'field' )
            {
                $more_search=array('name' => 'add_button', 'id' => 'add_button', 'type' => 'button', 'content' => '+');
                $js_add = 'onClick="addRow(\'search\')"';
                echo form_button('more_button','+',$js_add);

                $more_search=array('name' => 'remove_button', 'id' => 'remove_button', 'type' => 'button', 'content' => '-');
                $js_remove = 'onClick="removeRow(\'search\')"';
                echo form_button('remove_button','-',$js_remove);
                echo '<br id="break' . $count . '">';
             };                

        };

    };


    echo '</div>' ; // search_box

	echo '<div  class="search" id="search_buttons">';
    echo form_submit('submit_search', 'Search Item');
    echo form_submit('reset_search', 'Reset Search');
    echo '</div>' ;

?>

     <div id="main" class="main" style="overflow-x:auto;">
    
        <?php 
            echo $environments_table;
             echo br(1);
             echo $pager;
         ?>

    </div>

</div>  <!-- search _div -->

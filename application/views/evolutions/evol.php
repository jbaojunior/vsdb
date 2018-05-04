<?php echo validation_errors(); ?>
<?php
    include 'lateral_bar.php';

    echo '<div class="title_form title_form_evol" >';
    echo '<h1>Evolução</h1>';
    echo '<h1>Sequence '. $sequence_id . '</h1>';
    echo '</div>';
    
    echo '<div class="script_field script_field_sequence">';
    
    $attributes = array('class' => 'form_script');
    echo form_open('evolutions/evol/'.$id,$attributes);
 
    $form_glpi = array ( 'name' => 'glpi', 'maxlength' => '10','style' => 'width: 12em');
    echo '<b>Glpi</b>' . br(1) ;
    echo form_input($form_glpi);
    echo br(2);
    
    echo '<b>Envinroment</b>' . br(1) ;
    echo form_dropdown('environment',$form_environment,'','style="width: 12em"');
    echo br(2);
    
    echo '<b>Schema</b>' . br(1) ;
    echo form_dropdown('schema',$form_schema,'','style="width: 12em" disabled');
    echo br(2);
    
    echo '<b>Analyst</b>' . br(1) ;
    echo form_dropdown('analyst',$form_analyst,$analyst_default['Analyst_ID'],'style="width: 12em"');
    echo br(2);

    echo '<b>DBA/AD</b>' . br(1) ;
    echo form_dropdown('analyst_DBA',$form_analyst_DBA,$id_user,'style="width: 12em"');
    echo br(3);
   
#    $form_sname = array ( 'name' => 'script_name', 'maxlength' => '30', 'value' => $script_name,'readonly' => 0);
#    echo '<b>Script Name</b>' . br(1);
#    echo form_input($form_sname);
#    echo br(2);
    
    $form_new_evolution = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_evolution','Evolve',$form_new_evolution);

    if (!is_null($msg))
    {
        echo '<div class=mensagem>';
        echo '<p>' . $msg . '</p>';
        echo '</div>';
    };

    echo '</div>';

    echo '<div class="script">';
   
    echo '<div class="desc_script">';
    $form_description = array ( 'name' => 'description', 'maxlength' => '90', 'size' => '45', 'value' => $description, 'readonly' => 0 );
    echo '<b>Description</b>';
    
    echo br(1) . form_input($form_description);
    echo '</div>';

    $form_observation = array ( 'name' => 'observation', 'maxlength' => '150', 'size' => '150', 'value' => $observation );
    echo br(4) . '<b>Observações</b>'. br(1);
    echo form_input($form_observation);

    $form_script = array ( 'name' => 'script', 'rows'=> '47', 'cols' => '190', 'required', 'style' => 'overflow:scroll; resize:none', 'value' => $script, 'readonly' => 0);
    echo br(2) . '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';
/*
    echo '<div class=title_form_evol>';
    echo '<h1>Evolução</h1>';
    echo '<h1>Sequence '. $sequence_id . '</h1>';
#    echo '<h1>Evolução Sequence '. $sequence_id . '</h1>';
    echo '</div>';
*/
?>                

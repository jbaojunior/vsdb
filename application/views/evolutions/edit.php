<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';
    
    echo '<div class=title_form>';
    echo '<h1>Edição</h1>';
    echo '<h1>Sequencia '. $sequence_id . '</h1>';
    echo '</div>';

    echo '<div class="script_field script_field_sequence">';

    $attributes = array('class' => 'form_script');
    echo form_open('evolutions/edit/'.$id,$attributes);
 
#    echo br(1). '++++++++++' . br(1);
#    print_r($_POST);
#    echo br(1). '++++++++++' . br(2);

    $form_glpi = array ( 'name' => 'glpi', 'maxlength' => '10', 'style' => 'width: 12em');
    echo '<b>Glpi</b>' . br(1) ;
    echo form_input($form_glpi);
    echo br(2);
    
    echo '<b>Ambiente</b>' . br(1) ;
    echo form_dropdown('environment',$form_environment,'','disabled style="width: 12em"');
    echo br(2);
    
    echo '<b>Schema</b>' . br(1) ;
    echo form_dropdown('schema',$form_schema,'', 'disabled style="width: 12em"');
    echo br(2);
    
    echo '<b>Analista</b>' . br(1) ;
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
    echo form_submit('submit_evolution','Edit',$form_new_evolution);

    echo '</div>';

    echo '<div class="script">';

    echo '<div class="desc_script">';
    $form_description = array ( 'name' => 'description', 'maxlength' => '90', 'size' => '45', 'value' => $description );
    echo '<b>Descrição</b>'. br(1);
    echo form_input($form_description); 
    echo '</div>';
   
    $form_observation = array ( 'name' => 'observation', 'maxlength' => '150', 'size' => '150', 'value' => $observation );
    echo br(4) . '<b>Observações</b>'. br(1);
    echo form_input($form_observation); 
   
    $form_script = array ( 'name' => 'script', 'rows'=> '43', 'cols' => '190', 'required', 'style' => 'overflow:scroll; resize:none', 'value' => $script);
    echo br(2) . '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';

?>                

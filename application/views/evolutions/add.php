<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';
    
    echo '<div class=title_form>';
    echo '<h1>Novo Script</h1>';
    echo '</div>';

    echo '<div class="script_field">';
    
    $attributes = array('class' => 'form_script');
    echo form_open('evolutions/add',$attributes);
   
    if (empty($_POST))
    { 
        $glpi = '';
        $description = '';
        $script = '';
    } 
    else
    {
//        print_r($_POST);
        $glpi = $_POST['glpi'];
        $description = $_POST['description'];
        $script = $_POST['script'];
    };        

    $form_glpi = array ( 'name' => 'glpi', 'maxlength' => '10', 'value' => $glpi, 'style' => 'width: 12em');
    echo '<b>Glpi</b>' . br(1) ;
    echo form_input($form_glpi);
    echo br(2);
    
    echo '<b>Envinroment</b>' . br(1) ;
    echo form_dropdown('environment',$form_environment,'','style="width: 12em"');
    echo br(2);
    
    echo '<b>Schema</b>' . br(1) ;
    echo form_dropdown('schema',$form_schema,'','style="width: 12em"');
    echo br(2);
    
    echo '<b>Analyst</b>' . br(1) ;
    echo form_dropdown('analyst',$form_analyst,'','style="width: 12em"');
    echo br(2);

    echo '<b>DBA/AD</b>' . br(1) ;
    echo form_dropdown('analyst_DBA',$form_analyst_DBA,$id_user,'style="width: 12em"');
    echo br(3);
/*   
    $form_sname = array ( 'name' => 'script_name', 'maxlength' => '30', 'value' => $script_name);
    echo '<b>Script Name</b>' . br(1);
    echo form_input($form_sname);
    echo br(2);
*/
    $form_new_evolution = array ('style' => 'width: 12em; height: 4em ');
    echo form_submit('submit_evolution','Add',$form_new_evolution);
    echo '</div>';
    
    echo '<div class="script">';

    echo '<div class="desc_script">';
    $form_description = array ( 'name' => 'description', 'maxlength' => '90', 'size'  => '45', 'value' => $description);
    echo '<b>Description </b>'.br(1);
    echo form_input($form_description);
    echo '</div>';
    
   

    $form_script = array ( 'name' => 'script', 'rows'=> '47', 'cols' => '190', 'required', 'style' => 'overflow:scroll; resize:none;', 'value' => $script);
    echo br(4) . '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';

?>                

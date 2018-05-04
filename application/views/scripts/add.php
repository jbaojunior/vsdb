<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';
    
    echo '<div class="new_evolution">';
    
    $attributes = array('class' => 'form_add');
    echo form_open('evolutions/add',$attributes);
    
    $form_glpi = array ( 'name' => 'glpi', 'maxlength' => '50');
    echo '<b>Glpi</b>' . br(1) ;
    echo form_input($form_glpi);

    echo br(2);

    echo '<b>Envinroment</b>' . br(1) ;
    echo form_dropdown('environment',$form_environment);

    echo br(2);

    echo '<b>Schema</b>' . br(1) ;
    echo form_dropdown('schema',$form_schema);

    echo br(2);

    echo '<b>Analyst</b>' . br(1) ;
    echo form_dropdown('analyst',$form_analyst);

    echo br(2);

    $form_sname = array ( 'name' => 'script_name', 'maxlength' => '30');
    echo '<b>Script Name</b>' . br(1) ;
    echo form_input($form_sname);

    echo br(2);

    $form_description = array ( 'name' => 'description', 'maxlength' => '30');
    echo '<b>Script Description</b>' . br(1) ;
    echo form_input($form_description);

    echo br(2);

    $form_script = array ( 'name' => 'script', 'rows'=> '25', 'cols' => '80', 'required');
    echo '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';

?>                

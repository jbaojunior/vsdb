<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';
    
    echo '<div class=title_form>';
    echo '<h1> Novo Ambiente</h1>';
    echo '</div>';

    echo '<div class="script_field">';
    
    $attributes = array('class' => 'form_script');
    echo form_open('environments/add',$attributes);
   
    $form_schema = array ( 'name' => 'environment', 'maxlength' => '30', 'style' => 'width: 12em');
    echo '<b>Ambiente</b>' . br(1) ;
    echo form_input($form_schema);
    echo br(2);
    
    $form_new = array ('style' => 'width: 12em; height: 4em ');
    echo form_submit('submit','Add',$form_new);
    echo '</div>';
    

?>   

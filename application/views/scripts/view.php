<?php echo validation_errors(); ?>
<?php
    include 'lateral_bar.php';

    echo '<div class=title_form>';
    echo '<h1>Scripts</h1>';
    echo '</div>';
   
    echo '<div class="script_field sequence">';

    $attributes = array('class' => 'form_script');

    echo '<b> Ambientes </b>';
    foreach ($version['Linha'] as $data => $var)
    {
        echo '<ul>' . $var . '</ul>';
    };

    echo '</div>';
    
    echo '<div class="script">';

    if ( $version['Version'] != $last_version )
    {
       $last_version = '<span class=aviso_vermelho>' . $last_version . '</span>';
    };

    echo '<div class="desc_script">';
    echo '<h2><b> Versão: '. $version['Version'] . nbs(5) . ' Schema: ' . $version['Schema'] . nbs(20) . 'Sequence: ' . $version['Sequence'] . nbs(5) . 'Ultima Versão do Script: ' . $last_version . '</b></h2>';

    $form_description = array ( 'name' => 'description', 'maxlength' => '90', 'size' => '45', 'value' => $version['Description'], 'readonly' => 0 );
    echo '<b>Description</b>';
    echo br(1) . form_input($form_description) . br(1);
    echo '</div>';

    $form_observation = array ( 'name' => 'observation', 'maxlength' => '150', 'size' => '150', 'value' => $version['observation'] );
    echo br(7) . '<b>Observações</b>'. br(1);
    echo form_input($form_observation);

    $form_script = array ( 'name' => 'script', 'rows'=> '43', 'cols' => '190', 'required', 'style' => 'overflow:scroll; resize:none', 'value' => $version['script'], 'readonly' => 0);
    echo br(2) . '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';

?>                

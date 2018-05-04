<?php echo validation_errors(); ?>
<?php
    include 'lateral_bar.php';
    
    echo '<div class="title_form title_form_evol">';
    echo '<h1>Evoluções</h1>';
    echo '<h1>Sequence '. $last[0]['Sequence'] . '</h1>';
#    echo '<h1>Sequence ' . $last[0]['Sequence'] . ' - Evolução</h1>';
    echo '</div>';

    echo '<div class="script_field script_field_sequence">';

    $attributes = array('class' => 'form_script');

    foreach ($version as $data => $var)
    {
        echo '<li> <b>' . anchor('scripts/view/' . $var['Script'],'Versão ' . $data) . '</b>';
        
        foreach ($var['Linha'] as $field => $item)
        {
            echo '<ul>' . $item . '</ul>';
        };            

        echo '</li>'.br(2);
    };

    echo '</div>';
    
    echo '<div class="script">';
   
    echo '<div class="desc_script">';
    echo '<h2><b> Ultima versão do script: '. $last[0]['Version'] . '</b></h2>';
    echo '</div>';

    $form_script = array ( 'name' => 'script', 'rows'=> '47', 'cols' => '190', 'required', 'style' => 'overflow:scroll; resize:none', 'value' => $last[0]['Script'], 'readonly' => 0);
    echo br(4) . '<b>Script</b>' . br(1) ;
    echo form_textarea($form_script);
    echo '</div>';

?>                

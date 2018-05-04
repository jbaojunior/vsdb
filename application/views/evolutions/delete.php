<?php

    include 'lateral_bar.php';

    echo '<div class="deleted">';
    echo '<p><h2>A linha com o GLPI ' . $glpi . ' será removida.</h2></p>';
    echo '<p><h1>Deseja continuar?</h1></p>';
    echo '</div>';
    echo br(5); 


    echo '<div class="deleted_buttons">';
    echo form_open('evolutions/delete/'.$id);
    $form_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_delete','Ok, irei apagar',$form_delete);

    $form_cancel_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_cancel','Cancelar',$form_cancel_delete);
    echo '</div>';

?>

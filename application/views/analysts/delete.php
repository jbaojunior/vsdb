<?php

    include 'lateral_bar.php';

    echo '<div class="deleted">';
    echo '<p><h2> O analista <span class=aviso_vermelho>' . $name . '</span> será removido.</h2></p>';
    echo '<p><h1>Deseja continuar?</h1></p>';
    echo '</div>';
    echo br(5); 


    echo '<div class="deleted_buttons">';
    echo form_open('analysts/delete/'.$id);
    $form_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_delete','Ok, continuar',$form_delete);

    $form_cancel_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_cancel','Cancelar',$form_cancel_delete);
    echo '</div>';

?>

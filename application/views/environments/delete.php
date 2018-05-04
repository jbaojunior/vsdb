<?php

    include 'lateral_bar.php';

    echo '<div class="deleted">';
    echo '<p><h2><span class=aviso_vermelho>'. $count . '</span> registros serão removidos se o ambiente <span class=aviso_vermelho>' . $name . '</span> for removido.</h2></p>';
    echo '<p><h1>Deseja continuar?</h1></p>';
    echo '</div>';
    echo br(5); 


    echo '<div class="deleted_buttons">';
    echo form_open('environments/delete/'.$id);
    $form_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_delete','Ok, continuar',$form_delete);

    $form_cancel_delete = array ('style' => 'width: 12em; height: 4em;');
    echo form_submit('submit_cancel','Cancelar',$form_cancel_delete);
    echo '</div>';

?>

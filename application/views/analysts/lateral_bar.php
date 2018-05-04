<?php
    if ( $_SESSION['logged_in']['team'] === 'A' )
    {
        echo '<div class="barra_lateral nohover">';
        echo '<div class="menu_lateral">';
    } 
    else 
    {
        echo '<div class="barra_lateral">';
        echo '<div class="menu_lateral">';
        echo anchor('analysts/add', 'Novo Analista', 'tittle=Novo Analista');
        echo br(2);
        echo anchor('analysts/index', 'Lista de Analistas', 'tittle=Lista de Analistas');
        echo '</div>';
        echo '<div class="button_lateral">';
        echo '<p <i class="fa fa-caret-right fa-3x" title="Menu"></i></p>';
    };
    echo '</div>';
    echo '</div>';
?>



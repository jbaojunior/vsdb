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
        echo anchor('environments/add', 'Novo Ambiente', 'tittle=Novo Ambiente');
        echo br(2);
        echo anchor('environments/index', 'Lista de Ambientes', 'tittle=Lista de Ambientes');
        echo '</div>';
        echo '<div class="button_lateral">';
        echo '<p <i class="fa fa-caret-right fa-3x" title="Menu"></i></p>';
    };
    echo '</div>';
    echo '</div>';
?>


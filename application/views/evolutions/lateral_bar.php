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
        echo anchor('evolutions/add', 'Novo Script', 'title=Novo Script');
        echo br(2);
        echo anchor('evolutions/index', 'Lista de Evoluções', 'title=Lista de Evoluções');
        echo br(5);
        echo anchor('evolutions/get_scripts_desenvolvimento', 'Scripts de' . br(1) . ' Desenvolvimento' , 'title="Script com todas as alterações que estão aplicadas no ambiente de Desenvolvimento"');
        echo br(2);
        echo anchor('evolutions/get_scripts_homologacao', 'Scripts de' . br(1) . ' Homologação ' , 'title="Script com todas as alterações que estão aplicadas no ambiente do ambiente de Homologação"');
        echo br(2);
        echo anchor('evolutions/get_scripts_qualidade', 'Scripts de' . br(1) . ' Qualidade' , 'title="Script com todas as alterações que estão aplicadas no ambiente do ambiente de Qualidade"');
        echo '</div>';
        echo '<div class="button_lateral">';
        echo '<p <i class="fa fa-caret-right fa-3x" title="Menu"></i></p>';
    };
    echo '</div>';
    echo '</div>';
?>

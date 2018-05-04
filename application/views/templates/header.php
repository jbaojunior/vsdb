<html>
        <head>
                <title>VSDB</title>
                <pre>
                    <link rel="icon" href="<?php echo base_url(); ?>img/favicon.ico" type="image/gif">
                </pre>
                <?php #echo '<script src="'. base_url('js/sorttable.js') . '"></script>' ?>
                <?php echo link_tag('css/main.css'); ?>
                <?php echo link_tag('css/font-awesome/css/font-awesome.min.css'); ?>

        </head>

        <body class="body">
            <header>
                <div class="divheader">
                    <div class="menu">
                    <nav>
                        <?php 
                            echo '<span><a href="' . site_url('evolutions') . '">Evoluções</a></span>';
                            #echo '<span><a href="' . site_url('scripts') . '">Scripts</a></span>';
                            echo '<span><a href="' . site_url('environments') . '">Ambientes</a></span>';
                            echo '<span><a href="' . site_url('accounts') . '">Schemas</a></span>';
                            echo '<span><a href="' . site_url('analysts') . '">Analistas</a></span>';
                            echo '</nav>';
                            echo '</div>';
                            echo '<div class="logado">';
                            echo '<span class="logado_texto"> Logado como: ' . $login . '</span>';
                            echo '<span><a href="'. site_url('analysts/conta/') . $id_user . '"><i class="fa fa-cog fa-3x" title="Configuração da Conta"></i></a></span>';
                            echo '<span><a class="logout" href="'.site_url('logout').'"><i class="fa fa-sign-out fa-3x" title="Logout"></i></a></span>';
                            echo '</div>';
                        ?>
<!--                    </nav> -->

                </div>
             </header>

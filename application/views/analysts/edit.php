<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';

    echo '<div class=title_form>';
    echo '<h1> Editar - '. $Login .' </h1>';
    echo '</div>';

    echo '<div class="script_field">';


    if (empty($_POST))
    {
        $analyst = '';
        $login = '';
        $number_rows = '20';
        $team = '';
    }
    else
    {
        $Analyst = $_POST['analyst'];
        $Login = $_POST['login'];
        $number_rows = $_POST['number_rows'];
        $team = $_POST['team'];
    };

    $attributes = array('class' => 'form_script');
    echo form_open('analysts/Edit/' . $ID , $attributes);
   
    $form_schema = array ( 'name' => 'analyst', 'maxlength' => '50', 'value' => $Analyst, 'style' => 'width: 35em');
    echo '<b>Nome: </b>' . nbs(3);
    echo form_input($form_schema);
    echo br(2);
    
    $form_login = array ( 'name' => 'login', 'maxlength' => '15', 'value' => $Login, 'style' => 'width: 12em');
    echo '<b>Login: </b>'.nbs(3);
    echo form_input($form_login);
    echo br(2);

    $teams = array(
        'D'  => 'Dba',
        'A'  => 'Analista'
    );

    $form_team = array ( 'name' => 'team', 'maxlength' => '10', 'value' => $team, 'style' => 'width: 15em');
    echo '<b>Função: </b>' ;
    echo form_dropdown('team',$teams,$team,'style="width: 12em"');
    echo br(2);

    $form_nrows = array ( 'name' => 'number_rows', 'maxlength' => '2', 'value' => $_SESSION['logged_in']['nrows'] ,'style' => 'width: 5em');
    echo '<b>Exibir: <b>' . nbs(2);
    echo form_input($form_nrows);
    echo br(2);

    $form_conf = array ('style' => 'width: 12em; height: 4em ');
    echo form_submit('submit_conf','Salvar configuração',$form_conf);
    echo '</div>';

    echo '<div class="edit_password">';

    $form_password = array ( 'name' => 'password', 'maxlength' => '30', 'style' => 'width: 20em');
    echo nbs(17) . '<b>Nova Senha: <b>';
    echo form_password($form_password);
    echo br(2);

	$form_repassword = array ( 'name' => 'passconf', 'maxlength' => '30', 'style' => 'width: 20em');
    echo '<b>Confirme a Nova Senha: <b>';
    echo form_password($form_repassword);
    echo br(2);

    $form_new = array ('style' => 'width: 12em; height: 4em; float: right;');
    echo form_submit('submit_password','Alterar Senha',$form_new);
    echo '</div>';

    if (isset($msg))
    {
        echo '<div class=mensagem>';
        echo '<p>' . $msg . '</p>';
        echo '<div>';
    };

?>   

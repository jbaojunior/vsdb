<?php echo validation_errors(); ?>

<?php
    include 'lateral_bar.php';
    
    echo '<div class=title_form>';
    echo '<h1> Novo Analista</h1>';
    echo '</div>';

    echo '<div class="script_field">';

    if (empty($_POST))
    {
        $analyst = '';
        $login = '';
        $password = '';
        $passconf = '';
        $number_rows = '20';
        $team = '';
    }
    else
    {
        $analyst = $_POST['analyst'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $passconf = $_POST['passconf'];
        $number_rows = $_POST['number_rows'];
        $team = $_POST['team'];
    };

    $attributes = array('class' => 'form_script');
    echo form_open('analysts/add',$attributes);
  
	echo '<div class="nome">'; 
    $form_schema = array ( 'name' => 'analyst', 'maxlength' => '50', 'value' => $analyst, 'style' => 'width: 35em');
    echo '<b>Nome</b>' . br(1) ;
    echo form_input($form_schema);
	echo '</div>';

	echo '<div class="login">';
    $form_login = array ( 'name' => 'login', 'maxlength' => '15', 'value' => $login, 'style' => 'width: 12em');
    echo '<b>Login</b>' . br(1) ;
    echo form_input($form_login);
	echo '</div>';

    echo br(2);

	echo '<div class="tipo">';
	$teams = array(
        'A'  => 'Analista',
        'D'  => 'Dba'
	);

    echo '<b>Tipo</b>' . br(1) ;
    echo form_dropdown('team',$teams,$team,'style="width: 12em"');
	echo '</div>';

	echo '<div class=configuration>';
    $form_nrows = array ( 'name' => 'number_rows', 'maxlength' => '2', 'value' => $number_rows ,'style' => 'width: 5em');
    echo '<b>Exibir (numero de itens)<b>' .br(1);
    echo form_input($form_nrows);
	echo '</div>';

	echo br(2);

	echo '<div class="password">';
    $form_password = array ( 'name' => 'password', 'maxlength' => '30', 'value' => $password, 'style' => 'width: 20em');
    echo '<b>Senha: <b>' . br(1) ;
    echo form_password($form_password);

    echo br(2);

	$form_repassword = array ( 'name' => 'passconf', 'maxlength' => '30', 'value' => $passconf, 'style' => 'width: 20em');
    echo '<b>Confirme a Senha: <b>' . br(1) ;
    echo form_password($form_repassword);
	echo '</div>';

    echo br(2);

    $form_new = array ('style' => 'width: 12em; height: 4em ');
    echo form_submit('submit','Add',$form_new);
    echo '</div>';

    if (isset($msg))
    {
        echo '<div class=mensagem>';
        echo '<p>' . $msg . '</p>';
        echo '</div>';
    };


?>   

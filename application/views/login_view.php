<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <link href="<?php base_url()?>/css/main.css" rel="stylesheet" type="text/css">
   <link href="<?php base_url()?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <title>VSDB - Versionador de scripts de banco de dados</title>
 </head>
 <body style='background-color: white; color: #4CAF50'>
   <h1 class="center login_title" >VSDB - Versionador de banco de dados - 2 - <?php echo gethostname(); ?> </h1>
   <img src="<?php base_url()?>/img/dba.jpg" style="width:250px;height:240px;position: absolute;">
   <?php echo validation_errors();
         $attributes = array('class' => 'form_login center');
         echo form_open('verifylogin',$attributes); ?>
     <div class="login_username center">
     <label for="username">Login:</label>
     <input type="text" size="20" id="username" name="username"/>
     </div>
     <br/>
     <div class="login_password">
     <label for="password">Password:</label>
     <input type="password" size="20" id="password" name="password"/>
     </div>
     <br/>
     <input style='width: 12em; height:4em; margin-left:4em ' type="submit" value="Login"/>
   </form>
   </div>
 </body>
</html>

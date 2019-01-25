<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">

    <title>Login - Administração Site</title>

    <!-- Bootstrap core CSS -->
    <link href="./arquivos_login/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./arquivos_login/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./arquivos_login/signin.css" rel="stylesheet">


    <script src="./arquivos_login/ie-emulation-modes-warning.js.download"></script>


  </head>

  <body cz-shortcut-listen="true">

    <div class="container">

      <form class="form-signin" action='index.php' method='POST'>
		 
        <h2 class="form-signin-heading"><center>Administrar Site</center></h2>
        <label for="inputEmail" class="sr-only">Email:</label>
        <input type="email" id="inputEmail" class="form-control" name='email' placeholder="Endereço de Email" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Senha:</label>
        <input type="password" id="inputPassword" class="form-control" name='senha' placeholder="Senha" required="">
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
	  
	  <?php
      session_start();
      unset($_SESSION['email_usuario']);
	    if (isset($_POST['email']))
		{
			include("../conexao/conexao.php");
			$emailUsuario = $_POST['email'];
			$senhaUsuario = hash('sha256', $_POST['senha']);
			$usuarioExiste = 0;
			
			$sqlSelect = "SELECT * FROM usuario 
			WHERE email_usuario = '$emailUsuario' AND 
			senha_usuario = '$senhaUsuario'";
			
			$consultaSelect = Conexao::prepare($sqlSelect);
			$consultaSelect->execute();
			$usuarioExiste = $consultaSelect->rowCount();	
		
			//checando se usuario existe em banco
			if($usuarioExiste>0){
				//caso sim, iniciar session com o nome de email inserido
				$_SESSION['email_usuario'] = $emailUsuario;
				header("location: ../admin/");
			}else{
				//caso nao, exibir alerta com email/senha invalidos
				echo("
				<div class='alert alert-danger'>
				  <strong>Email ou senha inválidos.</strong>
				</div>
				");
			}
		}
	  ?>

    </div> <!-- /container -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./arquivos_login/ie10-viewport-bug-workaround.js.download"></script>
</body></html>

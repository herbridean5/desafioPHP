<?php
include("header.php");
include("../conexao/conexao.php");

if (isset($_POST["postEmail"]))
{
$emailUsuario = $_POST["postEmail"];
$senhaUsuario = hash('sha256', $_POST['postSenha']);


//concatenando todas as strings, sera usada para testes de caracteres especiais
$stringPteste = $emailUsuario.$senhaUsuario;

//testando para ver se nenhum campo possui caracteres especiais ( como por exemplo '; )
if(!(preg_match('/[!#$%^&*()?\'":;{}|<>]/',$stringPteste))){
	
$insertSql = "INSERT INTO usuario (email_usuario, senha_usuario) VALUES 
	('$emailUsuario','$senhaUsuario')";

try{ 
	$consultaInsert = Conexao::prepare($insertSql);
	$consultaInsert->execute();
	
	echo("
		<div class='row'>
		 <div class='col-lg-6'>
		 <div class='alert alert-success'>
		 <b>Dados inseridos com sucesso!!!</b><br>
		 <a href='usuario.php' 
		 class='btn btn-success' role='button'>
		 <span class='glyphicon 
		 glyphicon-arrow-left'></span>
		 Voltar</a>
		 </div>
		 </div>
		</div>
		");
}catch(PDOException $e){
	die("
		<div class='alert alert-danger'>
		<b>Falha na execução do SQL!<br>sqlException: $e</b>
		</div>
		");
}
}else{
	die("
		<div class='alert alert-danger'>
		<b>ERRO: algum campo possui caracteres especiais, nenhum dado foi salvo em banco</b>
		</div>
	");
}
} 
else
{
?>
<div class="row">
			<div class="col-lg-6">
				<h1>Cadastro de Usuários</h1>

				<form role="form" action="usuario_novo.php" method="POST" enctype="multipart/form-data">	
					<div class="form-group">
						<label>Email do Usuário</label>
						<input type='email' class="form-control" name='postEmail' placeholder="Email">
					</div>
					
					<div class="form-group">
						<label>Senha</label>
						<input type='password' class="form-control" name='postSenha' placeholder="Senha">
					</div>

					<button type="submit" class="btn btn-info">Salvar</button>
					<button type="reset" class="btn btn-danger">Limpar</button>

				</form>
			</div>
</div>
<?php
}
include("footer.php");
?>

<?php
include("header.php");
include("../conexao/conexao.php");

//if que faz a alterar em banco apos os dados serem recebidos por POST
if (isset($_POST["postEmail"]))
{
$emailUsuario = $_POST["postEmail"];
$senhaUsuario = hash('sha256', $_POST['postSenha']);
$idUsuario = $_POST["postId"];

//concatenando todas as strings, sera usada para testes de caracteres especiais
$stringPteste = $emailUsuario.$senhaUsuario.$idUsuario;

//testando para ver se nenhum campo possui caracteres especiais ( como por exemplo '; )
if(!(preg_match('/[!#$%^&*()?\'":;{}|<>]/',$stringPteste))){
	
$updateSql = "UPDATE usuario SET 
	email_usuario = '$emailUsuario', 
	senha_usuario ='$senhaUsuario' WHERE id_usuario = $idUsuario";
	
try{ 
$consultaUpdate = Conexao::prepare($updateSql);
$consultaUpdate->execute();
echo("
	<div class='row'>
		<div class='col-lg-6'>
		<div class='alert alert-success'>
		<b>Dados atualizados com sucesso!!!</b><br>
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
//else if para pegar dados do usuario que veio do usuario.php
else if (isset($_POST["postId"]))
{
$selectSql = "SELECT * FROM usuario 
WHERE id_usuario = ".$_POST["postId"].";";

try{
//preparando consulta Select
$consultaSelect = Conexao::prepare($selectSql);
$consultaSelect->execute();

$dadosUsuarios = $consultaSelect->fetchAll(PDO::FETCH_ASSOC);
foreach( $dadosUsuarios as $linhaConsulta) {
	$idUsuario = $linhaConsulta['id_usuario'];
	$emailUsuario = $linhaConsulta['email_usuario'];
} 
}catch(PDOException $e){
echo("
	<div class='alert alert-danger'>
	<b>Falha na execução do SQL</b>
	</div>
");
die($selectSql);
}
?>
	<div class="row">
				<div class="col-lg-6">
					<h1>Alterar Usuário</h1>
					<form role="form" action="usuario_alterar.php" method="POST">

						<div>
							<label>Email do Usuário</label>
							<input type='email' class="form-control" name='postEmail' placeholder="Email" value="<?php echo $emailUsuario ?>">
						</div>
						
						<div class="form-group">
							<label>Senha</label>
							<input type='password' class="form-control" name='postSenha' placeholder="Senha">
						</div>
						
						<input type="hidden" name="postId" value="<?php echo $idUsuario ?>">
						<button type="submit" class="btn btn-info">Salvar</button>
						<button type="reset" class="btn btn-danger">Limpar</button>
					</form>
				</div>
	</div>
<?php
}
include("footer.php");
?>

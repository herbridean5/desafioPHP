<?php
include("header.php");
include("../conexao/conexao.php");

if (isset($_POST['postId'])){
	//excluindo usuário do banco de dados
	$idUsuario = $_POST['postId'];
	$sqlDelete = "DELETE FROM usuario 
	WHERE id_usuario = $idUsuario";
	
	try{ 
		$consultaDelete = Conexao::prepare($sqlDelete);
		$consultaDelete->execute();
		echo("
		  <div class='row'>
			<div class='col-sm-12'>
			  <br>
			  <div 
			  class='alert alert-success'
			  role='alert'>
				<b>Registro excluído com sucesso!</b>
			  </div>
			</div>
		  </div>
		");
	}catch(PDOException $e){
		die("<div class='alert alert-alert'>
			<b>Não foi possível excluir! <br>sqlException: $e</b>
			</div>");
	}
}

if (isset($_POST['filtro'])){
	$sqlSelect = "
	SELECT 
		  id_usuario,
		  email_usuario,
		  senha_usuario 
		FROM
		  usuario  
	WHERE EMAIL_USUARIO 
	LIKE '%".$_POST['filtro']."%'";
} else{
	$sqlSelect = "
	SELECT 
		  id_usuario,
		  email_usuario,
		  senha_usuario 
		FROM
		  usuario ";
}

echo("
  <div class='row'>
  <div class='col-sm-12'>
	<center>
	<h1>Usuários</h1>
  </div>
  </div>
  
 <div class='row'>
	 <div class='col-sm-3'>
	 </div>
		 <div class='col-sm-6'>
		 <form action='usuario.php' 
			method='POST'>
			<div class='input-group'>
			<input type='text' name='filtro' class='form-control' placeholder='Busca por...'> <span class='input-group-btn'>
			<button type='submit' class='btn btn-info'><span class='glyphicon glyphicon-search'></span>Busca</button></span>
			</div>
		 </form>
	</div>
	<div class='col-sm-3'>
		 <a href='usuario_novo.php' class='btn btn-success' role='button'> <span class='glyphicon glyphicon-plus'></span>Novo</a>
	</div>
 </div>
 
 <div class='row'>
	 <div class='col-sm-12'>
		 <br><br>
		 <table class='table table-bordered table-striped table-hover'>
			 <thead>
				 <tr class='info'>
					 <th>ID</th>
					 <th>Email</th>
					 <th colspan='2'>Operação</th>
				 </tr>
			 </thead>
		 <tbody>
 "
 );

try{
	//preparando consulta Select
	$consultaSelect = Conexao::prepare($sqlSelect);
	$consultaSelect->execute();
	$dadosUsuarios = $consultaSelect->fetchAll(PDO::FETCH_ASSOC);
	
	//passando em cada usuario e criando sua linha na tabela
	foreach( $dadosUsuarios as $linhaConsulta) {
		$idUsuario = $linhaConsulta['id_usuario'];
		$emailUsuario = $linhaConsulta['email_usuario'];
		echo("
			<tr>
			  <td>$idUsuario</td>
			  <td>$emailUsuario</td>
			  <td align='center'>
				<form action='usuario_alterar.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idUsuario'>
						<button type='submit' class='btn btn-primary'>
						<span class='glyphicon 
							glyphicon-pencil'></span>
					</div>
				 </form> 
			  </td>
			  <td align='center'>
			   <form action='usuario.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idUsuario'>
						<button type='submit' class='btn btn-danger'>
						<span class='glyphicon 
							glyphicon-remove'></span>
					</div>
				 </form>   
			  </td>	  
			</tr>
		"); 
	}
}catch(PDOException $e){
	die("
	  <div class='row'>
		<div class='col-sm-12'>
		  <br>
		  <div 
		  class='alert alert-danger'
		  role='alert'>
			<b>Falha na execução da consulta!<br>sqlException: $e</b>
		  </div>
		</div>
	  </div>
	");
}
echo("</tbody></table></div></div>");

include("footer.php");
?>


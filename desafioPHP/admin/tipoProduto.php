<?php
include("header.php");
include("../conexao/conexao.php");

if (isset($_POST['postId'])){
	$idTipoproduto = $_POST['postId'];
	//criando consulta de delete
	$sqlDelete = "DELETE FROM tipoproduto 
	WHERE id_tipoProduto = $idTipoproduto";
	
	try{ 
		//executando consulta em banco e exibindo mensagem de sucesso
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
		//caso algum erro tenha ocorrido exibe mensagem de erro
		die("<div class='alert alert-alert'>
			<b>Não foi possível excluir! <br>sqlException: $e</b>
			</div>");
	}
}

if (isset($_POST['filtro'])){
	//criando consulta select caso o usuario tenha digitado um filtro
	$sqlSelect = "
	SELECT 
		  id_tipoProduto,
		  nome_tipoProduto,
		  imposto_tipoProduto 
		FROM
		  tipoproduto  
	WHERE nome_tipoProduto 
	LIKE '%".$_POST['filtro']."%'";
}else{
	//criando consulta select caso o usuario nao tenha digitado filtro
	$sqlSelect = "
	SELECT 
		  id_tipoProduto,
		  nome_tipoProduto,
		  imposto_tipoProduto 
		FROM
		  tipoproduto ";
}

echo("
  <div class='row'>
  <div class='col-sm-12'>
	<center>
	<h1>Tipos de Produto</h1>
  </div>
  </div>
  
 <div class='row'>
	 <div class='col-sm-3'>
	 </div>
		 <div class='col-sm-6'>
		 <form action='tipoProduto.php' 
			method='POST'>
			<div class='input-group'>
			<input type='text' name='filtro' class='form-control' placeholder='Busca por...'> <span class='input-group-btn'>
			<button type='submit' class='btn btn-info'><span class='glyphicon glyphicon-search'></span>Busca</button></span>
			</div>
		 </form>
	</div>
	<div class='col-sm-3'>
		 <a href='tipoProduto_novo.php' class='btn btn-success' role='button'> <span class='glyphicon glyphicon-plus'></span>Novo</a>
	</div>
 </div>
 
 <div class='row'>
	 <div class='col-sm-12'>
		 <br><br>
		 <table class='table table-bordered table-striped table-hover'>
			 <thead>
				 <tr class='info'>
					 <th>ID</th>
					 <th>Nome</th>
					 <th>Imposto</th>
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
	
	//passando em cada tipoproduto e criando sua linha na tabela
	foreach( $dadosUsuarios as $linhaConsulta) {
		$idTipoproduto = $linhaConsulta['id_tipoProduto'];
		$nomeTipoproduto = $linhaConsulta['nome_tipoProduto'];
		$impostoTipoproduto = $linhaConsulta['imposto_tipoProduto'];
		echo("
			<tr>
			  <td>$idTipoproduto</td>
			  <td>$nomeTipoproduto</td>
			  <td>$impostoTipoproduto</td>
			  <td align='center'>
				<form action='tipoProduto_alterar.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idTipoproduto'>
						<button type='submit' class='btn btn-primary'>
						<span class='glyphicon 
							glyphicon-pencil'></span>
					</div>
				 </form>   
			  </td>
			  <td align='center'>
				<form action='tipoProduto.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idTipoproduto'>
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
	//caso algum erro na consulta tenha ocorrido exibe mensagem de erro
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


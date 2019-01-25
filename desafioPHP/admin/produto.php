<?php
include("header.php");
include("../conexao/conexao.php");

if (isset($_POST['postId']))
{
	//excluindo produto do banco de dados
	$idProduto = $_POST['postId'];
	$sqlDelete = "DELETE FROM produto 
	WHERE id_produto = $idProduto";
	
	try{ 
		//executando SQL e exibindo mensagem em tela de sucesso
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
		//caso tenha ocorrido algum problema na hora da exclusão exibe mensagem de erro em tela
		die("<div class='alert alert-alert'>
			<b>Não foi possível excluir! <br>sqlException: $e</b>
			</div>");
	}
}

if (isset($_POST['filtro'])){
	//criando consulta SQL caso o usuario tenha digitado algum filtro
	$sqlSelect = "
	SELECT 
		id_produto,
		nome_produto,
		descri_produto,
		valor_produto,
		fk_tipoProduto,
		nome_tipoProduto,
		arquivo_produto,
		extensao_arquivo
	FROM
		produto as pr inner join tipoproduto as tp on (pr.fk_tipoProduto=id_tipoProduto)
	WHERE nome_produto 
	LIKE '%".$_POST['filtro']."%'";
} else{
	//criando consulta SQL caso o usuario nao tenha digitado filtro
	$sqlSelect = "
	SELECT 
		id_produto,
		nome_produto,
		descri_produto,
		valor_produto,
		fk_tipoProduto,
		nome_tipoProduto,
		arquivo_produto,
		extensao_arquivo
	FROM
		produto as pr inner join tipoproduto as tp on (pr.fk_tipoProduto=id_tipoProduto)";
}

echo("
  <div class='row'>
  <div class='col-sm-12'>
	<center>
	<h1>Produtos</h1>
  </div>
  </div>
  
 <div class='row'>
	 <div class='col-sm-3'>
	 </div>
		 <div class='col-sm-6'>
		 <form action='produto.php' 
			method='POST'>
			<div class='input-group'>
			<input type='text' name='filtro' class='form-control' placeholder='Busca por...'> <span class='input-group-btn'>
			<button type='submit' class='btn btn-info'><span class='glyphicon glyphicon-search'></span>Busca</button></span>
			</div>
		 </form>
	</div>
	<div class='col-sm-3'>
		 <a href='produto_novo.php' class='btn btn-success' role='button'> <span class='glyphicon glyphicon-plus'></span>Novo</a>
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
					 <th>Descrição</th>
					 <th>Valor</th>
					 <th>Tipo</th>
					 <th>Arquivo</th>
					 <th>Extensão</th>
					 <th colspan='2'>Operação</th>
				 </tr>
			 </thead>
		 <tbody>
 ");
try{
	//preparando consulta Select
	$consultaSelect = Conexao::prepare($sqlSelect);
	$consultaSelect->execute();
	$dadosUsuarios = $consultaSelect->fetchAll(PDO::FETCH_ASSOC);
	
	//passando em cada produto e criando sua linha na tabela
	foreach( $dadosUsuarios as $linhaConsulta) {
		$idProduto = $linhaConsulta['id_produto'];
		$nomeproduto = $linhaConsulta['nome_produto'];
		$descriProduto = $linhaConsulta['descri_produto'];
		$valorProduto = $linhaConsulta['valor_produto'];
		$fkTipo = $linhaConsulta['nome_tipoProduto'];
		$nomeArquivo = $linhaConsulta['arquivo_produto'];
		$extensaoArquivo = $linhaConsulta['extensao_arquivo'];
		
		echo("
			<tr>
			  <td>$idProduto</td>
			  <td>$nomeproduto</td>
			  <td>$descriProduto</td>
			  <td>$valorProduto</td>
			  <td>$fkTipo</td>
			  <td>$nomeArquivo</td>
			  <td>$extensaoArquivo</td>
			  <td align='center'>
				<form action='produto_alterar.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idProduto'>
						<button type='submit' class='btn btn-primary'>
						<span class='glyphicon 
							glyphicon-pencil'></span>
					</div>
				 </form> 
			  </td>
			  <td align='center'>
				<form action='produto.php' 
					method='POST'>
					<div class='input-group'>
						<input type='hidden' name='postId' value='$idProduto'>
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
	//caso algum erro tenha ocorrido durante a consulta ao banco
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


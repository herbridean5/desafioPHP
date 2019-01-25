<?php
include("header.php");
include("../conexao/conexao.php");

if (isset($_POST["postNome"])){
$nomeTipoproduto = $_POST["postNome"];
$impostoTipoproduto = $_POST['postImposto'];

//concatenando todas as strings, sera usada para testes de caracteres especiais
$stringPteste = $nomeTipoproduto.$impostoTipoproduto;

//testando para ver se nenhum campo possui caracteres especiais ( como por exemplo '; )
if(!(preg_match('/[!@#$%^&*()?\'":;{}|<>]/',$stringPteste))){
	
$insertSql = "INSERT INTO tipoproduto (nome_tipoProduto, imposto_tipoProduto) VALUES 
	('$nomeTipoproduto',$impostoTipoproduto)";

try{ 
	$consultaInsert = Conexao::prepare($insertSql);
	$consultaInsert->execute();
	
	echo("
		<div class='row'>
		 <div class='col-lg-6'>
		 <div class='alert alert-success'>
		 <b>Dados inseridos com sucesso!!!</b><br>
		 <a href='tipoProduto.php' 
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
				<h1>Cadastro de Tipos de Produto</h1>

				<form role="form" action="tipoProduto_novo.php" method="POST" enctype="multipart/form-data">	
					<div class="form-group">
						<label>Nome do Tipo do produto</label>
						<input type='text' class="form-control" name='postNome' placeholder="Processador">
					</div>
					
					<div class="form-group">
						<label>Valor do imposto em Double</label>
						<input type='number' step='0.01' class="form-control" name='postImposto' placeholder="4.5">
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

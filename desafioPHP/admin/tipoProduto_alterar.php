<?php
include("header.php");
include("../conexao/conexao.php");

//if que faz a alterar em banco apos os dados serem recebidos por POST
if (isset($_POST["postNome"]))
{
$nomeTipoproduto = $_POST["postNome"];
$impostoTipoproduto = $_POST['postImposto'];
$idTipoproduto = $_POST["postId"];

//concatenando todas as strings, sera usada para testes de caracteres especiais
$stringPteste = $nomeTipoproduto.$impostoTipoproduto.$idTipoproduto;

//testando para ver se nenhum campo possui caracteres especiais ( como por exemplo '; )
if(!(preg_match('/[!@#$%^&*()?\'":;{}|<>]/',$stringPteste))){

$updateSql = "UPDATE tipoproduto SET 
	nome_tipoProduto = '$nomeTipoproduto', 
	imposto_tipoProduto ='$impostoTipoproduto' WHERE id_tipoProduto = $idTipoproduto";
	
try{ 
$consultaUpdate = Conexao::prepare($updateSql);
$consultaUpdate->execute();
echo("
	<div class='row'>
		<div class='col-lg-6'>
		<div class='alert alert-success'>
		<b>Dados atualizados com sucesso!!!</b><br>
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
//else if para pegar dados do tipoproduto que veio do tipoProduto.php
else if (isset($_POST["postId"]))
{
$selectSql = "SELECT * FROM tipoproduto 
WHERE id_tipoProduto = ".$_POST["postId"].";";

try{
//preparando consulta Select
$consultaSelect = Conexao::prepare($selectSql);
$consultaSelect->execute();

$dadosUsuarios = $consultaSelect->fetchAll(PDO::FETCH_ASSOC);
foreach( $dadosUsuarios as $linhaConsulta) {
	$idTipoproduto = $linhaConsulta['id_tipoProduto'];
	$nomeTipoproduto = $linhaConsulta['nome_tipoProduto'];
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
					<h1>Alterar Tipo Produto</h1>
					<form role="form" action="tipoProduto_alterar.php" method="POST">

						<div>
							<label>Nome do Tipo do produto</label>
							<input type='text' class="form-control" name='postNome' placeholder="Processador" value="<?php echo $nomeTipoproduto ?>">
						</div>
						
						<div class="form-group">
							<label>Valor do imposto em Double</label>
							<input type='number' step='0.01' class="form-control" name='postImposto' placeholder="4.5">
						</div>
						
						<input type="hidden" name="postId" value="<?php echo $idTipoproduto ?>">
						<button type="submit" class="btn btn-info">Salvar</button>
						<button type="reset" class="btn btn-danger">Limpar</button>
					</form>
				</div>
	</div>
<?php
}
include("footer.php");
?>

<?php
include("header.php");
include("../conexao/conexao.php");

//if que faz a alteração em banco apos os dados serem recebidos por POST
if (isset($_POST["postNome"])){
$idProduto = $_POST["postId"];
$nomeProduto = $_POST["postNome"];
$valorProduto = $_POST["postValor"];
$descriProduto = $_POST["postDescri"];
$tipoProduto = $_POST["tipoProduto"];

//concatenando todas as strings, sera usada para testes de caracteres especiais
$stringPteste = $idProduto.$nomeProduto.$descriProduto.$valorProduto.$tipoProduto;

$nomeArquivo = $_FILES["arquivo"]["name"];
$extensaoArquivo = $_FILES["arquivo"]["type"];
$tamanhoArquivo = $_FILES["arquivo"]["size"];
$nomeTemporario = $_FILES["arquivo"]["tmp_name"];
$erroArquivo = $_FILES["arquivo"]["error"];

//testando para ver se nenhum campo possui caracteres especiais ( como por exemplo '; )
if(!(preg_match('/[!@#$%^&*()?\'":;{}|<>]/',$stringPteste))){

//criando consulta de update
$updateSql = "UPDATE produto SET 
	nome_produto = '$nomeProduto', 
	descri_produto ='$descriProduto',
	valor_produto = $valorProduto,
	fk_tipoProduto = $tipoProduto,
	arquivo_produto = '$nomeArquivo',
	extensao_arquivo = '$extensaoArquivo'
WHERE id_produto = $idProduto";

	//testando se ouve alguem erro com o upload do arquivo
	if ($erroArquivo == 0){
		try{ 
			$consultaUpdate = Conexao::prepare($updateSql);
			$consultaUpdate->execute();
			//faz conexão com o banco e exibe mensagem de sucesso
			
			//movendo arquivo para pasta de imagens
			if(move_uploaded_file($nomeTemporario, "../imgs/$nomeArquivo")){
				echo ("<div class='alert alert-sucess'>
					<b>Arquivo transferido para a pasta /imgs com sucesso</b>
				</div>");
			}else{
				//caso algum erro tenha ocorrido durante a transferencia (geralmente por falta de permissao na pasta /imgs)
				die ("<div class='alert alert-danger'>
					<b>Erro: falha em transferir arquivo, por favor checar permissão de escrita da pasta /imgs</b>
				</div>");
			}
			
			echo("
			<div class='row'>
				<div class='col-lg-6'>
					<div class='alert alert-success'>
						<b>Dados atualizados com sucesso!!!</b><br>
						<a href='produto.php' 
						class='btn btn-success' role='button'>
						<span class='glyphicon 
						glyphicon-arrow-left'></span>
						Voltar</a>
					</div>
				</div>
			</div>
			");
		}catch(PDOException $e){
			//exibe mensagem de erro caso tenha ocorrido alguma falha na consulta ao banco
			die("
			<div class='alert alert-danger'>
			<b>Falha na execução do SQL!<br>sqlException: $e</b>
			</div>
			");
		}
	}else{ 
		//else do if de upload do arquivo
	   	die ("<div class='alert alert-danger'>
				<b>Erro no upload do Arquivo</b>
		</div>");
	}	
}else{
	die("
		<div class='alert alert-danger'>
		<b>ERRO: algum campo possui caracteres especiais, nenhum dado foi salvo em banco</b>
		</div>
	");
}
} 
//else if para pegar dados do produto que veio do produto.php
else if (isset($_POST["postId"])){
$selectSql = "SELECT * FROM produto 
WHERE id_produto = ".$_POST["postId"].";";

try{
	//preparando consulta Select
	$consultaSelect = Conexao::prepare($selectSql);
	$consultaSelect->execute();

	$dadosUsuarios = $consultaSelect->fetchAll(PDO::FETCH_ASSOC);
	foreach( $dadosUsuarios as $linhaConsulta) {
		$idProduto = $linhaConsulta['id_produto'];
		$nomeProduto = $linhaConsulta['nome_produto'];
		$valorProduto = $linhaConsulta['valor_produto'];
		$descriProduto = $linhaConsulta['descri_produto'];
	} 
}catch(PDOException $e){
die("
	<div class='alert alert-danger'>
		<b>Falha na execução do SQL!<br>sqlException: $e</b>
	</div>
");
}
?>
<div class="row">
<div class="col-lg-6">
<h1>Alterar Produto</h1>
<form role="form" action="produto_alterar.php" method="POST" enctype="multipart/form-data">	
<div class="form-group">
	<label>Nome do Produto</label>
	<input type='text' class="form-control" name='postNome' value="<?php echo $nomeProduto ?>">
</div>

<div class="form-group">
	<label>Descrição do produto</label>
	<br>
	<textarea name="postDescri" rows="10" cols="110"><?php echo $descriProduto ?></textarea>
</div>

<div class="form-group">
	<label>Valor do Produto</label>
	<input type='number' step='0.01' class="form-control" name='postValor' value="<?php echo $valorProduto ?>">
</div>

<div class="form-group">
<label>Tipo Produto</label>
	<select class="form-control" name='tipoProduto'>
	<?php 
		//criando consulta de select e a executando
		$sqlTipoproduto = "SELECT 
				  id_tipoProduto,
				  nome_tipoProduto
				FROM
				  tipoproduto";
		$consultaTipo = Conexao::prepare($sqlTipoproduto);
		$consultaTipo->execute();
	
		//criando combobox com a lista de todos os tipos de produtos cadastrados
		$dadosTipoproduto = $consultaTipo->fetchAll(PDO::FETCH_ASSOC);
		foreach( $dadosTipoproduto as $linhaConsulta) {   
			   echo("<option
			   value='".$linhaConsulta['id_tipoProduto']."'>
			   ".$linhaConsulta['nome_tipoProduto']."</option>");
		}
	?>    
	</select>
	
	<br>  
	<div class="form-group">
		<label>Imagem (Arquivo *.jpg, *.png, *.gif)</label>
		<input type='file' class="form-control" name='arquivo'>
	</div
			  
</div>
<input type="hidden" name="postId" value="<?php echo $idProduto ?>">
<button type="submit" class="btn btn-info">Salvar</button>
<button type="reset" class="btn btn-danger">Limpar</button>
</form>
</div>
</div>
<?php
}
include("footer.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
<title>Produto</title>


    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../bootstrap/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

	<?php 
		if (isset($_POST['postId'])){
			
			include("../conexao/conexao.php");
		
			$selectSql = "
			SELECT 
				id_produto,
				nome_produto,
				descri_produto,
				valor_produto,
				fk_tipoProduto,
				nome_tipoProduto,
				imposto_tipoProduto,
				arquivo_produto
			FROM
				produto as pr inner join tipoproduto as tp on (pr.fk_tipoProduto=id_tipoProduto)
			WHERE id_produto = ".$_POST["postId"]."";

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
				$nomeTipoproduto = $linhaConsulta['nome_tipoProduto'];
				$impostoTipoproduto = $linhaConsulta['imposto_tipoProduto'];
				$nomeArquivo = $linhaConsulta['arquivo_produto'];
				
				$url = "../imgs/$nomeArquivo";
			} 
		}catch(PDOException $e){
			die("
				<div class='alert alert-danger'>
				<b>Falha na execução do SQL!<br>sqlException: $e</b>
				</div>
			");
		}	
	}
	?>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Produtos</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
			<?php
				if (isset($_POST['postQnt']) and $_POST['postQnt']>0){
					$quantidadeComprada = $_POST['postQnt'];
					$valorImposto = ($valorProduto*($impostoTipoproduto/100))*$quantidadeComprada;
					$valorFinal = ($valorProduto*$quantidadeComprada)+$valorImposto;
					
					echo("<br>
						<div class='row'>
							<div class='col-lg-12'>
							<div class='alert alert-success'>
							
							<b>Voce acaba de comprar $quantidadeComprada - $nomeProduto <br> 
							Pelo preço de $valorProduto R$ por cada unidade <br>
							O tipo produto \"$nomeTipoproduto\" possui $impostoTipoproduto% de imposto <br>
							Totalizando $valorImposto R$ de imposto e $valorFinal R$ de valor final de compra</b>
							
							<br><br>
							<a href='index.php' 
							class='btn btn-success' role='button'>
							<span class='glyphicon 
							glyphicon-arrow-left'></span>
							Voltar</a>
							</div>
							</div>
						</div>
					");
				}
			?>
					
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo($nomeProduto); ?>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-6">
                    <div>
                        <div class="item active">
                            <img class="img" widht="400" height="420" src="<?php echo($url);?>" alt="">
                        </div>
                    </div>
            </div>

            <div class="col-md-4">
                <h3>Nome</h3>
                <p><?php echo($nomeProduto." - ".$nomeTipoproduto);?></p>
            </div>
            <div class="col-md-4">
                <h3>Descrição</h3>
                <p><?php echo($descriProduto);?></p>
            </div>
            <div class="col-md-4">
                <h3>Valor</h3>
                <p><?php echo($valorProduto);?></p>	
            </div>
			<div class="col-md-4">
				<form role="form" action="produto.php" method="post" enctype="multipart/form-data">	
					<div class"col-md-4">	 
						<h3>Quantidade:</h3>
						<input type="number" name="postQnt" min="1" max="100">
					</div>
					
					<br>
					<input type="hidden" name="postId" value="<?php echo $idProduto ?>">
					<button type="submit" class="btn btn-info">Comprar</button>        
				</form>
			</div>     
        </div>
        <!-- /.row -->
        <hr>
    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../bootstrap/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>

</body>

</html>

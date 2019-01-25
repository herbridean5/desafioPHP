<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lista de Produtos</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../bootstrap/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="index.php">Produtos</a>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Page Content -->
    <div class="container">
        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Conheça nossos produtos
                </h1>

            </div>
        </div>
        <!-- /.row -->
		<?php
			include("../conexao/conexao.php");
		
			$selectSql = "
			SELECT 
				id_produto,
				nome_produto,
				descri_produto,
				valor_produto,
				fk_tipoProduto,
				nome_tipoProduto,
				arquivo_produto
			FROM
				produto as pr inner join tipoproduto as tp on (pr.fk_tipoProduto=id_tipoProduto)";
				
			$count = 0;
			$cursos = "<div class=\"row\">";
			try{
				//preparando consulta Select
				$consultaSelect = Conexao::prepare($selectSql);
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
					
					$count++;
		
					$url = "../imgs/$nomeArquivo";
						
					$cursos .="
						<div class='col-md-4 img-portfolio'>
							<form method='POST' id='formp'action='produto.php'>
								<input type='hidden' name='postId' value='$idProduto'>
								<input type='image' width='350' height='300' src='$url' alt='Submit' />
								<h3>
									<a>$nomeproduto - $fkTipo</a>
								</h3>
							</form>
							<p>$descriProduto</p>
					</div>";
					if($count%3 == 0){
						//criando nova linha para cada 3 produtos
						$cursos .="
						</div>
							<div class='row'>
						";
					}
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
		
			$cursos .= "</div>";	
			echo $cursos;
		?>
		<hr>
    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../bootstrap/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>

</body>

</html>

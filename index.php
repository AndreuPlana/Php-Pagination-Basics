
<?php session_start(); ?>

<!DOCTYPE html>
<?php 

/*

*****************************
Usuari Admin 

user: admin
pass: admin

Permet afegir productes i/o crear usuaris.
*****************************
*/

$servidor="localhost";
		$usuari="root";
		$password="";
		$bbdd="botiga2018";
		$connexio = mysqli_connect($servidor,$usuari,$password,$bbdd); ?>
<html>
<head>
	<?php 

		
		$servidor="localhost";
			$usuari="root";
			$password="";
			$bbdd="botiga2018";
			$connexio = mysqli_connect($servidor,$usuari,$password,$bbdd);

	if (@$_SESSION["valid"]==1) {		
		
		if (isset($_REQUEST['paginacio'])){
			$inici = $_REQUEST['paginacio'];
		} else {
			$inici = 0;
		}



		
		
		if(!empty($_POST["newuser"]))
		{
			$nuser=$_POST["nuser"];
			$npass=$_POST["npass"];
			$nnom=$_POST["nom"];
			$ncognoms=$_POST["cognoms"];
			$nemail=$_POST["email"];
			$ndireccio=$_POST["direccio"];
			$npoblacio=$_POST["poblacio"];
			$ncPostal=$_POST["cPostal"];


			$npassword=md5($npass);


			$sql="insert into usuari (codiU,password,nom,cognoms,email,direccio,poblacio,cPostal) values ('$nuser','$npassword', '$nnom','$ncognoms','$nemail','$ndireccio','$npoblacio','$ncPostal')";
			mysqli_query($connexio,$sql);
			?>
			<script type="text/javascript">alert("Usuari Afegit!");</script>
			<?php

		}
		if (!empty($_POST["afegir"])) {
 			$foto = $_FILES["foto"]["name"];
 			$temp = $_FILES["foto"]["tmp_name"];
 			$foto  = $_FILES['foto']['tmp_name'];
 			$ex  = $_FILES['foto']['type'];
 			$pos = strpos($ex, "/")+1;
 			$extensio = substr($ex, $pos, strlen($ex));
 			$foto = addslashes(file_get_contents($_FILES["foto"]["tmp_name"]));
 			$nom=$_POST["pnom"];
 			$preu=$_POST["preu"];
 			$stock=$_POST["stock"];
 			
 			@$arx= file_get_contents($foto);
 			 			

 			$sql = "insert into producte (nom,preu,stock,dadesImatge,tipusImatge) values ('$nom','$preu','$stock','$foto','$extensio')";
 			mysqli_query($connexio,$sql);
 
 		}
 		if(!empty($_POST["comprar"])){
 		$rand= rand(0,1000);

        foreach ($_POST as $key => $value) {
            $ncodi="hcodi".$value;
            

            if(!empty($_POST[$ncodi])){
                $vcodi=$_POST[$ncodi];

                $nm=$_POST["hnom".$vcodi];
                $pre=$_POST["hpreu".$vcodi];
                $quant=$_POST["hquantitat".$vcodi];
                $stock=$_POST["hstock".$vcodi];
                $user=$_SESSION["user"];
                $ses=session_id()."".$rand;
                
                $insert="insert into comanda values ('$ses','$user','$vcodi','$quant');";
                $res=mysqli_query($connexio,$insert);
                $sqlUpd="update producte set stock='$stock' where codiP='$vcodi'";
                $resu=mysqli_query($connexio,$sqlUpd);

           		}
        	}
        	?><script type="text/javascript">alert("Compra Realitzada !");</script><?php
        	session_destroy();
        	header("Refresh:0");
    	}
    	if (!empty($_POST["sortir"])) {
    		
    		?>
			<script type="text/javascript">alert("Sessio tancada!");</script>
    		<?php
    		session_destroy();
    		header("Refresh:0");
    	}


	 ?>

	 <script type="text/javascript">
	 	
	 	var Carro = function(codicomanda,codiuser,codiproducte,quantitat){
			this.codic = codicomanda;
			this.codiu = codiuser;
			this.codip = codiproducte;
			this.quantitat = quantitat;
		}

		var Producte = function(codiproducte,nom,preu,stock,quantitat){
			this.codiProducte = codiproducte;
			this.nomProducte = nom;
			this.preuProducte = preu;
			this.quantitatProducte = quantitat;
			this.stockProducte = stock-quantitat;
			

		}
		var total="";
		var iniciat=0;
		var n = 0;
		var productes = new Array();
		var llista="";

		function comprarProducte(id){
		
			var nom = document.getElementById("prodnom"+id).value;
			var preu = document.getElementById("prodpreu"+id).value;
			var stock = document.getElementById("prodstock"+id).value;
			var quantitat = document.getElementById("quantitat"+id).value;
			var auxprod = new Producte(id,nom,preu,stock,quantitat);
			productes[n]=auxprod;	
			n++;
			afegirCarro();
		}

		function afegirCarro(){
			llista="";
			
			total=0;
			for(var i=0;i<productes.length;i++){
			 llista+= "<tr><td>"+productes[i].nomProducte+"</td><td>"+productes[i].quantitatProducte+" X "+productes[i].preuProducte+"€</td><td id='tot"+i+"'>"+productes[i].quantitatProducte*productes[i].preuProducte+"</td></tr>";
				llista+="<input type=hidden name='hcodi"+productes[i].codiProducte+"' value="+productes[i].codiProducte+">";
				llista+="<input type=hidden name='hnom"+productes[i].codiProducte+"' value="+productes[i].nomProducte+">";
				llista+="<input type=hidden name='hpreu"+productes[i].codiProducte+"' value="+productes[i].preuProducte+">";
				llista+="<input type=hidden name='hstock"+productes[i].codiProducte+"' value="+productes[i].stockProducte+">";
				llista+="<input type=hidden name='hquantitat"+productes[i].codiProducte+"' value="+productes[i].quantitatProducte+">";
				total+=productes[i].quantitatProducte*productes[i].preuProducte;

			}
			total+=" €";
			document.getElementById("llista").innerHTML = llista;
			if(iniciat==1){
			document.getElementById("total").innerHTML = total+"<br> <button class='btn btn-success' name='comprar' value='1'>Comprar</button>";


		}
		}

		




	 </script>
	<title>TechShop</title>
	<link href="img/icono.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link href="https://fonts.googleapis.com/css?family=Supermercado+One" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="estil.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 

	

</head>
<body>
	
	<div class="container-fluid" >
		<h1 class="text-center" style="font-family: 'Supermercado One', cursive; font-size: 5em;">Shop Online</h1>
	</div>
	<span style="height: 300px;"></span>
	<div class="container">
		<div class="container-fluid prods">
			
			<h1 class="text-center linia caps">Productes</h1>
			<?php if(@$_SESSION["user"]=="admin") {
				
			 ?> 
			<h4 class="text-center linia  caps sepesq"><a style="text-decoration: none;" data-toggle="modal" data-target="#crear">Crear usuari</a></h4>	
			<h4 class="text-center linia  caps sepesq"><a style="text-decoration: none;" data-toggle="modal" data-target="#afegir">Afegir Producte</a></h4> 
			<?php } ?>
			<div id="inici"></div>
			<?php 
			if(!isset($_SESSION["valid"])) {
					?>
					<script type="text/javascript">
						document.getElementById("inici").innerHTML = "<span id=\"inici\"><button type=\"button\" id=\"inici\"  class=\"blogin linia	 btn btn-success navbar-btn navbar-right\" data-toggle=\"modal\" data-target=\"#log\">Login</button></span> ";
					</script>

					<?php
			}else{
				?>
				<script type="text/javascript">
						iniciat=1;

						document.getElementById("inici").innerHTML = "<form method='POST' action='index.php'><span id=\"\"><input type=\"submit\"  class=\"blogin linia	 btn btn-danger navbar-btn navbar-right\" value='sortir'></span><input type='hidden' value='1' name='sortir'></form> ";
					</script>
				<?php
			}

	 ?>
			 
		</div>
		
		<div class="container-fluid" >
			<div class="row">
			
			<div class="col-md-9">
			
			<div class="row prods">
			

			<?php 
			$servidor="localhost";
			$usuari="root";
			$password="";
			$bbdd="botiga2018";
			$connexio = mysqli_connect($servidor,$usuari,$password,$bbdd);
				$linies = 8;
				$impresos = 0;
				$sql = "select * from producte limit $inici, $linies";
        		$resultat = mysqli_query($connexio, $sql);
        		while($fila = mysqli_fetch_assoc($resultat)){
         				echo "<div class=\"col-md-3 sepbottom\" >";
        				echo "<h3 class=\"text-center\">".$fila["nom"]."</h3><br>";
        				echo "<img class=\"img-fluid rounded center-block foto mb-3 mb-md-0\" src='data:image/".$fila["tipusImatge"].";base64,".base64_encode($fila["dadesImatge"])."'><br>";
        				echo "<h4 class=\" text-center \"> ".$fila["preu"]."€</h4>";
        				echo "<p class=\"text-center\">Stock : ".$fila["stock"]."</p>";
        				echo "<button onclick=\"comprarProducte(".$fila["codiP"].")\" class=\"btn btn-primary center-block\" style='display:inline-block;' id=\"".$fila["codiP"]."\">Afegir Al Carro</button>";
        				echo "<input type='number' min=\"1\" max=\"10\" id='quantitat".$fila["codiP"]."' name='quantitat' required='' class='text-center' style='display:inline-block; width:30px; margin-left:10px;' >";
        				echo "<input type='hidden' id='prodnom".$fila["codiP"]."' name='prodnom".$fila["codiP"]."' value='".$fila["nom"]."'>";
        				echo "<input type='hidden' id='prodpreu".$fila["codiP"]."' name='prodnom".$fila["codiP"]."' value='".$fila["preu"]."'>";
        				echo "<input type='hidden' id='prodstock".$fila["codiP"]."' name='prodnom".$fila["codiP"]."' value='".$fila["stock"]."'>";
        				echo "</div>";
        				$impresos++;
 
        		}




			 ?>
			<div style="height: 100px;"> </div>
			
			</div>

			</div>
			<div class="col-md-3 prods">
				<h4 class="text-center">Carro</h4>
				<form method="POST" action="index.php" enctype="multipart/form-data">
				<table class="table table-hover" id="llista">
				
				</table>

				<strong class="">Total : </strong><span id="total"></span>
				</form>
			</div>
		</div>
	</div>
	


<div class="modal fade" id="log" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class=" mod modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Login</h4>
        </div>
        <div class="modal-body">
         <form method="POST" action="index.php" enctype="multipart/form-data">
			  <div class="form-group" method="POST" action="index.php">
			    <label for="Usuari">Usuari</label>
			    <input type="text" class="form-control" name="user" id="Usuari" placeholder="Usuari">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Password</label>
			    <input type="password" name="pass" class="form-control" id="exampleInputPassword1" placeholder="Password">
			  </div>
			 <div class="form-group">
			 	<input type="hidden" name="login" value="1">
			 </div>
			  <div class="form-group">
			  	<button type="submit" class="btn btn-success">Login</button>
			  </div>
			</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Tancar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="crear" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class=" mod modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Crear Usuari</h4>
        </div>
        <div class="modal-body">
         <form method="POST" action="index.php" enctype="multipart/form-data">
			  <div class="form-group" method="POST" action="index.php">
			    <label for="Usuari">Usuari</label>
			    <input type="text" class="form-control" name="nuser" id="Usuari" placeholder="Usuari">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Password</label>
			    <input type="password" name="npass" class="form-control" id="exampleInputPassword1" placeholder="Password">
			  </div>
			  <div class="form-group">
			    <label for="nom">Nom</label>
			    <input type="text" name="nom" class="form-control" id="nom" placeholder="Nom">
			  </div>
			  <div class="form-group">
			    <label for="cognoms">Cognoms</label>
			    <input type="text" name="cognoms" class="form-control" id="cognoms" placeholder="Cognoms">
			  </div>
			  <div class="form-group">
			    <label for="email">Email</label>
			    <input type="email" name="email" class="form-control" id="email" placeholder="email">
			  </div>
			  <div class="form-group">
			    <label for="Direccio">Direccio</label>
			    <input type="text" name="direccio" class="form-control" id="Direccio" placeholder="Direccio">
			  </div>
			  <div class="form-group">
			    <label for="Poblacio">Poblacio</label>
			    <input type="text" name="poblacio" class="form-control" id="Poblacio" placeholder="Poblacio">
			  </div>
			  <div class="form-group">
			    <label for="CodiPostal">CodiPostal</label>
			    <input type="text" name="cPostal" class="form-control" id="CodiPostal" placeholder="CodiPostal">
			  </div>
			 <div class="form-group">
			 	<input type="hidden" name="newuser" value="1">
			 </div>
			  <div class="form-group">
			  	<button type="submit" class="btn btn-success">Crear</button>
			  </div>
			</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Tancar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="afegir" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class=" mod modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Afegir Producte ADMIN</h4>
        </div>
        <div class="modal-body">
         <form method="POST" action="index.php" enctype="multipart/form-data">
			  <div class="form-group" method="POST" action="index.php">
			    <label for="pnom">Nom</label>
			    <input type="text" class="form-control" name="pnom" id="pnom" placeholder="pnom">
			  </div>
			  <div class="form-group">
			    <label for="">Preu</label>
			    <input type="number" name="preu" class="form-control" id="preu" placeholder="Preu">
			  </div>
			  <div class="form-group">
			    <label for="">stock</label>
			    <input type="number" name="stock" class="form-control" id="stock" placeholder="Stock">
			  </div>
			  <div class="form-group">
			    <label for="foto">Imatge</label>
		    	<input type="file" name="foto">
			  </div>
			 <div class="form-group">
			 	<input type="hidden" name="afegir" value="1">
			 </div>
			  <div class="form-group">
			  	<button type="submit" class="btn btn-success">Afegir Producte</button>
			  </div>
			</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Tancar</button>
        </div>
      </div>
    </div>
  </div>
<div class="pagines">
<?php 
if ($inici == 0) {
		echo "Anteriors ";
	} else {
		$anterior = $inici - $linies;
		echo "<a href=\"index.php?paginacio=$anterior\">Anteriors </a>";
	}
	if ($impresos == $linies) {	
		$proper = $inici + $linies;
		echo "<a href=\"index.php?paginacio=$proper\">Següents </a>";
	} else {
		echo "Següents ";
	}
?>
</div>
<?php

}else{

	if(isset($_POST["login"]))
		{
			
			$user= $_POST["user"];
			$password = $_POST["pass"];

			$trobat=0;
			$password=md5($password);
			$sql = "SELECT `codiU`, `password` FROM `usuari` WHERE `codiU`='$user'";
	        $resultat = mysqli_query($connexio, $sql);
	        while($fila = mysqli_fetch_assoc($resultat)){
	        	foreach ($fila as $key => $value) {
	        		if($key=="password"){
	        			
	        		if($value==$password){
	        			$_SESSION["valid"]=1;
	        			$_SESSION["user"]=$user;
	        			$trobat=1;
	        			
	        			?><script type="text/javascript">
	        				var val = "<?php echo $user ?>";
	        				alert("Benvingut "+val);
	        				iniciat=1;
 							
	        			</script><?php

	        			header("Refresh:0");

	        		}	
	        	}
	        	}
	        }
	        if ($trobat!=1) {
	        	?><script type="text/javascript">alert("Usuari i/o Contrassenya Incorrectes");</script><?php
	        }

		}

		?> 

</script>
	<title>TechShop</title>
	<link href="img/icono.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link href="https://fonts.googleapis.com/css?family=Supermercado+One" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="estil.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<div class="row">
  <div class="col-md-offset-4 col-md-4 ">
  	<h2 class="text-center" style="font-family: 'Supermercado One', cursive; font-size: 3em;">ONLINE SHOP</h2>
  <form name='formulari' action='index.php' method='POST' enctype='multipart/form-data' class='form'>

	<strong>USUARI</strong>  <input type="text" name="user" required="" placeholder="Usuari" style="margin-bottom: 30px;" class="form-control"> 
	<strong>PASSWD</strong>	  <input type="password" name="pass" required="" placeholder="Password" class="form-control"> 

	<button type="submit" class="btn btn-success center-block" style="margin-top: 36px;width: 100px;margin-bottom: 60px;">LOGIN</button>
    <input type='hidden' value='1' name='login'/>       
  </form>
  </div>
</div>




		<?php


}

 ?>
</body>
</html>
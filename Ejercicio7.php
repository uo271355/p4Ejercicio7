<!DOCTYPE  html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ejercicio 7</title>
<link rel="stylesheet" href="Ejercicio7.css">
</head>
<body>
<header>
<h1>Gestion de la base de datos</h1>
</header>
<section>
<pre>
<code>
<?php
class BaseDatos{
	private $nombreServidor;
    private $usuario;
    private $contraseña;
    private $nombreBDatos;
	
	public function __construct(){
		session_start();
		$this->nombreServidor="localhost";
		$this->usuario="DBUSER2020";
		$this->contraseña="DBPSWD2020";
		$this->nombreBDatos="BDatos7";
	}
	public function inicializar(){
		if (count($_POST)>0) { 
            if(isset($_POST["crearB"])){
                $this->crearBaseDatos();
            }else if(isset($_POST["crearT"])){
                $this->crearTabla();
            }else if(isset($_POST["insertarCoche"])){
                $this->insertarCoche();
            }else if(isset($_POST["insertarAgencia"])){
                $this->insertarAgencia();
            }else if(isset($_POST["insertaAlquileres"])){
                $this->insertaAlquileres();
            }else if(isset($_POST["buscarAgencias"])){
                $this->buscarAgencias();
            }else if(isset($_POST["buscarCoche"])){
                $this->buscarCoches();
            }else if(isset($_POST["buscarAlquileres"])){
                $this->buscaAlquileres();
            }else if(isset($_POST["exportar"])){
                $this->exportar();
            }else if(isset($_POST["cargar"])){
                $this->cargar();
            }
		}
	}
	
	public function crearBaseDatos(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña);
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$creaBDSQL="create database if not exists BDatos7 collate utf8_spanish_ci";
		
		if($baseDatos->query($creaBDSQL) !== TRUE){
			exit ("<p>ERROR EN BASE DE DATOS:  BDatos7 </p>");  
		}else{
			echo "<h4>Creada BDatos7 con exito</h4>";
		}
		
		$baseDatos->close();
	}
	
	public function crearTabla(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		$crearTablaCochesSQL="create table if not exists Coches(
			matricula varchar(7) not null,
			marca varchar(255) not null,
			modelo varchar(255) not null,
			precioC int not null,
			primary key(matricula)
		)";
		
		if($baseDatos->query($crearTablaCochesSQL) !== TRUE){
			exit ("<p>ERROR AL CREAR TABLA: Coches </p>");  
		}else{
			echo "<h4>Creada tabla Coches con exito</h4>";
		}
		
		$crearTablaAgenciasSQL="create table if not exists Agencias(
			id_agencia int not null,
			nombre varchar(255) not null,
			precioA int not null,
			valoracion int not null,
			primary key(id_agencia)
		)";
		
		if($baseDatos->query($crearTablaAgenciasSQL) !== TRUE){
			exit ("<p>ERROR AL CREAR TABLA: Agencias </p>");  
		}else{
			echo "<h4>Creada tabla Agencias con exito</h4>";
		}
		
		$crearTablaAlquileresSQL="create table if not exists Alquileres(
			matriculaA varchar(7) not null,
			id_agenciaA int not null,
			precio int not null,
			dias int not null,
			foreign key(matriculaA) references Coches(matricula),
			foreign key(id_agenciaA) references Agencias(id_agencia),
			primary key(id_agenciaA,matriculaA)
		)";
		
		if($baseDatos->query($crearTablaAlquileresSQL) !== TRUE){
			exit ("<p>ERROR AL CREAR TABLA: Alquileres </p>");  
		}else{
			echo "<h4>Creada tabla Alquileres con exito</h4>";
		}
		$baseDatos->close();
	}
	public function insertarCoche(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$this->compruebaCamposCoche();
		
		$insertarCocheSQL=$baseDatos->prepare("insert into Coches (matricula,marca,modelo,precioC) values (?,?,?,?)");
		$insertarCocheSQL->bind_param('sssi',$_POST["matricula"],$_POST["marca"],$_POST["modelo"],$_POST["precioC"]);
		$insertarCocheSQL->execute();
		echo "<h4>Se han insertado los datos del coche</h4>";
		$insertarCocheSQL->close();
		$baseDatos->close();
	}
	public function compruebaCamposCoche(){
		if(strlen($_POST["matricula"])!=7){
			echo "<p class='error'>El campo dni tiene el siguiente formato ZZZZXXX. (Siendo X un numero y Z una letra)</p>";
			return;
		}else if(strlen($_POST["marca"])==0){
			echo "<p class='error'>El campo marca esta vacio</p>";
			return;
		}else if(strlen($_POST["modelo"])==0){
			echo "<p class='error'>El campo modelo esta vacio</p>";
			return;
		}else if(intval($_POST["precioC"]) ==0){
			echo "<p class='error'>El precio no puede ser 0</p>";
			return;
		}
		
	}
	public function insertarAgencia(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$this->compruebaCamposAgencia();
		
		$insertarAgenciaSQL=$baseDatos->prepare("insert into Agencias (id_agencia,nombre,precioA,valoracion) values (?,?,?,?)");
		
		$insertarAgenciaSQL->bind_param('isii',$_POST["id_agencia"],$_POST["nombre"],$_POST["precioA"],$_POST["valoracion"]);
		
		$insertarAgenciaSQL->execute();
		echo "<h4>Se han insertado los datos de la agencia</h4>";
		$insertarAgenciaSQL->close();
		$baseDatos->close();
	}
	public function compruebaCamposAgencia(){
		if(strlen($_POST["nombre"])==0){
			echo "<p class='error'>El campo nombre esta vacio</p>";
			return;
		}else if(intval($_POST["valoracion"])<0||intval($_POST["valoracion"])>10){
			echo "<p class='error'>La valoracion tiene que estar entre este intervalo [1-10]</p>";
			return;
		}else if(intval($_POST["precioA"]) ==0){
			echo "<p class='error'>El precio no puede ser 0</p>";
			return;
		}else if(intval($_POST["id_agencia"]) ==0){
			echo "<p class='error'>El identificador no puede ser 0</p>";
			return;
		}
	}
	public function buscarAgencias(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$buscarAgenciasSQL=$baseDatos->prepare('select * from Agencias where id_agencia=?');
		$buscarAgenciasSQL->bind_param('i',$_POST["id_agenciaB"]);
		$buscarAgenciasSQL->execute();
		$resultadoBusqueda = $buscarAgenciasSQL->get_result();
		if($resultadoBusqueda->fetch_assoc()==NULL){
			echo "<p class='error'>No se ha podido realizar la busqueda</p>";
		}else{
			echo "<h5>La busqueda se ha realizado correctamente</h5>";
			$resultadoBusqueda->data_seek(0);
			while($fila=$resultadoBusqueda->fetch_assoc()){
				$mostrarBusqueda='<ul>';
				$mostrarBusqueda.='<li> Identificador agencia: '.$fila["id_agencia"].'</li>';
				$mostrarBusqueda.='<li> Nombre: '.$fila["nombre"].'</li>';
				$mostrarBusqueda.='<li> Precio: '.$fila["precioA"].'</li>';
				$mostrarBusqueda.='<li> Valoracion: '.$fila["valoracion"].'</li>';
				$mostrarBusqueda.='</ul>';
				
				echo $mostrarBusqueda;    
			}
		}
		$buscarAgenciasSQL->close();
		$baseDatos->close();
	}
	
	public function buscarCoches(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$buscarCochesSQL=$baseDatos->prepare('select * from Coches where matricula=?');
		$buscarCochesSQL->bind_param('s',$_POST["matriculaB"]);
		$buscarCochesSQL->execute();
		$resultadoBusqueda = $buscarCochesSQL->get_result();
		if($resultadoBusqueda->fetch_assoc()==NULL){
			echo "<p class='error'>No se ha podido realizar la busqueda</p>";
		}else{
			echo "<h5>La busqueda se ha realizado correctamente</h5>";
			$resultadoBusqueda->data_seek(0);
			while($fila=$resultadoBusqueda->fetch_assoc()){
				$mostrarBusqueda='<ul>';
				$mostrarBusqueda.='<li> Matricula: '.$fila["matricula"].'</li>';
				$mostrarBusqueda.='<li> Marca: '.$fila["marca"].'</li>';
				$mostrarBusqueda.='<li> Modelo: '.$fila["modelo"].'</li>';
				$mostrarBusqueda.='<li> Precio: '.$fila["precioC"].'</li>';
				$mostrarBusqueda.='</ul>';
				
				echo $mostrarBusqueda;    
			}
		}
		$buscarCochesSQL->close();
		$baseDatos->close();
	}
	public function insertaAlquileres(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$this->compruebaCamposReserva();
		
		$findPrecioCoche=$baseDatos->prepare("select precioC from Coches where matricula=?");
		$findPrecioCoche->bind_param('s',$_POST["matriculaA"]);
		$findPrecioCoche->execute();
		$precioCoche = $findPrecioCoche->get_result();
		$findPrecioCoche->close();
		
		$findPrecioAlquiler=$baseDatos->prepare("select precioA from Agencias where id_agencia=?");
		$findPrecioAlquiler->bind_param('i',$_POST["id_agenciaA"]);
		$findPrecioAlquiler->execute();
		$precioAlquiler=$findPrecioAlquiler->get_result();
		$findPrecioAlquiler->close();
		
		$precioTotal=($precioCoche+$precioAlquiler)*$_POST["dias"];
		
		$insertarAlquilerSQL=$baseDatos->prepare("insert into Alquileres (matriculaA,id_agenciaA,precio,dias) values (?,?,?,?)");
		
		$insertarAlquilerSQL->bind_param('siii',$_POST["matriculaA"],$_POST["id_agenciaA"],$precioTotal,$_POST["dias"]);
		
		$insertarAlquilerSQL->execute();
		echo "<h4>Se han insertado los datos del alquiler</h4>";
		$insertarAlquilerSQL->close();
		$baseDatos->close();
	}
	public function compruebaCamposReserva(){
		if(intval($_POST["dias"]) ==0){
			echo "<p class='error'>Los dias no pueden ser 0</p>";
			return;
		}else if(strlen($_POST["matriculaA"])!=7){
			echo "<p class='error'>El campo dni tiene el siguiente formato ZZZZXXX. (Siendo X un numero y Z una letra)</p>";
			return;
		}else if(intval($_POST["id_agenciaA"]) ==0){
			echo "<p class='error'>El identificador no puede ser 0</p>";
			return;
		}
	}
	public function buscaAlquileres(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$buscarAlquileresSQL=$baseDatos->prepare('select * from Alquileres where matriculaA=? and id_agenciaA=?');
		$buscarAlquileresSQL->bind_param('si',$_POST["matriculaBA"],$_POST["id_agenciaBA"]);
		$buscarAlquileresSQL->execute();
		$resultadoBusqueda = $buscarAlquileresSQL->get_result();
		if($resultadoBusqueda->fetch_assoc()==NULL){
			echo "<p class='error'>No se ha podido realizar la busqueda</p>";
		}else{
			echo "<h5>La busqueda se ha realizado correctamente</h5>";
			$resultadoBusqueda->data_seek(0);
			while($fila=$resultadoBusqueda->fetch_assoc()){
				$mostrarBusqueda='<ul>';
				$mostrarBusqueda.='<li> Matricula: '.$fila["matriculaA"].'</li>';
				$mostrarBusqueda.='<li> Identificador agencia: '.$fila["id_agenciaA"].'</li>';
				$mostrarBusqueda.='<li> Precio: '.$fila["precio"].'</li>';
				$mostrarBusqueda.='<li> Dias: '.$fila["dias"].'</li>';
				$mostrarBusqueda.='</ul>';
				
				echo $mostrarBusqueda;    
			}
		}
		$buscarAlquileresSQL->close();
		$baseDatos->close();
	}
	public function exportar(){
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		$exportarCochesSQL=$baseDatos->query('select * from Coches');
		$exportarAgenciasSQL=$baseDatos->query('select * from Agencias');
		$exportarAlquileresSQL=$baseDatos->query('select * from Alquileres');
		
		try{
			$nombreFichero = "Informacion.csv";

			$fichero = fopen($nombreFichero, "w");
			if($exportarCochesSQL->num_rows > 0){
				while($fila = $exportarCochesSQL->fetch_assoc()) {
					$linea = array($fila["matricula"], $fila["marca"], $fila['modelo'], $fila['precioC']); 
					fputcsv($fichero, $linea,";");
				}
			}
			if($exportarAgenciasSQL->num_rows > 0 ){
				while($fila = $exportarAgenciasSQL->fetch_assoc()) {
					$linea = array($fila["id_agencia"], $fila["nombre"], $fila['precioA'], $fila['valoracion']); 
					fputcsv($fichero, $linea,";");
				} 
			}
			if($exportarAlquileresSQL->num_rows > 0){
				while($fila = $exportarAlquileresSQL->fetch_assoc()) {
					$linea = array($fila["matriculaA"], $fila["id_agenciaA"], $fila['precio'], $fila['dias']); 
					fputcsv($fichero, $linea,";");
				}
			}
			fclose($fichero);
			echo "<h3> El archivo se ha exportado </h3>";

		}catch(Throwable $e){
			echo "<p class='error'>No se ha podido exportar el archivo</p>";
		}
		
		$baseDatos->close();
	}
	public function cargar(){
		
		$baseDatos = new mysqli($this->nombreServidor,$this->usuario,$this->contraseña,$this->nombreBDatos);
		
		if($baseDatos->connect_error){
			exit ("<p>ERROR CONEXION:".$baseDatos->connect_error."</p>");  
		} else {
			echo "<h4>La conexión se ha establecido correctamente con: " . $baseDatos->host_info . "</h4>";
		}
		
		if($_FILES){
			$nombreFichero = $_FILES["datos"]["name"];
			$informacion = new SplFileInfo($nombreFichero);
			$extension = pathinfo($informacion->getFilename(), PATHINFO_EXTENSION);

			if($extension == "csv"){
				$handle = fopen($nombreFichero, "r"); 

				while(($datos = fgetcsv($handle, 1000, ";")) !== FALSE){
					$cargarSQL = "insert into Coches (matricula, marca, modelo, precioC) 
						values ('$datos[0]','$datos[1]','$datos[2]','$datos[3]')";
					$baseDatos->query($cargarSQL);
					$cargarSQL = "insert into Agencias (id_agencia, nombre, precioA, valoracion) 
						values ('$datos[4],$datos[5],'$datos[6]',$datos[7]')";
					$baseDatos->query($cargarSQL);
					$cargarSQL = "insert into Agencias (id_agencia, nombre, precioA, valoracion) 
						values ('$datos[8],'$datos[9]','$datos[10]','$datos[11]')";
					$baseDatos->query($cargarSQL);
					$cargarSQL = "insert into Alquileres (matriculaA, id_agenciaA, precio, dias) 
						values ('$datos[12],'$datos[13]','$datos[14]','$datos[15]')";
					$baseDatos->query($cargarSQL);

					
				}

			  
			}
        }
		$baseDatos->close();
	}
}
$bdatos =new BaseDatos();
$bdatos->inicializar();
?>
</code>
</pre>
</section>
<section class='calculadora'>
<form action='#' method='post' name='clase' enctype='multipart/form-data'>
<h2>Crear base de datos</h2>
<input type='submit' class='button' name ='crearB' value='Crear'/>
<h2>Crear las tablas</h2>
<input type='submit' class='button' name ='crearT' value='Crear'/>
<h2>Insertar un coche</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='matricula'>Matricula:		</label> 
<input type='text' id='matricula' name ='matricula'/>
</div>

<div>
<label for='marca'>Marca:		</label> 
<input type='text' id='marca' name ='marca'/>
</div>

<div>
<label for='modelo'>Modelo:		</label> 
<input type='text' id='modelo' name ='modelo'/>
</div>

<div>
<label for='precioC'>Precio alquiler:		</label> 
<input type='text' id='precioC' name ='precioC'/>
</div>

<input type='submit' class='button' name ='insertarCoche' value='Insertar'/>

<h2>Insertar una Agencia</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='id_agencia'>Identificador:		</label> 
<input type='text' id='id_agencia' name ='id_agencia'/>
</div>

<div>
<label for='nombre'>Nombre:		</label> 
<input type='text' id='nombre' name ='nombre'/>
</div>

<div>
<label for='precioA'>Precio:		</label> 
<input type='text' id='precioA' name ='precioA'/>
</div>

<div>
<label for='valoracion'>Valoracion [0-10]:		</label> 
<input type='text' id='valoracion' name ='valoracion'/>
</div>

<input type='submit' class='button' name ='insertarAgencia' value='Insertar'/>

<h2>Insertar un Alquiler</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='id_agenciaA'>Identificado Agencia:		</label> 
<input type='text' id='id_agenciaA' name ='id_agenciaA'/>
</div>

<div>
<label for='matriculaA'>Matricula:		</label> 
<input type='text' id='matriculaA' name ='matriculaA'/>
</div>

<div>
<label for='dias'>Dias:		</label> 
<input type='text' id='dias' name ='dias'/>
</div>
<input type='submit' class='button' name ='insertaAlquileres' value='Insertar'/>

<h2>Busca un Coche</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='matriculaB'>Matricula:		</label> 
<input type='text' id='matriculaB' name ='matriculaB'/>
</div>
<input type='submit' class='button' name ='buscarCoche' value='Buscar'/>

<h2>Busca una Agencia</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='id_agenciaB'>Identificador:		</label> 
<input type='text' id='id_agenciaB' name ='id_agenciaB'/>
</div>
<input type='submit' class='button' name ='buscarAgencias' value='Buscar'/>

<h2>Busca un Alquiler</h2>
<p class='info'>Rellena los datos</p>
<div>
<label for='id_agenciaBA'>Identificador:		</label> 
<input type='text' id='id_agenciaBA' name ='id_agenciaBA'/>
</div>
<div>
<label for='matriculaBA'>Matricula:		</label> 
<input type='text' id='matriculaBA' name ='matriculaBA'/>
</div>
<input type='submit' class='button' name ='buscarAlquileres' value='Buscar'/>
<h2>Exportar datos de la base de datos</h2>
<input type='submit' class='button' name ='exportar' value='Exportar'/>

<h2>Carga tus datos</h2>
<label for='datos'>Selecciona tu archivo:		</label> 
<input type="file" id="datos" name="datos" /> 
<input type='submit' class='button' name ='cargar' value='Cargar'/>
</form>
</section>
</body>
</html>
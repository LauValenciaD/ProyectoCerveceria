<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Procesado</title>
</head>
<body>
    <?php
 echo "<h1> Inserción de Cervezas </h1>";
    if (isset($_REQUEST["embase"]) && isset($_REQUEST["marca"]) && isset($_REQUEST["advertencia"]) && isset($_REQUEST["fecha"]) && isset($_REQUEST["alergenos"])) {
$cerveza = $_REQUEST["cerveza"];
$nombre = $_REQUEST["denominacion"];
$embase = $_POST["embase"];
$cantidad = $_REQUEST["cantidad"];
$marca = $_REQUEST["marca"];
$advertencia = $_REQUEST["advertencia"];
$fecha = $_REQUEST["fecha"];
$observaciones = $_REQUEST["observaciones"];



if (isset($_POST['alergenos'])) {
    // Verificamos si es un array
    if (is_array($_POST['alergenos'])) {
        $opcionesSeleccionadas = $_POST['alergenos'];
    } else {
        // Si solo hay una opción seleccionada, convertirla en un array
        $opcionesSeleccionadas = [$_POST['alergenos']];
    }

    // Mostrar los valores seleccionados
    echo "Nombre: $cerveza <br> Denominación: $nombre <br> Embase: $embase <br> Cantidad: $cantidad <br> Marca: $marca <br>";
    echo "Has seleccionado los siguientes alérgenos:<br>";
    foreach ($opcionesSeleccionadas as $opcion) {
        echo htmlspecialchars($opcion) . "<br>";
    }
    echo "Advertencia: $advertencia <br> Fecha: $fecha <br> Observaciones: $observaciones <br>";
    comprobarImg2();
 

 


 } else{
    echo "No se ha podido realizar la insercción debido a los siguientes errores:";
    echo "<ul>";
    if($_REQUEST["marca"] == ""){
        echo "<li>Se requiere Marca</li>";
    }
    if($_REQUEST["advertencia"] == ""){
        echo "<li>Es obligatoria la advertencia sobre el abuso del consumo de alcohol</li>";
    }
    if($_REQUEST["fecha"] == ""){
        echo "<li>No ha introducido fecha</li>";
    }
    if(!isset($_REQUEST["alergenos"])){
        echo "<li>Es obligatorio incluir alergenos</li>";
    }

    echo "</ul>";
}
echo "[<a href='formulario2.html'> Volver </a>]";

}
function comprobarImg(){
    //if (isset($_FILES['foto'])){
    $errores = $_FILES['foto']['error'];
    if ($errores !==0)
    {
        echo "<br> <strong> Hay un error en la imagen o falta subir la imagen. </strong> El error es $errores .<br>";
    }
else {
    $nombre= $_FILES['foto']['name'];
    $tamanio= $_FILES['foto']['size'];
    $tipo= $_FILES['foto']['type'];
    $origen= $_FILES['foto']['tmp_name'];



    if($tamanio > 1000000) {
        echo "La imagen es demasiado grande. <br>";
    }
    if($tipo !== "image/jpeg" || $tipo !== "image/png")
    {
        echo "La imagen debe ser jpg o png. <br>";
        echo $tipo;
    } else{
  
    $destino= "/cursophp/cerveceria/archivos/" . $nombre;
    move_uploaded_file($origen, $destino);
    echo "La imagen fue subida correctamente. <br>"; 
    echo   "<img src=$destino alt='Imagen subida'>";
}}

/*} else {
    echo "No has subido na de na.";} */
}

function comprobarImg2(){
    // Verificar si hay errores en la subida del archivo
    $errores = $_FILES['foto']['error'];
    if ($errores !== 0) {
        echo "<br> <strong> Hay un error en la imagen o falta subir la imagen. </strong> El error es $errores .<br>";
    } else {
        // Obtener información del archivo
        $nombre = $_FILES['foto']['name'];
        $tamanio = $_FILES['foto']['size'];
        $tipo = $_FILES['foto']['type'];
        $origen = $_FILES['foto']['tmp_name'];

        // Verificar tamaño (1MB = 1,000,000 bytes)
        if ($tamanio > 1000000) {
            echo "La imagen es demasiado grande. <br>";
        }

        // Verificar tipo de archivo
        if ($tipo !== "image/jpeg" && $tipo !== "image/png") {
            echo "La imagen debe ser jpg o png. <br>";
            echo "Tipo de archivo: $tipo <br>";
        } else {
            // Definir la ruta de destino relativa
            $destino = "archivos/" . $nombre;  // Ruta relativa a la carpeta "archivos"

            // Mover el archivo a la ruta de destino
            if (move_uploaded_file($origen, $destino)) {
                echo "La imagen fue subida correctamente. <br>";
                // Mostrar la imagen subida usando la ruta relativa
                echo "<img src='$destino' alt='Imagen subida'>";
            } else {
                echo "Error al mover el archivo a la carpeta de destino. <br>";
            }
        }
    }
}

?>
</body>
</html>
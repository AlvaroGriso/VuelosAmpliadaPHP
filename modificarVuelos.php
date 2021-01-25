
<?php

include "conexion.php";

if (isset($_GET['CODIGO_VUELO']) && isset($_GET['campoModificar']) && isset($_GET['nuevoValor']))  {
    $codigoVuelo = $_GET['CODIGO_VUELO'];
    $campoModificar = $_GET['campoModificar'];
    $nuevoValor = $_GET['nuevoValor'];
    $sql = "UPDATE vuelos SET $campoModificar = '$nuevoValor' WHERE CODIGO_VUELO = '$codigoVuelo'";
    $conn ->query($sql);

}

?>

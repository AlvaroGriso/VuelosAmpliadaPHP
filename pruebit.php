<?php
require 'vendor/autoload.php'; // include Composer goodies

$cliente = new MongoDB\Client("mongodb://localhost:27017");
$coleccion = $cliente->VuelosAmpliada->vuelos2_0;

$resultado = $coleccion->find();

foreach ($resultado as $entry) {
    echo $entry['codigo'], ': ', $entry['origen'], ' -> ',$entry['destino'], "\n";
}

?>
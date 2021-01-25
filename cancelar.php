<?php

require 'vendor/autoload.php'; // include Composer goodies

try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->VuelosAmpliada->vuelos2_0;

    $codigo = $_GET['codigo'];
    $dni = $_GET['dni'];
    $codigoVenta = $_GET['codigoVenta'];

    $resultado = $coleccion->find([
        'codigo' => $codigo
    ]);

    $vendidos = $resultado->toArray()[0]['vendidos'];

    for ($i = 0; $i < count($vendidos); $i++) {
        if ($vendidos[$i]['dni'] == $dni && $vendidos[$i]['codigoVenta'] == $codigoVenta) {

            $aux = $vendidos[$i];

            $coleccion->updateOne(array("codigo"=> $codigo),array('$pull' => array("vendidos" => $aux)));

            echo "Eliminado el cliente";
        }
    }


} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}
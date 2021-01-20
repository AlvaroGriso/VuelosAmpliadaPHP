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

    //print_r($vendidos);

    for ($i = 0; $i < count($vendidos); $i++) {
        if ($vendidos[$i]['dni'] == $dni && $vendidos[$i]['codigoVenta'] == $codigoVenta) {
            //print_r($vendidos[$i]['codigoVenta']);
            //$coleccion->deleteOne(array('codigo' => $codigo,'vendidos.codigoVenta'=> $vendidos[$i]['codigoVenta']));

            //$coleccion->updateOne(array("codigo" => $codigo), array('$pull' => array('vendidos' => "")));
            $bbsita = $vendidos[$i];

            $coleccion->updateOne(array("codigo"=> $codigo),array('$pull' => array("vendidos" => $bbsita)));

            echo "Eliminado el cliente";
            echo die();
        }
    }


} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}
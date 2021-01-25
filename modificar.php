<?php

require 'vendor/autoload.php'; // include Composer goodies

try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->VuelosAmpliado->vuelos2_0;

    $codigoVuelo = $_GET['codigo'];
    $dni = $_GET['dni'];
    $codigoVenta = $_GET['codigoVenta'];
    $dniPagador = $_GET['dniPagador'];
    $apellido = $_GET['apellido'];
    $nombre = $_GET['nombre'];

    $query = array('$and' => array(array('codigo' => $codigoVuelo), array('vendidos.codigoVenta' => $codigoVenta)));
    $resultado = $coleccion->find($query);
    foreach ($resultado->toArray() as $vuelo) {
      for ($i = 1; $i < sizeof($vuelo["vendidos"]); $i++) {
        $arrayVendidos = $vuelo["vendidos"][$i];
        if ($vuelo["vendidos"][$i]["codigoVenta"] == $codigoVenta) {
          $asientoEncontrado = $i;
          $numAsiento = $vuelo["vendidos"][$i]['asiento'];
          $tarjeta = $vuelo["vendidos"][$i]["tarjeta"];
        }
      }
    }

    $nuevoAsiento = array('asiento' => $numAsiento, 'dni' => $dni, 'apellido' => $apellido, 'nombre' => $nombre, 'dniPagador' => $dniPagador, 'tarjeta' => $tarjeta, 'codigoVenta' => $codigoVenta);
    $resultado = $coleccion->updateOne(array("codigo"=> $codigoVuelo),array('$set' => array("vendidos.$asientoEncontrado" => $nuevoAsiento)));
    $query = array('codigo' => $codigoVuelo);
    $resultado = $coleccion->find($query);

    $estado = false;
    $estado = true;

    $list_vuelos[] = array();

    $arrayFinal = array(
        "estado" => $estado,
        "hola" => $resultado


    );

    header("Content-type:application/json");
    echo json_encode($arrayFinal);

} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}

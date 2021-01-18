<?php

require 'vendor/autoload.php'; // include Composer goodies

try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->VuelosAmpliada->vuelos2_0;

    $fecha = $_GET['fecha'];
    $origen = $_GET['origen'];
    $destino = $_GET['destino'];

    $query = array('origen' => $origen, 'fecha' => $fecha, 'destino' => $destino);
    $resultado = $coleccion->find($query);

    $estado = false;
    $busqueda[] = [];
    $estado = true;
    $busqueda = ['fecha' => $fecha, 'origen' => $origen, 'destino' => $destino];

    $count = 0;
    $list_vuelos[] = array();

    foreach ($resultado->toArray() as $vuelo) {

        $array = array(
            "codigo" => $vuelo["codigo"],
            "origen" => $vuelo["origen"],
            "destino" => $vuelo["destino"],
            "fecha" => $vuelo["fecha"],
            "hora" => $vuelo["hora"],
            "plazas_totales" => $vuelo["plazas_totales"],
            "plazas_disponibles" => $vuelo["plazas_disponibles"]
        );

        $list_vuelos[$count] = $array;

        $count++;
    }

    $arrayFinal = array(
        "estado" => $estado,
        "encontrado" => $count,
        "busqueda" => $busqueda,
        "vuelos" => $list_vuelos
    );

    header("Content-type:application/json");
    echo json_encode($arrayFinal);

} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}




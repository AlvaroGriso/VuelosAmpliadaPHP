<?php

require 'vendor/autoload.php'; // include Composer goodies
if (is_array($_POST)) {

    try {
        $cliente = new MongoDB\Client("mongodb://localhost:27017");
        $coleccion = $cliente->VuelosAmpliada->vuelos2_0;

        $query = array();

        if (isset($_GET['fecha'])) {
            $fecha = $_GET['fecha'];
            $query['fecha'] = $fecha;
        }

        if (isset($_GET['origen'])) {
            $origen = $_GET['origen'];
            $query['origen'] = $origen;
        }

        if (isset($_GET['destino'])) {
            $destino = $_GET['destino'];
            $query['destino'] = $destino;
        }

        $resultado = $coleccion->find($query);

        $estado = false;
        $busqueda[] = [];

        $busqueda = $query;

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

        $estado = false;

        if ($count > 0) {
            $estado = true;
        }

        $arrayFinal = array(
            "estado" => $estado,
            "encontrados" => $count,
            "busqueda" => $busqueda,
            "vuelos" => $list_vuelos
        );

        $miVariable = json_encode($arrayFinal, JSON_PRETTY_PRINT);
        echo $miVariable;

    } catch (MongoDB\Driver\Exception\Exception $e) {
        print_r($e);
    }
}


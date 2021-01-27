<?php

require 'vendor/autoload.php'; // include Composer goodies
$recibido = file_get_contents('php://input');
$primerCaracter = substr($recibido,0,1);
if($primerCaracter == '{'){
    $_GET =json_decode($recibido,true);

}else{
    parse_str($recibido,$_GET);
}
try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->VuelosAmpliada->vuelos2_0;

    $codigoVuelo = $_GET['codigo'];
    $dni = $_GET['dni'];
    $codigoVenta = $_GET['codigoVenta'];
    $dniPagador = $_GET['dniPagador'];
    $apellido = $_GET['apellido'];
    $nombre = $_GET['nombre'];



    $dniNuevo =$_GET['dniNuevo'];

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


    $resultado = $coleccion->updateOne(array("codigo"=> $codigoVuelo,"vendidos.dni"=>$dni,"vendidos.codigoVenta" =>$codigoVenta),

        array('$set' => array(
           // "vendidos.$.$asientoEncontrado" => $asientoEncontrado,
            "vendidos.$.dni" => $dniNuevo,
            "vendidos.$.nombre" => $nombre,
            "vendidos.$.apellido" => $apellido
        )));
   // $query = array('codigo' => $codigoVuelo);
   // $resultado = $coleccion->find($query);

    $estado = false;
    $estado = true;

    //$list_vuelos[] = array();

    $arrayFinal = array(
        "estado" => $estado,
        "vuelos" => $resultado


    );

    header("Content-type:application/json");
    echo json_encode($arrayFinal);

} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}


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
    $apellido = $_GET['apellido'];
    $nombre = $_GET['nombre'];
    $dniPagador = $_GET['dniPagador'];
    $tarjeta = $_GET['tarjeta'];


    //  $coleccion->insert([ 'codigo' => $codigoVuelo, 'vendidos' => [ 'asiento' => '3', 'dni'=> $dni, 'apellido'=>$apellido, 'nombre'=>$nombre, 'dniPagador'=>$dniPagador, 'tarjeta'=> $tarjeta, 'codigoVenta'=>"Wonki" ]]);
    //$coleccion->update(array('codigo' => $codigoVuelo),
    //             array('$push' => array('questions' => array('vendidos' => '2'))));

    $codigoVenta = RandomCodigoVenta();
    $costeBillete = (int)RandomPrecioBillete();

    $query = array('codigo' => $codigoVuelo);
    $resultado = $coleccion->find($query);
    foreach ($resultado->toArray() as $vuelo) {
        $numAsiento = sizeof($vuelo["vendidos"]);
    }

    $nuevoAsiento = array('asiento' => $numAsiento + 1, 'dni' => $dni, 'apellido' => $apellido, 'nombre' => $nombre, 'dniPagador' => $dniPagador, 'tarjeta' => $tarjeta, 'codigoVenta' => $codigoVenta, 'costeBillette' => $costeBillete);
    $resultado = $coleccion->updateOne(array("codigo"=> $codigoVuelo),array('$push' => array("vendidos" => $nuevoAsiento)));
    $query = array('codigo' => $codigoVuelo);
    $resultado = $coleccion->find($query);

    $estado = false;
    $estado = true;


$list_vuelos [] = [];

    foreach ($resultado->toArray() as $vuelo) {
        $vendidos = $vuelo["vendidos"][$numAsiento];
        $list_vuelos = array(
            "estado" => $estado,
            "codigo" => $vuelo["codigo"],
            "origen" => $vuelo["origen"],
            "destino" => $vuelo["destino"],
            "fecha" => $vuelo["fecha"],
            "hora" => $vuelo["hora"],
                "asiento" => $vendidos["asiento"],
                "dni" => $vendidos["dni"],
                "apellido" => $vendidos["apellido"],
                "nombre" => $vendidos["nombre"],
                "dniPagador" => $vendidos["dniPagador"],
                "tarjeta" => $vendidos ["tarjeta"],
                "codigoVenta" => $vendidos["codigoVenta"],
                "costeBillette" => $vendidos["costeBillette"]
        );


    }



    $miVariable = json_encode($list_vuelos, JSON_PRETTY_PRINT);
    echo $miVariable;

} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}

function RandomCodigoVenta(){
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomCodigoVenta = '';
    for ($i = 0; $i < 9; $i++) {
        $randomCodigoVenta .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCodigoVenta;
}

function RandomPrecioBillete(){
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomPrecio = '';
    for ($i = 0; $i < 3; $i++) {
        $randomPrecio .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPrecio;
}


?>


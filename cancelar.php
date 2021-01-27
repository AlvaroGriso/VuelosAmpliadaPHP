<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
require 'vendor/autoload.php'; // include Composer goodies
$recibido = file_get_contents('php://input');

$jsRecibido =json_decode($recibido,true);

try {
    $cliente = new MongoDB\Client("mongodb://localhost:27017");
    $coleccion = $cliente->VuelosAmpliada->vuelos2_0;

    $codigo = $jsRecibido['codigo'];
    $dni = $jsRecibido['dni'];
    $codigoVenta = $jsRecibido['codigoVenta'];

    $resultado = $coleccion->find([
        'codigo' => $codigo
    ]);

    $vendidos = $resultado->toArray()[0]['vendidos'];

    for ($i = 0; $i < count($vendidos); $i++) {
        if ($vendidos[$i]['dni'] == $dni && $vendidos[$i]['codigoVenta'] == $codigoVenta) {

            $aux = $vendidos[$i];

            $coleccion->updateOne(array("codigo"=> $codigo),array('$pull' => array("vendidos" => $aux)));
            $msgFinal = json_encode(array("mensaje"=>"Eliminado el cliente", "estado"=>true), JSON_PRETTY_PRINT);
            echo $msgFinal;
        }
    }


} catch (MongoDB\Driver\Exception\Exception $e) {
    print_r($e);
}
?>
<?php
require 'vendor/autoload.php';

$cliente = new MongoDB\Client("mongodb://localhost:27017");
$coleccion = $cliente->VuelosAmpliada->vuelos2_0;

$codigo = "IB706";
$dni = "44556677H";
$codigoVenta = "GHJ7766GG";

$nuevoDni = "Y4904844Q";
$nuevoNombre = "OLIVER";
$nuevoApellido = "BENSHI";

$query= array('codigo'=>$codigo);

//$queryModificacion = array('dni'=>$nuevoDni,'nombre'=>$nuevoNombre,'apellido'=>$nuevoApellido);

$resultado = $coleccion->find($query);

$list_vuelos[] = array();
$count = 0;




foreach ($resultado->toArray() as $vuelo) {
    foreach ($vuelo['vendidos'] as $vendidos) {
        if ($vendidos["dni"] == $dni) {

            $vendidos["dni"] = $nuevoDni;
            $vendidos["nombre"] = $nuevoNombre;
            $vendidos["apellido"] = $nuevoApellido;

            $coleccion->updateOne(
                ['codigo' =>$codigo],
                [ '$set' => array('vendidos.$.dni'=> $vendidos)]
            );
        }
    }

    $list_vuelos= $vuelo;
    $count++;
}

$miVariable = json_encode($list_vuelos, JSON_PRETTY_PRINT);
echo $miVariable;

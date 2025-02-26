<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require 'vendor/autoload.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        require 'billetes.php';
        break;
    case 'POST':
        require 'comprar.php';
        break;
    case 'DELETE':
        require 'cancelar.php';
        break;
    case 'PUT':
        require 'modificar.php';
        break;
   };

?>
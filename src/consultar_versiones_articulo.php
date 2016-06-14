<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $id_articulo = filter_input(INPUT_POST, "idArticulo");
    $resultados = array();
    $articulos = ApiBd::obtener_historial_articulos($id_articulo);
    $resultados["status"] = "ok";
    $resultados["articulos"] = $articulos;
    echo json_encode($resultados);
}


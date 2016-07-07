<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $id_version = filter_input(INPUT_POST, "id_version");
    $id_articulo = filter_input(INPUT_POST, "id_articulo");
    $resultados = array();
    $resultados["status"] = "no";
    if(isset($id_version) && isset($id_articulo)){
        $articulo_version = ApiBd::obtener_version_articulo($id_version, $id_articulo);
        $resultados["status"] = "ok";
        $resultados["version"] = $articulo_version["version"];
        $resultados["titulo"] = $articulo_version["titulo"];
    }
    echo json_encode($resultados);
}


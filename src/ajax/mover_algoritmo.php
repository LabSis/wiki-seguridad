<?php
require_once '../../config.php';

$sesion = Session::get_instance();
$respuesta = array();

$respuesta['estado'] = "error";
$respuesta['mensaje'] = "";


try {
    if (!$sesion->is_active()) {
        throw new EditarDatosUsuarioException("La sesión no está activa");
    }

    $id_algoritmo = filter_input(INPUT_POST, 'id_algoritmo', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_nuevo_padre = filter_input(INPUT_POST, 'id_nuevo_padre', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($id_algoritmo) || !isset($id_nuevo_padre)){
        throw new Exception("Error en los parámetros de entrada");
    }

    ApiBd::mover_algoritmo($id_algoritmo, $id_nuevo_padre);
    
    $respuesta["estado"] = "ok";
}
catch (Exception $e){
    $respuesta['estado'] = "error";
    if (DEV_MODE){
        $respuesta['mensaje'] = $e->getMessage();
    }
    else {
        $respuesta['mensaje'] = "Ha ocurrido un error";
    }
}

echo json_encode($respuesta);

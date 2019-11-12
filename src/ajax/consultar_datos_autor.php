<?php
require_once '../../config.php';

$sesion = Session::get_instance();
$respuesta = array();

$respuesta['estado'] = "error";
$respuesta['mensaje'] = "";

if ($sesion->is_active()) {
    $nombre_usuario = $sesion->get_user()->get_name();
    $autor = ApiBd::consultar_autor_por_usuario($nombre_usuario);

    $respuesta["datos"] = array(
        "autor" => $autor
    );
    $respuesta["estado"] = "ok";

}
else {
    $respuesta['mensaje'] = "La sesión no está activa";
}

echo json_encode($respuesta);
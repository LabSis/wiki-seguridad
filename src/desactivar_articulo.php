<?php
require_once '../config.php';

$sesion = Sesion::get_instancia();
$sesion->limpiar_mensajes();


$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {

    $id_tecnica = filter_input(INPUT_POST, "id_tecnica");
    $id_articulo = trim(filter_input(INPUT_POST, "id_articulo"));
    $ok = true;
    if (isset($id_tecnica) && isset($id_articulo)) {
        $ok = ApiBd::desactivar_articulo($id_articulo);
    } else {
        $ok = false;
    }
    if ($ok) {
        $sesion->cargar_mensaje("El artículo fue borrado con éxito", Sesion::TIPO_MENSAJE_EXITO);
    } else {
        $sesion->cargar_mensaje("Error al borrar el artículo", Sesion::TIPO_MENSAJE_ERROR);
    }
    header("Location: tecnica.php?id=$id_tecnica");
}


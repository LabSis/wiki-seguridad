<?php
require_once '../config.php';

$sesion = Session::get_instance();

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
        $sesion->add_success_message("El artículo fue borrado con éxito");
    } else {
        $sesion->add_error_message("Error al borrar el artículo");
    }
    header("Location: tecnica.php?id=$id_tecnica");
}


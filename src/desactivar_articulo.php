<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $id_contenedor = filter_input(INPUT_POST, "id_contenedor");
    $tipo = filter_input(INPUT_POST, "tipo");
    $id_articulo = trim(filter_input(INPUT_POST, "id_articulo"));
    $ok = true;
    if (isset($id_contenedor) && isset($id_articulo)) {
        $ok = ApiBd::desactivar_articulo($id_articulo);
    } else {
        $ok = false;
    }
    if ($ok) {
        $sesion->add_success_message("El artículo fue borrado con éxito");
    } else {
        $sesion->add_error_message("Error al borrar el artículo");
    }
    if ($tipo === "tecnica") {
        header("Location: contenedor.php?id=$id_contenedor&tipo=tecnica");
    } else if ($tipo === "vulnerabilidad") {
        header("Location: contenedor.php?id=$id_contenedor&tipo=vulnerabilidad");
    } else {
        echo "Error.";
    }
} else {
    echo "Error.";
}

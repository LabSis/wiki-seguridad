<?php
require_once '../config.php';

$sesion = Session::get_instance();

$id_tecnica = $_GET["id"];
$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $hubo_error = false;
    // Guardar sección o artículo...
    $titulo = filter_input(INPUT_POST, "txtTitulo", FILTER_SANITIZE_STRING);
    $titulo = trim($titulo);
    if (strlen($titulo) === 0) {
        $sesion->add_error_message("El título no puede ser vacío");
        $hubo_error = true;
    }

    $contenido = filter_input(INPUT_POST, "txtContenido");
    $contenido = trim($contenido);
    if (strlen($contenido) === 0) {
        $sesion->add_error_message("El contenido no puede ser vacío");
        $hubo_error = true;
    }
    if (!$hubo_error) {
        $ok = ApiBd::crear_articulo($titulo, $id_tecnica, $contenido);
        if ($ok){
            $sesion->add_success_message("El artículo fue guardado con éxito");
        } else {
            $sesion->add_error_message("Hubo un error al guardar el artículo");
        }
    }
}
header("Location: tecnica.php?id=$id_tecnica");

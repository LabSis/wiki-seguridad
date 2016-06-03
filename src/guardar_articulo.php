<?php
require_once '../config.php';

$sesion = Sesion::get_instancia();
$sesion->limpiar_mensajes();

$id_tecnica = $_GET["id"];
$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $hubo_error = false;
    // Guardar sección o artículo...
    $titulo = filter_input(INPUT_POST, "txtTitulo", FILTER_SANITIZE_STRING);
    $titulo = trim($titulo);
    if (strlen($titulo) === 0) {
        $sesion->cargar_mensaje("El título no puede ser vacío", Sesion::TIPO_MENSAJE_ERROR);
        $hubo_error = true;
    }

    $contenido = filter_input(INPUT_POST, "txtContenido");
    $contenido = trim($contenido);
    if (strlen($contenido) === 0) {
        $sesion->cargar_mensaje("El contenido no puede ser vacío", Sesion::TIPO_MENSAJE_ERROR);
        $hubo_error = true;
    }
    if (!$hubo_error) {
        $ok = ApiBd::crear_articulo($titulo, $id_tecnica, $contenido);
        if ($ok){
            $sesion->cargar_mensaje("El artículo fue guardado con éxito", Sesion::TIPO_MENSAJE_EXITO);
        } else {
            $sesion->cargar_mensaje("Hubo un error al guardar el artículo", Sesion::TIPO_MENSAJE_ERROR);
        }
    }
}
header("Location: tecnica.php?id=$id_tecnica");

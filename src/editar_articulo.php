<?php
require_once '../config.php';

$sesion = Sesion::get_instancia();
$sesion->limpiar_mensajes();


$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {


    $id_tecnica = filter_input(INPUT_POST, "hidIdTecnicaModalEditar");
    $id_articulo = filter_input(INPUT_POST, "hidIdArticuloModalEditar");
    $txt_titulo = filter_input(INPUT_POST, "txtTituloModalEditar");
    $txt_contenido = trim(filter_input(INPUT_POST, "txtContenidoModalEditar"));
    $ok = true;
    if (isset($id_tecnica) && isset($id_articulo) && isset($txt_titulo) && isset($txt_contenido)) {
        $ok = ApiBd::editar_articulo($id_articulo, $txt_titulo, $txt_contenido);
    } else {
        $ok = false;
    }
    if ($ok) {
        $sesion->cargar_mensaje("El artículo fue editado con éxito", Sesion::TIPO_MENSAJE_EXITO);
    } else {
        $sesion->cargar_mensaje("Error al editar el artículo", Sesion::TIPO_MENSAJE_ERROR);
    }
    //header("Location: tecnica.php?id=$id_tecnica");
}


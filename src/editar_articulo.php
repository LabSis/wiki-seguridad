<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $id_contenedor = filter_input(INPUT_POST, "id_contenedor");
    $tipo = filter_input(INPUT_POST, "tipo");
    if ($sesion->is_active()) {
        $id_articulo = filter_input(INPUT_POST, "hidIdArticuloModalEditar");
        $txt_titulo = filter_input(INPUT_POST, "txtTituloModalEditar");
        $txt_contenido = trim(filter_input(INPUT_POST, "txtContenidoModalEditar"));
        $ok = true;
        if (isset($id_contenedor) && isset($id_articulo) && isset($txt_titulo) && isset($txt_contenido)) {
            $id_autor = ApiBd::consultar_autor_por_usuario($sesion->get_user()->get_name())['id']; // en caso de que el autor sea obligatorio verificar acá si es null o no
            $ok = ApiBd::editar_articulo($id_articulo, $txt_titulo, $txt_contenido, $id_autor);
        } else {
            $ok = false;
        }
    } else {
        $ok = false;
    }
    if ($ok) {
        $sesion->add_success_message("El artículo fue editado con éxito");
    } else {
        $sesion->add_success_message("Error al editar el artículo");
    }
    if ($tipo === "tecnica") {
        $sesion->redirect("contenedor.php?id=$id_contenedor&tipo=tecnica");
    } else if ($tipo === "vulnerabilidad") {
        $sesion->redirect("contenedor.php?id=$id_contenedor&tipo=vulnerabilidad");
    } else if ($tipo === "algoritmo") {
        $sesion->redirect("contenedor.php?id=$id_contenedor&tipo=algoritmo");
    } else {
        echo "Error.";
    }
} else {
    echo "Error.";
}

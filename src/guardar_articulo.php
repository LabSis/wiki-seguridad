<?php
require_once '../config.php';

$sesion = Session::get_instance();

try {
    $id_contenedor = filter_input(INPUT_GET, "id_contenedor");
    $metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");


    if (strcasecmp($metodo, "POST") === 0) {
        if ($sesion->is_active()) {
            $hubo_error = false;
            // Guardar sección o artículo...
            $tipo = filter_input(INPUT_POST, "tipo");
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



        } else {
            $hubo_error = true;
        }

        if (!$hubo_error) {
            $id_autor = ApiBd::consultar_autor_por_usuario($sesion->get_user()->get_name())['id']; // en caso de que el autor sea obligatorio verificar acá si es null o no

            $ok = ApiBd::crear_articulo($titulo, $id_contenedor, $contenido, $tipo, $id_autor);
            if ($ok){
                $sesion->add_success_message("El artículo fue guardado con éxito");
            } else {
                $sesion->add_error_message("Hubo un error al guardar el artículo");
            }
        }
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
} catch (\InvalidArgumentException $ex) {
    $sesion->add_error_message($ex->getMessage());
    echo "Error.";
}

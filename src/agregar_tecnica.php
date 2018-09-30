<?php
require_once '../config.php';

ini_set("display", "errors");

$sesion = Session::get_instance();

try {
    $metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if (strcasecmp($metodo, "POST") === 0) {
        $hubo_error = false;
        if ($sesion->is_active()) {
            $nombre_tecnica = filter_input(INPUT_POST, "txtTechniqueName", FILTER_SANITIZE_STRING);
            $nombre_tecnica = trim($nombre_tecnica);
            if (strlen($nombre_tecnica) === 0) {
                $sesion->add_error_message("El nombre de la técnica no puede ser vacío");
                $hubo_error = true;
            }

            $id_padre = filter_input(INPUT_POST, "hidTechniqueParentId", FILTER_SANITIZE_STRING);
            if (!is_numeric($id_padre)) {
                $sesion->add_error_message("El id_padre no es un número o un string numérico");
                $hubo_error = true;
            }
        } else {
            $hubo_error = true;
        }

        if (!$hubo_error) {
            // Registrar técnica
            $ok = ApiBd::crear_tecnica($nombre_tecnica, $id_padre);
            if ($ok){
                $sesion->add_success_message("El artículo fue guardado con éxito");
            } else {
                $sesion->add_error_message("Hubo un error al guardar el artículo");
            }
        }
    }
    $sesion->redirect($WEB_PATH);
} catch (\InvalidArgumentException $ex) {
    $sesion->add_error_message($ex->getMessage());
    echo "Error.";
}

<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    if ($sesion->is_active()) {
        $id_vulnerabilidad = filter_input(INPUT_POST, "id_vulnerabilidad");
        $etapa = filter_input(INPUT_POST, "etapa");
        $cantidad = filter_input(INPUT_POST, "cantidad");
        $ok = true;
        if (isset($id_vulnerabilidad) && isset($etapa) && isset($cantidad)) {
            $ok = ApiBd::cambiar_cantidad_vulnerabilidad($id_vulnerabilidad, $etapa, $cantidad);
        } else {
            $ok = false;
        }
        if ($ok) {
            $sesion->add_success_message("La cantidad fue cambiada exitosamente.");
            echo "Ok";
        } else {
            $sesion->add_error_message("Error al cambiar la cantidad.");
            echo "Error";
        }
    } else {
        echo "Error";
    }
} else {
    echo "Error";
}

<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $usuario = filter_input(INPUT_POST, "usuario");
    $clave = filter_input(INPUT_POST, "clave");
    $ok = true;
    if (isset($usuario) && isset($clave)) {
        $ok = ApiBd::iniciar_sesion($usuario, $clave);
        $usuario = new Usuario();
        $sesion->log_in($usuario);
    } else {
        $ok = false;
    }
    if ($ok) {
        $sesion->add_success_message("Iniciado sesión exitosamente.");
        echo "Ok";
    } else {
        $sesion->add_error_message("Error al iniciar sesión.");
        echo "Error";
    }
} else {
    echo "Error";
}

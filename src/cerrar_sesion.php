<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    if ($sesion->is_active()) {
        $sesion->log_out();
    }
    echo "Ok";
} else {
    echo "Error";
}

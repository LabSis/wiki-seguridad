<?php
require_once '../config.php';


$session = Session::get_instance();

$method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($method, "POST") === 0) {
    $technique_id = filter_input(INPUT_POST, "hidTechniqueId");
    $technique_name = filter_input(INPUT_POST, "txtTechniqueName");

    $ok = true;
    if (isset($technique_id) && isset($technique_name)) {
        $ok = ApiBd::edit_technique($technique_name, $technique_id);
    } else {
        $ok = false;
    }
    if ($ok) {
        $session->add_success_message("La técnica fue actualizada con éxito");
    } else {
        $session->add_success_message("Error al actualizar la técnica");
    }
    header("Location: ../index.php");
}


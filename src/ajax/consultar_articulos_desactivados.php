<?php
require_once '../../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    $id_tecnica = trim(filter_input(INPUT_POST, "id_tecnica"));
    $tecnicas = array();
    if(isset($id_tecnica)){
        $tecnicas = ApiBd::consultar_articulos_desactivados($id_tecnica);
    }
    echo json_encode($tecnicas);
}


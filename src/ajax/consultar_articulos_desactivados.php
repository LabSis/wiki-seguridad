<?php
require_once '../../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {
    if ($sesion->is_active()) {
        $id_tecnica = trim(filter_input(INPUT_POST, "id_tecnica"));
        $respuesta = array();
        if(isset($id_tecnica)){
            $articulos_respuesta = ApiBd::consultar_articulos_desactivados($id_tecnica);
            $articulos = array();
            foreach($articulos_respuesta as $articulo){
                $art = ApiBd::obtener_anteultima_version($articulo["id"]);
                $articulo["contenido"] = $art["contenido"];
                $articulos[] = $articulo;
            }
            $respuesta["articulos"] = $articulos;
            $respuesta["status"] = "ok";
        } else {
            $respuesta["status"] = "no";
        }
    } else {
        $respuesta["status"] = "no";
    }
    echo json_encode($respuesta);
}


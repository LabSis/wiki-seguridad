<?php

class ApiBd {

    /**
     *
     * @var \Conexion
     */
    private static $conexion;

    private static function iniciar() {
        if (!isset(self::$conexion)) {
            self::$conexion = Conexion::get_instancia();
        }
        if (!self::$conexion->esta_conectado()) {
            self::$conexion->conectar();
        }
    }

    private static function cerrar() {
        if (isset(self::$conexion)) {
            self::$conexion->cerrar();
        }
    }

    public static function sanitizar($cadena) {
        $mysqli = self::$conexion->get_mysqli();
        $cadena = mysqli_real_escape_string($mysqli, $cadena);
        return strip_tags($cadena, "<a><h1><h2><h3><h4><h5><h6>");
    }

    public static function obtener_tecnicas() {
        self::iniciar();
        $consulta = "SELECT id, nombre, id_padre FROM tecnicas WHERE id_padre IS NULL";
        $tecnicas = self::$conexion->consultar_simple($consulta);
        $o_tecnicas = array();
        foreach ($tecnicas as $tecnica) {
            $consulta = "SELECT id, nombre, id_padre FROM tecnicas WHERE id_padre = {$tecnica["id"]}";
            $subtecnicas = self::$conexion->consultar_simple($consulta);
            $o_tecnica = array(
                "nombre" => $tecnica["nombre"],
                "id" => $tecnica["id"]
            );
            $links = array();
            foreach ($subtecnicas as $subtecnica) {
                $links[] = array(
                    "href" => $subtecnica["id"],
                    "nombre" => $subtecnica["nombre"]
                );
            }
            $o_tecnica["links"] = $links;
            $o_tecnicas[] = $o_tecnica;
        }
        self::cerrar();
        return $o_tecnicas;
    }

    public static function obtener_tecnicas_raiz() {
        self::iniciar();
        $consulta = "SELECT id, nombre, id_padre FROM tecnicas WHERE id_padre IS NULL";
        $tecnicas = self::$conexion->consultar_simple($consulta);
        $o_tecnicas = array();
        foreach ($tecnicas as $tecnica) {
            $o_tecnicas[] = array(
                "nombre" => $tecnica["nombre"],
                "id" => $tecnica["id"]
            );
        }
        return $o_tecnicas;
    }

    public static function obtener_tecnica($id_tecnica) {
        self::iniciar();
        $consulta = "SELECT id, nombre FROM tecnicas WHERE id={$id_tecnica}";
        $tecnica = self::$conexion->consultar_simple($consulta);
        $o_tecnica = array(
            "nombre" => $tecnica[0]["nombre"]
        );
        $consulta = "SELECT nombre, contenido FROM articulos WHERE id_tecnica={$id_tecnica}";
        $articulos = self::$conexion->consultar_simple($consulta);
        $o_articulos = array();
        foreach ($articulos as $articulo) {
            $o_articulos[] = array(
                "titulo" => $articulo["nombre"],
                "fecha" => "",
                "contenido" => $articulo["contenido"]
            );
        }
        $o_tecnica["articulos"] = $o_articulos;
        self::cerrar();
        return $o_tecnica;
    }

    public static function crear_articulo($titulo, $id_tecnica, $contenido) {
        self::iniciar();
        $titulo = self::sanitizar($titulo);
        $contenido = self::sanitizar($contenido);
        $insercion = "INSERT INTO articulos (nombre,id_tecnica,contenido) VALUES ('{$titulo}',{$id_tecnica},'{$contenido}')";
        if (self::$conexion->insertar_simple($insercion)) {
            self::cerrar();
            return true;
        }
        self::cerrar();
        return false;
    }

}

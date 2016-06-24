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

    public static function sanitizar($cadena, $strip_tags = "") {
        $mysqli = self::$conexion->get_mysqli();
        $cadena = mysqli_real_escape_string($mysqli, $cadena);
        return strip_tags($cadena, $strip_tags);
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
        if ($tecnica !== false && !empty($tecnica)) {
            $o_tecnica = array(
                "nombre" => $tecnica[0]["nombre"],
                "id" => $tecnica[0]["id"]
            );
        } else {
            throw new InvalidArgumentException("Página no encontrada");
        }
        $consulta = "SELECT id, nombre, contenido FROM articulos WHERE activada=TRUE AND id_tecnica={$id_tecnica}";
        $articulos = self::$conexion->consultar_simple($consulta);
        $o_articulos = array();
        foreach ($articulos as $articulo) {
            $o_articulos[] = array(
                "id" => $articulo["id"],
                "titulo" => $articulo["nombre"],
                "fecha" => "",
                "contenido" => $articulo["contenido"]
            );
        }
        $o_tecnica["articulos"] = $o_articulos;
        self::cerrar();
        return $o_tecnica;
    }
    
    public static function existe_tecnica($id_tecnica) {
        try {
            self::obtener_tecnica($id_tecnica);
        } catch (InvalidArgumentException $ex) {
            return false;
        }
        return true;
    }

    public static function crear_articulo($titulo, $id_tecnica, $contenido) {
        if (self::existe_tecnica($id_tecnica)) {
            self::iniciar();
            $titulo = self::sanitizar($titulo);
            $contenido = self::sanitizar($contenido, "<a><strong><em><ol><li><ul><p>");
            $insercion = "INSERT INTO articulos (nombre,id_tecnica,contenido, fecha_hora) VALUES ('{$titulo}',{$id_tecnica},'{$contenido}', NOW())";
            if (self::$conexion->insertar_simple($insercion)) {
                self::cerrar();
                return true;
            }
            self::cerrar();
        }
        return false;
    }
    
    public static function desactivar_articulo($id_articulo){
        self::iniciar();
        $id_articulo = self::sanitizar($id_articulo);
        $actualizacion = <<<SQL
        UPDATE articulos AS a SET activada=FALSE
        WHERE a.id=$id_articulo
SQL;
        if (self::$conexion->actualizar_simple($actualizacion)) {
            self::cerrar();
            // Elimino el título y el contenido
            return self::editar_articulo($id_articulo, "", "");
        }
        self::cerrar();
        return false;
    }

    public static function obtener_historial_articulos($id_articulo){
        self::iniciar();
        $id_articulo = self::sanitizar($id_articulo);
        $consulta = <<<SQL
            SELECT *
            FROM historial_articulos
            WHERE id_articulo=$id_articulo
            ORDER BY fecha_hora DESC
SQL;
        $respuesta = self::$conexion->consultar_simple($consulta);
        $articulos = array();
        if(isset($respuesta) && is_array($respuesta)){
            foreach($respuesta as $fila){
                $articulo = array();
                $articulo["fecha_hora"] = $fila["fecha_hora"];
                $articulo["id"] = $fila["id"];
                $articulo["id_articulo"] = $fila["id_articulo"];
                $articulos[] = $articulo;
            }
        }
        self::cerrar();
        return $articulos;
    }

    /**
     * Obtener una versión de un artículo.
     *
     * Tener en cuenta que si la versión es muy vieja, este método va aplicando
     * los difs consecutivamente hacia atrás.
     *
     * @return la versión de un artículo.
     */
    public static function obtener_version_articulo($id_version, $id_articulo){
        self::iniciar();
        $id_version = self::sanitizar($id_version);
        $id_articulo = self::sanitizar($id_articulo);
        $version = "";

        if($id_version == "-1"){
            // Versión actual
            if(isset($id_articulo)){
                $consulta = <<<SQL
                    SELECT contenido
                    FROM articulos
                    WHERE id=$id_articulo
SQL;
                $respuesta = self::$conexion->consultar_simple($consulta);
                if(isset($respuesta) && is_array($respuesta) && !empty($respuesta)){
                    $version = $respuesta[0]["contenido"];
                }
            }
        } else {
             $consulta = <<<SQL
                SELECT id, diff, id_articulo
                FROM historial_articulos
                WHERE id_articulo=$id_articulo AND id>=$id_version
                ORDER BY id DESC
SQL;
            $respuesta = self::$conexion->consultar_simple($consulta);
            if(isset($respuesta) && is_array($respuesta)){
                $len_respuesta = count($respuesta);
                for($i = 0; $i < $len_respuesta; $i++){
                    $id_articulo = null;
                    $patch = $respuesta[$i]["diff"];
                    $id_articulo = $respuesta[$i]["id_articulo"];
                    if(isset($id_articulo)){
                        if(empty($version)){
                            $consulta = <<<SQL
                                SELECT contenido
                                FROM articulos
                                WHERE id=$id_articulo
SQL;
                            $resp = self::$conexion->consultar_simple($consulta);
                            if(isset($resp) && is_array($resp) && !empty($resp)){
                                $contenido = $resp[0]["contenido"];
                                $version = xdiff_string_bpatch($contenido, $patch);
                            }
                        } else {
                            $version = xdiff_string_bpatch($version, $patch);
                        }
                    }
                }
            }
        }
        self::cerrar();
        return $version;
    }

    public static function editar_articulo($id_articulo, $titulo, $contenido){
        self::iniciar();
        $id_articulo = self::sanitizar($id_articulo);
        $titulo = self::sanitizar($titulo);
        $contenido = self::sanitizar($contenido, "<a><strong><em><ol><li><ul><p>");
        $ok = true;
		self::$conexion->transaccion_comenzar();

        // Obtengo artículo anterior
        $consulta = <<<SQL
        SELECT id, nombre, contenido FROM articulos WHERE activada=TRUE AND id={$id_articulo}
SQL;
        $articulo = self::$conexion->consultar_simple($consulta);
        if(isset($articulo) && is_array($articulo) && count($articulo) > 0){
            $contenido_articulo_anterior = $articulo[0]["contenido"];
			/*echo $contenido_articulo_anterior;
			echo "-------------------";
			echo $contenido;*/
            $dif = xdiff_string_bdiff($contenido, $contenido_articulo_anterior);
            $actualizacion_historial = <<<SQL
			INSERT INTO historial_articulos (id_articulo, diff, fecha_hora)
			VALUES ($id_articulo, '$dif', NOW())
SQL;
			if (!self::$conexion->insertar_simple($actualizacion_historial)) {
		        $ok = false;
		    }
        }
        $actualizacion = <<<SQL
        UPDATE articulos AS a SET nombre='$titulo', contenido='$contenido'
        WHERE a.id=$id_articulo
SQL;
        if (!self::$conexion->actualizar_simple($actualizacion)) {
            $ok = false;
        }
		if ($ok) {
			self::$conexion->transaccion_confirmar();
		} else {
			self::$conexion->transaccion_revertir();
		}
        self::cerrar();
        return $ok;
    }
}

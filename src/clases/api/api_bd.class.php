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
        if ($tecnicas !== false && isset($tecnicas)) {
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
        }
        self::cerrar();
        return $o_tecnicas;
    }
    
    public static function obtener_algoritmos() {
        self::iniciar();
        $consulta = "SELECT id, nombre, id_padre FROM algoritmos WHERE id_padre IS NULL";
        $algoritmos = self::$conexion->consultar_simple($consulta);
        $o_algoritmos = array();
        if ($algoritmos !== false && isset($algoritmos)) {
            foreach ($algoritmos as $algoritmo) {
                $consulta = "SELECT id, nombre, id_padre FROM algoritmos WHERE id_padre = {$algoritmo["id"]}";
                $subalgoritmos = self::$conexion->consultar_simple($consulta);
                $o_algoritmo = array(
                    "nombre" => $algoritmo["nombre"],
                    "id" => $algoritmo["id"]
                );
                $links = array();
                foreach ($subalgoritmos as $subalgoritmo) {
                    $links[] = array(
                        "href" => $subalgoritmo["id"],
                        "nombre" => $subalgoritmo["nombre"]
                    );
                }
                $o_algoritmo["links"] = $links;
                $o_algoritmos[] = $o_algoritmo;
            }
        }
        self::cerrar();
        return $o_algoritmos;
    }

    public static function obtener_vulnerabilidades() {
        self::iniciar();
        $consulta = "SELECT id, nombre, disenio, codigo, configuracion FROM vulnerabilidades";
        $vulnerabilidades = self::$conexion->consultar_simple($consulta);
        $o_vulnerabilidades = array();
        if ($vulnerabilidades !== false && isset($vulnerabilidades)) {
            foreach ($vulnerabilidades as $vulnerabilidad) {
                $o_vulnerabilidades[] = array(
                    "nombre" => $vulnerabilidad["nombre"],
                    "id" => $vulnerabilidad["id"],
                    "disenio" => $vulnerabilidad["disenio"],
                    "codigo" => $vulnerabilidad["codigo"],
                    "configuracion" => $vulnerabilidad["configuracion"]
                );
            }
        }
        self::cerrar();
        return $o_vulnerabilidades;
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
        
        $actualizacion = "UPDATE tecnicas SET visitas=visitas+1 WHERE id=$id_tecnica";
        self::$conexion->actualizar_simple($actualizacion);
        
        $consulta = "SELECT id, nombre, visitas FROM tecnicas WHERE id={$id_tecnica}";
        $tecnica = self::$conexion->consultar_simple($consulta);
        if ($tecnica !== false && !empty($tecnica)) {
            $o_tecnica = array(
                "nombre" => $tecnica[0]["nombre"],
                "id" => $tecnica[0]["id"],
                "visitas" => $tecnica[0]["visitas"]
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
        $o_tecnica["cantidad_eliminados"] = self::obtener_cantidad_articulos_eliminados($id_tecnica);
        return $o_tecnica;
    }
    
    public static function obtener_algoritmo($id_algoritmo) {
        self::iniciar();
        
        $actualizacion = "UPDATE algoritmos SET visitas=visitas+1 WHERE id=$id_algoritmo";
        self::$conexion->actualizar_simple($actualizacion);
        
        $consulta = "SELECT id, nombre, visitas FROM algoritmos WHERE id={$id_algoritmo}";
        $algoritmo = self::$conexion->consultar_simple($consulta);
        if ($algoritmo !== false && !empty($algoritmo)) {
            $o_algoritmo = array(
                "nombre" => $algoritmo[0]["nombre"],
                "id" => $algoritmo[0]["id"],
                "visitas" => $algoritmo[0]["visitas"]
            );
        } else {
            throw new InvalidArgumentException("Página no encontrada");
        }
        $consulta = "SELECT id, nombre, contenido FROM articulos WHERE activada=TRUE AND id_algoritmo={$id_algoritmo}";
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
        $o_algoritmo["articulos"] = $o_articulos;
        
        self::cerrar();
        //$o_tecnica["cantidad_eliminados"] = self::obtener_cantidad_articulos_eliminados($id_tecnica);
        return $o_algoritmo;
    }

    public static function obtener_vulnerabilidad($id_vulnerabilidad) {
        self::iniciar();

        $actualizacion = "UPDATE vulnerabilidades SET visitas=visitas+1 WHERE id=$id_vulnerabilidad";
        self::$conexion->actualizar_simple($actualizacion);

        $consulta = "SELECT id, nombre, visitas FROM vulnerabilidades WHERE id={$id_vulnerabilidad}";
        $vulnerabilidad = self::$conexion->consultar_simple($consulta);
        if ($vulnerabilidad !== false && !empty($vulnerabilidad)) {
            $o_vulnerabilidad = array(
                "nombre" => $vulnerabilidad[0]["nombre"],
                "id" => $vulnerabilidad[0]["id"],
                "visitas" => $vulnerabilidad[0]["visitas"]
            );
        } else {
            throw new InvalidArgumentException("Página no encontrada");
        }
        $consulta = "SELECT id, nombre, contenido FROM articulos WHERE activada=TRUE AND id_vulnerabilidad={$id_vulnerabilidad}";
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
        $o_vulnerabilidad["articulos"] = $o_articulos;

        self::cerrar();
        return $o_vulnerabilidad;
    }

    /**
     * Retorna la cantidad de artículos eliminados en una técnica.
     */
    public static function obtener_cantidad_articulos_eliminados($id_tecnica) {
        self::iniciar();
        $cantidad = 0;
        $id_tecnica = self::sanitizar($id_tecnica);
        $consulta = "SELECT COUNT(*) AS c FROM articulos WHERE activada=FALSE AND id_tecnica={$id_tecnica}";
        $cantidad_articulos = self::$conexion->consultar_simple($consulta);
        if(isset($cantidad_articulos)){
            $cantidad = $cantidad_articulos[0]["c"];
        }
        self::cerrar();
        return $cantidad;
    }

    public static function existe_tecnica($id_tecnica) {
        try {
            self::obtener_tecnica($id_tecnica);
        } catch (InvalidArgumentException $ex) {
            return false;
        }
        return true;
    }
    
    public static function existe_algoritmo($id_algoritmo) {
        try {
            self::obtener_algoritmo($id_algoritmo);
        } catch (InvalidArgumentException $ex) {
            return false;
        }
        return true;
    }
    
    public static function existe_vulnerabilidad($id_tecnica) {
        try {
            self::obtener_vulnerabilidad($id_tecnica);
        } catch (InvalidArgumentException $ex) {
            return false;
        }
        return true;
    }
    
    public static function crear_tecnica($nombre_tecnica, $id_padre) {
        self::iniciar();
        $nombre_tecnica = self::sanitizar($nombre_tecnica);
        if (isset($id_padre)) {
            $id_padre = self::sanitizar($id_padre);
            $insercion = "INSERT INTO tecnicas (nombre, id_padre) VALUES ('{$nombre_tecnica}',{$id_padre})";
        } else {
            $insercion = "INSERT INTO tecnicas (nombre) VALUES ('{$nombre_tecnica}')";
        }
        if (self::$conexion->insertar_simple($insercion)) {
            self::cerrar();
            return true;
        }
        self::cerrar();
        return false;
    }
    
    public static function crear_algoritmo($nombre_algoritmo, $id_padre) {
        self::iniciar();
        $nombre_algoritmo = self::sanitizar($nombre_algoritmo);
        if (isset($id_padre)) {
            $id_padre = self::sanitizar($id_padre);
            $insercion = "INSERT INTO algoritmos (nombre, id_padre) VALUES ('{$nombre_algoritmo}',{$id_padre})";
        } else {
            $insercion = "INSERT INTO algoritmos (nombre) VALUES ('{$nombre_algoritmo}')";
        }
        if (self::$conexion->insertar_simple($insercion)) {
            self::cerrar();
            return true;
        }
        self::cerrar();
        return false;
    }

    public static function crear_articulo($titulo, $id_tecnica, $contenido, $tipo) {
        self::iniciar();
        $titulo = self::sanitizar($titulo);
        $contenido = self::sanitizar($contenido, "<a><strong><em><ol><li><ul><p><span><pre><code><table><tbody><tr><th><td><thead><caption><hr><iframe><blockquote>");
        if ($tipo === "tecnica") {
            if (self::existe_tecnica($id_tecnica)) {
                $insercion = "INSERT INTO articulos (nombre, id_tecnica, contenido, fecha_hora) VALUES ('{$titulo}',{$id_tecnica},'{$contenido}', NOW())";
            } else {
                self::cerrar();
                return false;
            }
        } else if ($tipo === "vulnerabilidad") {
            if (self::existe_vulnerabilidad($id_tecnica)) {
                $insercion = "INSERT INTO articulos (nombre, id_vulnerabilidad, contenido, fecha_hora) VALUES ('{$titulo}',{$id_tecnica},'{$contenido}', NOW())";
            } else {
                self::cerrar();
                return false;
            }
        } else if ($tipo === "algoritmo") {
            if (self::existe_algoritmo($id_tecnica)) {
                $insercion = "INSERT INTO articulos (nombre, id_algoritmo, contenido, fecha_hora) VALUES ('{$titulo}',{$id_tecnica},'{$contenido}', NOW())";
            } else {
                self::cerrar();
                return false;
            }
        } else {
            throw InvalidArgumentException("Tipo incorrecto.");
        }
        if (self::$conexion->insertar_simple($insercion)) {
            self::cerrar();
            return true;
        }
        self::cerrar();
        return false;
    }

    /**
     * Desactiva un artículo a través de su id.
     *
     * Elimina el contenido del artículo actual, agrega una versión al
     * historial y pone el flag activada en false.
     *
     */
    public static function desactivar_articulo($id_articulo){
        if (self::editar_articulo($id_articulo, "", "")) {
            self::iniciar();
            $id_articulo = self::sanitizar($id_articulo);
            $actualizacion = <<<SQL
                UPDATE articulos AS a SET activada=FALSE
                WHERE a.id=$id_articulo AND activada=TRUE
SQL;
            if (self::$conexion->actualizar_simple($actualizacion)) {
                self::cerrar();
                return true;
            } else {
                self::cerrar();
                return false;
            }
        }
        return false;
    }
    
    /**
     * Cambia la cantidad de una vulnerabilidad, sumando uno o restando uno.
     *
     *
     */
    public static function cambiar_cantidad_vulnerabilidad($id_vulnerabilidad, $etapa, $cantidad) {
        self::iniciar();
        $id_vulnerabilidad = self::sanitizar($id_vulnerabilidad);
        $etapa = self::sanitizar($etapa);
        $cantidad = self::sanitizar($cantidad);
        if ($etapa === "disenio") {
            $actualizacion = <<<SQL
            UPDATE vulnerabilidades AS v SET disenio=disenio+$cantidad
            WHERE v.id=$id_vulnerabilidad
SQL;
        } else if ($etapa === "desarrollo"){
            $actualizacion = <<<SQL
            UPDATE vulnerabilidades AS v SET codigo=codigo+$cantidad
            WHERE v.id=$id_vulnerabilidad
SQL;
        } else if ($etapa === "despliegue"){
            $actualizacion = <<<SQL
            UPDATE vulnerabilidades AS v SET configuracion=configuracion+$cantidad
            WHERE v.id=$id_vulnerabilidad
SQL;
        } else {
            self::cerrar();
            return false;
        }
        if (self::$conexion->actualizar_simple($actualizacion)) {
            self::cerrar();
            return true;
        } else {
            self::cerrar();
            return false;
        }
        return false;
    }

    /**
     * Retorna un array con el historial de un artículo.
     *
     * Cada elemento del array contiene:
     *      fecha_hora
     *      id
     *      id_articulo
     *      activada
     *
     */
    public static function obtener_historial_articulos($id_articulo){
        self::iniciar();
        $id_articulo = self::sanitizar($id_articulo);
        $consulta = <<<SQL
            SELECT ha.fecha_hora, ha.id AS id, ha.id_articulo
            FROM historial_articulos AS ha
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
     * Si el id_version es -1 retorna la versión actual del artículo.
     *
     * @param string $id_version es el id de versión que se desea recuperar.
     * @param string $id_articulo es el id del artículo del cual se desea
     * recuperar la versión.
     * @return array[string] donde el primer elemento es la versión del
     * artículo y el segundo elemento es el título del artículo.
     */
    public static function obtener_version_articulo($id_version, $id_articulo){
        self::iniciar();
        $id_version = self::sanitizar($id_version);
        $id_articulo = self::sanitizar($id_articulo);
        if($id_articulo <= 0){
            throw new InvalidArgumentException("Argumento inválido: $id_articulo");
        }
        $version = null; // Texto de la versión
        $titulo = null;

        if($id_version == "-1"){
            // Versión actual
            if(isset($id_articulo)){
                $consulta = <<<SQL
                    SELECT contenido, nombre
                    FROM articulos
                    WHERE id=$id_articulo
SQL;
                $respuesta = self::$conexion->consultar_simple($consulta);
                if(isset($respuesta) && is_array($respuesta) && !empty($respuesta)){
                    $version = $respuesta[0]["contenido"];
                    $titulo = $respuesta[0]["nombre"];
                }
            }
        } else {
             $consulta = <<<SQL
                SELECT id, diff, diff_titulo, id_articulo
                FROM historial_articulos
                WHERE id_articulo=$id_articulo AND id>=$id_version
                ORDER BY id DESC
SQL;
            $respuesta = self::$conexion->consultar_simple($consulta);
            if(isset($respuesta) && is_array($respuesta)){
                $len_respuesta = count($respuesta);
                for($i = 0; $i < $len_respuesta; $i++){
                    $patch = $respuesta[$i]["diff"];
                    $patch_titulo = $respuesta[$i]["diff_titulo"];
                    $id_articulo = $respuesta[$i]["id_articulo"];
                    if(isset($id_articulo)){
                        // Cargo el contenido de la versión
                        if(is_null($version)){
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
                        // Cargo el título de la versión
                        if(is_null($titulo)){
                            $consulta = <<<SQL
                                SELECT nombre
                                FROM articulos
                                WHERE id=$id_articulo
SQL;
                            $resp = self::$conexion->consultar_simple($consulta);
                            if(isset($resp) && is_array($resp) && !empty($resp)){
                                $nombre = $resp[0]["nombre"];
                                $titulo = xdiff_string_bpatch($nombre, $patch_titulo);
                            }
                        } else {
                            $titulo = xdiff_string_bpatch($titulo, $patch_titulo);
                        }
                    }
                }
            }
        }
        self::cerrar();
        return array("version" => $version, "titulo" => $titulo);
    }

    /**
     * Edita un artículo a través de su id.
     *
     * Modifica el titulo y el contenido. Ambos, o al menos, uno de los dos.
     *
     * Además de modificar el título y el contenido, crea una nueva versión del
     * artículo almacenando las diferencias.
     *
     * @param int $id_articulo
     * @param string $titulo
     * @param string $contenido
     * @return bool
     */
    public static function editar_articulo($id_articulo, $titulo, $contenido){
        self::iniciar();
        $id_articulo = self::sanitizar($id_articulo);
        $titulo = self::sanitizar($titulo);
        $contenido = self::sanitizar($contenido, "<a><strong><em><ol><li><ul><p><span>");
        $ok = true;
		self::$conexion->transaccion_comenzar();

        // Obtengo artículo anterior
        $consulta = <<<SQL
        SELECT id, nombre, contenido, nombre FROM articulos WHERE id={$id_articulo}
SQL;
        $articulo = self::$conexion->consultar_simple($consulta);
        if(isset($articulo) && is_array($articulo) && count($articulo) > 0){
            $contenido_articulo_anterior = $articulo[0]["contenido"];
            $titulo_articulo_anterior = $articulo[0]["nombre"];
            $dif = xdiff_string_bdiff($contenido, $contenido_articulo_anterior);
            $diff_titulo = xdiff_string_bdiff($titulo, $titulo_articulo_anterior);
            $actualizacion_historial = <<<SQL
			INSERT INTO historial_articulos (id_articulo, diff, diff_titulo, fecha_hora)
			VALUES ($id_articulo, '$dif', '$diff_titulo', NOW())
SQL;
			if (!self::$conexion->insertar_simple($actualizacion_historial)) {
		        $ok = false;
		    }
        }
        $actualizacion = <<<SQL
        UPDATE articulos AS a SET nombre='$titulo', contenido='$contenido', activada=1
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

    public static function consultar_articulos_desactivados($id_tecnica){
        self::iniciar();
        $id_tecnica = self::sanitizar($id_tecnica);
        $consulta = <<<SQL
            SELECT * 
            FROM articulos
            WHERE id_tecnica=$id_tecnica AND activada=FALSE
SQL;
        $articulos = self::$conexion->consultar_simple($consulta);
        return $articulos;
    }

    /**
     * Retorna la anteúltima versión.
     *
     * Es decir, la versión más reciente que se guarda en la tabla
     * historial_articulos.
     *
     */
    public static function obtener_anteultima_version($id_articulo){
        self::iniciar();
        $articulo = array();
        $consulta = <<<SQL
            SELECT MAX(id) AS id_version
            FROM historial_articulos
            WHERE id_articulo=$id_articulo
SQL;
        $respuesta = self::$conexion->consultar_simple($consulta);
        $articulo["nombre"] = null;
        $articulo["contenido"] = null;
        if(isset($respuesta) && !empty($respuesta)){
            $id_version = $respuesta[0]["id_version"];
            $articulo["contenido"] = self::obtener_version_articulo($id_version, $id_articulo);
        }
        self::cerrar();
        return $articulo;
    }

    /**
     * Agrega una técnica.
     *
     * Si $technique_parent_id es null entonces se inserta como técnica padre.
     * Es decir, con id_padre nulo.
     *
     */
    public static function add_technique($technique_name, $technique_parent_id = null){
        self::iniciar();
        $technique_name = self::sanitizar($technique_name);
        $ok = true;
        if(isset($technique_parent_id)){
            $technique_parent_id = self::sanitizar($technique_parent_id);
            $insercion = <<<SQL
                INSERT INTO tecnicas (nombre, id_padre)
                VALUES ('$technique_name', '$technique_parent_id')
SQL;
        } else {
            $insercion = <<<SQL
                INSERT INTO tecnicas (nombre)
                VALUES ('$technique_name')
SQL;
        }
        $respuesta = self::$conexion->insertar_simple($insercion);
        if(!$respuesta){
            $ok = false;
        }
        self::cerrar();
        return $ok;
    }

    /**
     * Editar una técnica.
     *
     * Edita el nombre de una técnica.
     *
     */
    public static function edit_technique($technique_name, $technique_id){
        self::iniciar();
        $technique_name = self::sanitizar($technique_name);
        $technique_id = self::sanitizar($technique_id);
        $ok = true;
        $actualizacion = <<<SQL
            UPDATE tecnicas SET nombre='$technique_name'
            WHERE id='$technique_id'
SQL;
        $respuesta = self::$conexion->actualizar_simple($actualizacion);
        if(!$respuesta){
            $ok = false;
        }
        self::cerrar();
        return $ok;
    }
    
    /**
     * Editar una técnica.
     *
     * Edita el nombre de una técnica.
     *
     */
    public static function edit_algorithm($algorithm_name, $algorithm_id){
        self::iniciar();
        $algorithm_name = self::sanitizar($algorithm_name);
        $algorithm_id = self::sanitizar($algorithm_id);
        $ok = true;
        $actualizacion = <<<SQL
            UPDATE algoritmos SET nombre='$algorithm_name'
            WHERE id='$algorithm_id'
SQL;
        $respuesta = self::$conexion->actualizar_simple($actualizacion);
        if(!$respuesta){
            $ok = false;
        }
        self::cerrar();
        return $ok;
    }
    
    public static function iniciar_sesion($usuario, $clave) {
        self::iniciar();
        $usuario = self::sanitizar($usuario);
        $clave = self::sanitizar($clave);
        $consulta = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE nombre='$usuario' AND clave=SHA2('$clave', 256)";
        $resultado = self::$conexion->consultar_simple($consulta);
        if (!empty($resultado)) {
            if ($resultado[0]["cantidad"] === "1") {
                return true;
            }
        }
        return false;
    }

   public static function crear_usuario($usuario, $clave) {
       self::iniciar();
       $usuario = self::sanitizar($usuario);
       $clave = self::sanitizar($clave);
       $insert = <<<SQL
       INSERT INTO usuarios (nombre, clave) VALUES ('$usuario', SHA2('$clave', 256))
SQL;
       $ok = self::$conexion->insertar_simple($insert);
       return $ok;
   }
}

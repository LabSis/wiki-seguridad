<?php
ini_set("display_errors", 1);
require_once 'src/conexion.class.php';
require_once 'src/api_bd.class.php';
$id_tecnica = $_GET["id"];
Conexion::set_default_conexion("labsis_seg", Conexion::init("localhost", "root", "", "labsis_seg", true));
if(!empty($_POST)){
    // Guardar sección o artículo...
    ApiBd::crear_articulo($titulo, $id_tecnica, $contenido);
}
$tmpl_tecnica = ApiBd::obtener_tecnica($id_tecnica);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>LabSis - Seg</title>
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/estilo.css" rel="stylesheet" />
        <script src="js/jquery.js"></script>
        <script src="css/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <main class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3><?php echo $tmpl_tecnica["nombre"]; ?></h3>
                    <?php foreach ($tmpl_tecnica["articulos"] as $articulo): ?>
                        <section>
                            <h5>
                                <?php echo $articulo["titulo"] ?>
                            </h5>
                            <div class="contenido">
                                <p>
                                    <?php echo $articulo["contenido"] ?>
                                </p>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row">
                <form role="form">
                    <div class="form-group">
                        <label for="comment">Título:</label>
                        <input type="text" class="form-control" id="txtTitulo">
                    </div>
                    <div class="form-group">
                        <label for="comment">Contenido:</label>
                        <textarea class="form-control" rows="20" id="txtContenido"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Crear sección</button>
                </form>
            </div>
        </main>
    </body>
</html>

<?php
ini_set("display_errors", 1);
require_once 'src/conexion.class.php';
require_once 'src/api_bd.class.php';
$id_tecnica = $_GET["id"];
Conexion::set_default_conexion("labsis_seg", Conexion::init("localhost", "root", "", "labsis_seg", true));
$tmpl_tecnica = ApiBd::obtener_tecnica($id_tecnica);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Tec Bucket</title>
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
        </main>
    </body>
</html>

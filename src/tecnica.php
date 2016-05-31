<?php
require_once '../config.php';

$sesion = Sesion::get_instancia();
$sesion->limpiar_mensajes();

$id_tecnica = $_GET["id"];

$tmpl_tecnica = array();
try{
    $tmpl_tecnica = ApiBd::obtener_tecnica($id_tecnica);
} catch(Exception $ex) {
    $sesion->cargar_mensaje($ex->getMessage(), Sesion::TIPO_MENSAJE_ERROR);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>LabSis - Seg</title>
        <link href="<?php echo $RUTA_WEB ?>/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo $RUTA_WEB ?>/css/estilo.css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $RUTA_WEB ?>/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $RUTA_WEB ?>/js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo $RUTA_WEB ?>/css/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                CKEDITOR.replace("txtContenido");
            });
        </script>
    </head>
    <body>
        <main class="container">
            <?php require_once ('../tmpl/maquetado/mensajes.tmpl.php') ?>
            <div class="row">
                <div class="col-sm-12">
                    <h1><?php echo (isset($tmpl_tecnica["nombre"]))?$tmpl_tecnica["nombre"]:""; ?></h1>
                    <?php if(isset($tmpl_tecnica["articulos"])): ?>
                        <?php foreach ($tmpl_tecnica["articulos"] as $articulo): ?>
                            <section>
                                <h3>
                                    <?php echo $articulo["titulo"] ?>
                                </h3>
                                <div class="contenido">
                                    <p>
                                        <?php echo $articulo["contenido"] ?>
                                    </p>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row crear_articulo">
                <form role="form" action="guardar_tecnica.php?id=<?php echo $id_tecnica ?>" method="post">
                    <div>
                        <p>Al agregar un artículo usted se hace responsable de la información que pública. Para un control interno guardamos datos de aquellas personas que crean un artículo. Gracias.</p>
                    </div>
                    <div class="form-group">
                        <label for="comment">Título:</label>
                        <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
                    </div>
                    <div class="form-group">
                        <label for="comment">Contenido:</label>
                        <textarea class="form-control" rows="20" name="txtContenido" id="txtContenido"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Crear sección</button>
                </form>
            </div>
        </main>
    </body>
</html>
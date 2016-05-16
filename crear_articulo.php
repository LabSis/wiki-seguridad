<?php
require_once 'src/conexion.class.php';
require_once 'src/api_bd.class.php';
Conexion::set_default_conexion("labsis_seg", Conexion::init("localhost", "root", "", "labsis_seg", true));
$tecnicas = ApiBd::obtener_tecnicas_raiz();
$btn_crear = filter_input(INPUT_POST, "btnCrear");
if (isset($btn_crear)) {
    $titulo = filter_input(INPUT_POST, "titulo");
    $id_tecnica = filter_input(INPUT_POST, "tecnica");
    $contenido = filter_input(INPUT_POST, "contenido");
    if (ApiBd::crear_articulo($titulo, $id_tecnica, $contenido)) {
        $tmpl_mensaje = "Creado con éxito";
    } else {
        $tmpl_mensaje = "Error al crear artículo";
    }
}
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
                    <h3>Crear artículo nuevo</h3>
                    <br/>
                    <div>
                        <form method="post">
                            <?php if (isset($tmpl_mensaje)): ?>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="alert alert-info">!</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <label class="col-sm-2">
                                    Título:
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" name="titulo" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2">
                                    Técnica:
                                </label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="tecnica">
                                        <?php foreach ($tecnicas as $tecnica): ?>
                                            <option value="<?php echo $tecnica["id"]; ?>">
                                                <?php echo $tecnica["nombre"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <!--<option value="0">Seleccione</option>-->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2">
                                    Contenido:
                                </label>
                                <div class="col-sm-5">
                                    <textarea rows="10" class="form-control" name="contenido"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn-primary" type="submit" name="btnCrear" style="width: 180px; margin-left:210px;">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>

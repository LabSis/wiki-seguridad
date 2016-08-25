<?php

require_once 'config.php';

$sesion = Session::get_instance();

ini_set("display_errors", 1);
$tmpl_tecnicas = ApiBd::obtener_tecnicas();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/general.css" rel="stylesheet" />
        <script src="js/jquery.js"></script>
        <script src="css/bootstrap/js/bootstrap.min.js"></script>
        <title>LabSis - Seg</title>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".add").click(function(){
                    var techniqueId = $(this).data("techniqueId");
                    var webPath = $("#webPath").val();

                    $("#hidTechniqueParentId").val(techniqueId);
                    $("#txtTechniqueName").val("");
                    $("#modalTechnique").find("form").attr("action", webPath + "src/add_technique.php");
                    $("#modalTechnique").find("#submit").text("Crear");

                    $("#modalTechnique").modal("show");
                });
                $(".edit").click(function(){
                    var techniqueId = $(this).data("techniqueId");
                    var techniqueName = $(this).data("techniqueName");
                    var webPath = $("#webPath").val();

                    $("#hidTechniqueId").val(techniqueId);
                    $("#txtTechniqueName").val(techniqueName);
                    $("#modalTechnique").find("form").attr("action", webPath + "src/edit_technique.php");
                    $("#modalTechnique").find("#submit").text("Editar");
                    
                    $("#modalTechnique").modal("show");
                });
            });
        </script>
    </head>
    <body>
        <input type="hidden" value="<?php echo $WEB_PATH ?>" name="webPath" id="webPath" />
        <main class="container">
            <h1>LabSis - Seg</h1>
            <h3>Técnicas de ataques</h3>
            <div class="row">
                <div class="col-sm-12">
                    <?php $i = 1 ?>
                    <?php foreach ($tmpl_tecnicas as $tmpl_tecnica): ?>
                        <?php if ($i % 4 == 1): ?>
                            <div class="row">
                        <?php endif; ?>
                        <div class="col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <?php echo $tmpl_tecnica["nombre"]; ?>
                                        <i class="add glyphicon glyphicon-plus" title="Agregar" data-technique-id="<?php echo $tmpl_tecnica["id"] ?>"></i>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul>
                                        <?php foreach ($tmpl_tecnica["links"] as $link): ?>
                                            <li>
                                                <a href="src/tecnica.php?id=<?php echo $link["href"]; ?>">
                                                    <?php echo $link["nombre"]; ?>
                                                </a>
                                                <i class="edit glyphicon glyphicon-edit" title="Editar" data-technique-id="<?php echo $link["href"] ?>" data-technique-name="<?php echo $link["nombre"] ?>"></i>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php if ($i % 4 == 0): ?>
                            </div>
                        <?php endif; ?>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <div class="modal fade" id="modalTechnique" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form action="<?php echo $WEB_PATH ?>src/add_technique.php" method="POST">
                        <input type="hidden" value="" name="hidTechniqueId" id="hidTechniqueId" />
                        <input type="hidden" value="" name="hidTechniqueParentId" id="hidTechniqueParentId" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Agregar técnica</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="comment">Nombre:</label>
                                <input type="text" class="form-control" name="txtTechniqueName" id="txtTechniqueName">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

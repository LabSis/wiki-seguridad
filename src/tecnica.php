<?php
require_once '../config.php';

$sesion = Session::get_instance();

$id_tecnica = $_GET["id"];

$tmpl_tecnica = array();
try{
    $tmpl_tecnica = ApiBd::obtener_tecnica($id_tecnica);
} catch(Exception $ex) {
    $sesion->add_success_message($ex->getMessage());
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>LabSis - Seg</title>
        <link href="<?php echo $WEB_PATH ?>/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo $WEB_PATH ?>/css/estilo.css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $WEB_PATH ?>/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $WEB_PATH ?>/js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo $WEB_PATH ?>/css/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                CKEDITOR.replace("txtContenido");
                CKEDITOR.replace("txtContenidoModalEditar");

                // Borrado de artículo
                var articuloABorrarJQuery = null;
                function mostrarModalConfirmacionBorrar(idArticulo){
                    $("#hidIdArticulo").val(idArticulo);
                    var idTecnica = $("#hidIdTecnica").val();
                    $("#hidIdTecnicaModalBorrado").val(idTecnica);
                    $("#modalConfirmarBorrado").modal("show");
                }

                $(document).on("hide.bs.modal", "#modalConfirmarBorrado", function () {
                    console.log(articuloABorrarJQuery);
                    if(articuloABorrarJQuery != null){
                        articuloABorrarJQuery.removeClass("articuloSeleccionado");
                    }
                });

                $(".borrar").click(function(){
                    articuloABorrarJQuery = $(this).parents("section");
                    articuloABorrarJQuery.addClass("articuloSeleccionado");
                    var id = $(this).parents("section").data("id");
                    mostrarModalConfirmacionBorrar(id);
                });

                // Edición de artículo
                function mostrarModalEditar(idArticulo, titulo, contenido){
                    /*$.ajax({
                        url: "<?php echo $WEB_PATH ?><?php echo $CTRL_REL_PATH ?>consultar_versiones_articulo.php",
                        type: "post",
                        data: {
                            idArticulo: idArticulo
                        }
                    }).done(function(r){
                        console.log(r);
                    });*/
                    
                    CKEDITOR.instances['txtContenidoModalEditar'].setData(contenido);
                    $("#txtTituloModalEditar").val(titulo);
                    $("#hidIdArticuloModalEditar").val(idArticulo);
                    $("#modalEditar").modal("show");
                }

                $(document).on("click", ".editar", function(){
                    var titulo = $(this).parents("section").find(".titulo").text().trim();
                    var contenido = $(this).parents(".contenido").html().trim();
                    var idArticulo = $(this).parents("section").data("id");
                    $("#btnVerHistorial").data("id-articulo", idArticulo);
                    mostrarModalEditar(idArticulo, titulo, contenido);
                });

                // Ver historial
                function verHistorial(idArticulo, idTecnica){
                    location.href = "<?php echo $WEB_PATH ?><?php echo $CTRL_REL_PATH ?>ver_historial_articulo.php?id_articulo=" + idArticulo + "&id_tecnica=" + idTecnica;
                }

                $("#btnVerHistorial").click(function(){
                    var idArticulo = $(this).data("id-articulo");
                    var idTecnica = $("#hidIdTecnica").val();
                    verHistorial(idArticulo, idTecnica);
                });

                // Ver artículos desactivados
                function mostrarArticulosDesactivados(idTecnica){
                    $.ajax({
                        url: "ajax/consultar_articulos_desactivados.php",
                        type: "post",
                        data: {
                            id_tecnica: idTecnica
                        }
                    }).done(function(r){
                        var articulosDesactivados = JSON.parse(r);
                        if(articulosDesactivados.status === "ok") {
                            var articulos = articulosDesactivados.articulos;
                            for(indiceArticulo in articulos){
                                var articulo = articulos[indiceArticulo];

                                // Creo el HTML
                                var seccionHtml = '<section class="articulo-desactivado" data-id="' + articulo.id +'">';
                                var tituloHtml = '<h3 class="titulo">';
                                var contenidoHtml = '<div class="contenido">';

                                tituloHtml += articulo.nombre;
                                contenidoHtml += articulo.contenido;

                                tituloHtml += "</h3>";
                                contenidoHtml += '<i class="editar glyphicon glyphicon-edit" title="Editar"></i></div>';
                                seccionHtml += tituloHtml;
                                seccionHtml += contenidoHtml;

                                seccionHtml += "</section>";
                                $("#divSecciones").append(seccionHtml);
                            }
                        }
                        console.log(articulosDesactivados);
                    }).fail(function(){
                        alert("Fail AJAX");
                    });
                }

                function ocultarArticulosDesactivados(idTecnica){
                    $(".articulo-desactivado").remove();
                }

                $("#chkMostrarArticulosDesactivados").change(function(){
                    var idTecnica = $("#hidIdTecnica").val();
                    if($(this).prop("checked")){
                        mostrarArticulosDesactivados(idTecnica);
                    } else {
                        ocultarArticulosDesactivados(idTecnica);
                    }
                });
            });
        </script>
    </head>
    <body>
        <main class="container">
            <input type="hidden" value="<?php echo $tmpl_tecnica["id"] ?>" name="id_tecnica" id="hidIdTecnica" />
            <?php require_once $SERVER_PATH . $TEMPLATES_REL_PATH . 'maquetado/menu.tmpl.php' ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php require_once $SERVER_PATH . $TEMPLATES_REL_PATH . 'maquetado/mensajes.tmpl.php' ?>
                    <h1><?php echo (isset($tmpl_tecnica["nombre"]))?$tmpl_tecnica["nombre"]:""; ?></h1>
                    <div id="divSecciones">
                        <?php if(isset($tmpl_tecnica["articulos"])): ?>
                            <?php foreach ($tmpl_tecnica["articulos"] as $articulo): ?>
                                <section data-id="<?php echo $articulo["id"]?>">
                                    <h3 class="titulo">
                                        <?php echo $articulo["titulo"] ?>
                                    </h3>
                                    <div class="contenido">
                                        <?php echo $articulo["contenido"] ?>
                                        <i class="borrar glyphicon glyphicon-trash" title="Borrar"></i>
                                        <i class="editar glyphicon glyphicon-edit" title="Editar"></i>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <input type="checkbox" id="chkMostrarArticulosDesactivados" />
                    <label for="chkMostrarArticulosDesactivados" >Mostrar artículos desactivados</label>
                </div>
            </div>
            <div class="row crear_articulo">
                <form role="form" action="guardar_articulo.php?id=<?php echo $id_tecnica ?>" method="post">
                    <div>
                        <p>Al agregar un artículo usted se hace responsable de la información que publica. Para llevar un control interno guardamos algunos datos de aquellas personas que crean un artículo. Gracias.</p>
                    </div>
                    <div class="form-group">
                        <label for="txtTitulo">Título:</label>
                        <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
                    </div>
                    <div class="form-group">
                        <label for="txtContenido">Contenido:</label>
                        <textarea class="form-control" rows="20" name="txtContenido" id="txtContenido"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Crear artículo</button>
                </form>
            </div>
        </main>
        <div class="modal fade" id="modalConfirmarBorrado" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo $WEB_PATH ?>/src/desactivar_articulo.php" method="POST">
                        <input type="hidden" value="<?php echo $tmpl_tecnica["id"] ?>" name="id_tecnica" id="hidIdTecnicaModalBorrado" />
                        <input type="hidden" name="id_articulo" id="hidIdArticulo" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación de borrado</h4>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro que deseas borrar este artículo? Más tarde puedes deshacer este cambio.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Sí</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="<?php echo $WEB_PATH ?>src/editar_articulo.php" method="POST">
                        <input type="hidden" value="<?php echo $tmpl_tecnica["id"] ?>" name="hidIdTecnicaModalEditar" id="hidIdTecnicaModalEditar" />
                        <input type="hidden" name="hidIdArticuloModalEditar" id="hidIdArticuloModalEditar" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Editar artículo</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="comment">Título:</label>
                                <input type="text" class="form-control" name="txtTituloModalEditar" id="txtTituloModalEditar">
                            </div>
                            <div class="form-group">
                                <label for="comment">Contenido:</label>
                                <textarea class="form-control" rows="20" name="txtContenidoModalEditar" id="txtContenidoModalEditar"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a data-id-articulo="" id="btnVerHistorial" class="btn btn-default" >Ver historial</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

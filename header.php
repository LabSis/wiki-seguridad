<script src="<?php echo $WEB_PATH ?>js/modulo_ventana.js"></script>
<script>
    $(document).ready(function() {
        var webPath = $("#webPath").val();
        function iniciarSesion() {
            var usuario = $("#usuario").val();
            var clave = $("#clave").val();
            $.ajax({
                url: webPath + "src/iniciar_sesion.php",
                type: "POST",
                data: {
                    usuario: usuario,
                    clave: clave
                },
                success: function(r) {
                    if (r === "Ok") {
                        location.href = "";
                    } else {
                        $("#modal-inicio-sesion").modal("hide");
                        ModuloVentana.mostrar({
                            titulo: "Error",
                            contenido: "Usuario incorrecto",
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });
                    }
                },
                error: function(r) {
                    console.log(r);
                }
            });
        }
        $("#btn-iniciar-sesion").click(function() {
            iniciarSesion();
        });
        $("#clave").keypress(function(e) {
            if (e.which === 13) {
                iniciarSesion();
            }
        });
        
        $("#btn-cerrar-sesion").click(function() {
            cerrarSesion()
        });

        function cerrarSesion(){
            $.ajax({
                url: webPath + "src/cerrar_sesion.php",
                type: "POST",
                success: function(r) {
                    if (r === "Ok") {
                        location.href = "";
                    }
                },
                error: function(r) {
                    console.log(r);
                }
            });
        }

        $("#btn-abrir-modal-datos-usuario").click(function(){
            $.ajax({
                url: webPath + "src/ajax/consultar_datos_autor.php",
                type: "GET",
                dataType: "json",
                success: function(r) {
                    if (r.estado == "ok"){
                        limpiarModalDatosUsuario()
                        let autor = r.datos.autor;
                        $("#nombre-autor-modal").val(autor.nombre)
                        $("#alias-autor-modal").val(autor.alias)
                        $("#email-autor-modal").val(autor.email)
                        $("#usuario-github-autor-modal").val(autor.usuario_github)

                        $("#modal-editar-datos-usuario").modal("show")
                    }
                    else {
                        ModuloVentana.mostrar({
                            titulo: "Error",
                            contenido: r.mensaje,
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });
                    }
                },
                error: function(r) {
                    console.log(r);
                }
            });
        })


        $("#btn-cambiar-clave").click(function(){

            $.ajax({
                url: webPath + "src/ajax/editar_datos_usuario.php",
                type: "POST",
                dataType: "json",
                data: {
                    "clave_actual": $("#clave-actual-usuario-modal").val(),
                    "clave_nueva": $("#clave-nueva-usuario-modal ").val(),
                    "clave_repetida": $("#clave-repetida-usuario-modal").val()
                },
                success: function(r) {

                    if (r.estado == "ok"){
                        ModuloVentana.mostrar({
                            titulo: "Éxito",
                            contenido: "La calve se modificó correctamente",
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            callbacks: {
                                "aceptar": cerrarSesion
                            },
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });

                    }
                    else {
                        ModuloVentana.mostrar({
                            titulo: "Error",
                            contenido: r.mensaje,
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });
                    }
                },
                error: function(r) {
                    console.log(r);
                }
            });
        })

        $("#btn-guardar-datos-autor").click(function(){
            $.ajax({
                url: webPath + "src/ajax/editar_datos_autor.php",
                type: "POST",
                dataType: "json",
                data: {
                    "nombre_autor": $("#nombre-autor-modal").val(),
                    "alias_autor": $("#alias-autor-modal").val(),
                    "email_autor": $("#email-autor-modal").val(),
                    "usuario_github": $("#usuario-github-autor-modal").val()
                },
                success: function(r) {
                    if (r.estado == "ok"){

                        ModuloVentana.mostrar({
                            titulo: "Éxito",
                            contenido: "Los datos de autor se guardaron correctamente",
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });
                    }
                    else {
                        ModuloVentana.mostrar({
                            titulo: "Error",
                            contenido: r.mensaje,
                            tipo: ModuloVentana.TIPO.ACEPTAR,
                            cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                        });
                    }
                },
                error: function(r) {
                    console.log(r);
                }
            });
        })



        $("#checkbox-cambiar-clave-modal").on("change", function(){
            let checked = $("#checkbox-cambiar-clave-modal").prop("checked");
            if (checked){
                $("#div-cambio-clave-usuario").css("display", "block")
                $("#btn-cambiar-clave").prop("disabled", false)
            }
            else {
                $("#div-cambio-clave-usuario").css("display", "none")
                $("#btn-cambiar-clave").prop("disabled", true)
            }

        });

        function limpiarModalDatosUsuario(){
            $("#clave-actual-usuario-modal").val("")
            $("#clave-nueva-usuario-modal").val("")
            $("#clave-repetida-usuario-modal").val("")
            $("#nombre-autor-modal").val("")
            $("#alias-autor-modal").val("")
            $("#email-autor-modal").val("")
            $("#usuario-github-autor-modal").val("")

        }

    });
</script>

<input type="hidden" value="<?php echo $WEB_PATH ?>" name="webPath" id="webPath" />

<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <a class="navbar-brand" href="<?php echo $WEB_PATH ?>">LabSis - Wiki de seguridad</a>
            <ul class="nav navbar-nav navbar-right">
                <li>
                <?php 
                
                $sesion = Session::get_instance();

                ?><?php if (!$sesion->is_active()): ?>
                    <a class="nav navbar-nav navbar-right" data-toggle="modal" data-target="#modal-inicio-sesion" style="cursor: pointer">
                        <i class="glyphicon glyphicon-log-in" data-technique-id=""></i>
                        Iniciar sesión
                    </a>
                <?php else: ?>
                    <?php
                        $usuario = $sesion->get_user()->get_name();
                    ?>
                    <a class="nav navbar-nav " style="cursor: pointer" id="btn-abrir-modal-datos-usuario">
                        <i class="glyphicon glyphicon-user" data-technique-id=""></i>
                        <?php echo $usuario?>
                    </a>
                    <a class="nav navbar-nav navbar-right" style="cursor: pointer" id="btn-cerrar-sesion">
                        <i class="glyphicon glyphicon-log-out" data-technique-id=""></i>
                        Cerrar sesión
                    </a>
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="modal-inicio-sesion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Iniciar sesión</h4>
            </div>
            <div class="modal-body">
                <form action="/action_page.php">
                    <div class="form-group">
                        <label for="email">Usuario:</label>
                        <input type="email" class="form-control" id="usuario">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Clave:</label>
                        <input type="password" class="form-control" id="clave">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn-iniciar-sesion">Iniciar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<div id="modal-editar-datos-usuario" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar mis datos</h4>
            </div>
            <div class="modal-body">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Datos de usuario</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="nombre-usuario-modal">Nombre de usuario</label>
                                    <input type="text" id="nombre-usuario-modal" class="form-control" value="<?php echo ($sesion->is_active()) ? $sesion->get_user()->get_name() : ""?>" disabled>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <input type="checkbox" id="checkbox-cambiar-clave-modal"> Cambiar clave<br>
                            </div>
                        </div>
                        <div class="row" id="div-cambio-clave-usuario" style="display: none">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="clave-actual-usuario-modal">Clave actual</label>
                                    <input type="password" class="form-control" id="clave-actual-usuario-modal">
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="clave-nueva-usuario-modal">Clave</label>
                                    <input type="password" class="form-control" id="clave-nueva-usuario-modal">
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="clave-repetida-usuario-modal">Repetir clave</label>
                                    <input type="password" class="form-control" id="clave-repetida-usuario-modal">
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="panel-footer" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="btn-cambiar-clave" disabled>Cambiar clave</button>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Datos de autor</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group">
                                    <label for="nombre-usuario-modal">Nombre</label>
                                    <input type="text" id="nombre-autor-modal" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="alias-usuario-modal">Alias</label>
                                    <input type="text" id="alias-autor-modal" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="email-usuario-modal">Email</label>
                                    <input type="text" id="email-autor-modal" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                    <label for="usuario-github-usuario-modal">Usuario Github</label>
                                    <input type="text" id="usuario-github-autor-modal" class="form-control" >
                                </div>
                            </div>
                        </div>


                <div class="panel-footer" style="text-align: right">
                    <button type="submit" class="btn btn-primary"   id="btn-guardar-datos-autor">Guardar datos</button>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
</div>
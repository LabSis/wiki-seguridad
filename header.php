<script>
    $(document).ready(function() {
        $("#btn-iniciar-sesion").click(function() {
            var usuario = $("#usuario").val();
            var clave = $("#clave").val();
            $.ajax({
                url: "src/iniciar_sesion.php",
                type: "POST",
                data: {
                    usuario: usuario,
                    clave: clave
                },
                success: function(r) {
                    if (r === "Ok") {
                        location.href = "";
                    } else {
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
        });
        
        $("#btn-cerrar-sesion").click(function() {
            $.ajax({
                url: "src/cerrar_sesion.php",
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
        });
    });
</script>

<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <a class="navbar-brand" href="<?php echo $WEB_PATH ?>">LabSis - Pentesting</a>
            <ul class="nav navbar-nav navbar-right">
                <li>
                <?php 
                
                $sesion = Session::get_instance();

                ?><?php if (!$sesion->is_active()): ?>
                    <a class="nav navbar-nav navbar-right" data-toggle="modal" data-target="#modal-inicio-sesion" style="cursor: pointer">Iniciar sesión</a>
                <?php else: ?>
                    <a class="nav navbar-nav navbar-right" style="cursor: pointer" id="btn-cerrar-sesion">Cerrar sesión</a>
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

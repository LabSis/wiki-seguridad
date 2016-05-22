<div id="mensajeFormulario">
    <?php if (Sesion::get_instancia()->hay_mensajes()): ?>
        <?php $template_mensajes = Sesion::get_instancia()->get_mensajes(); ?>
        <?php if (isset($template_mensajes) && count($template_mensajes) > 0): ?>
            <?php
            $template_correspondencias_tipos_mensaje = array(
                Sesion::TIPO_MENSAJE_EXITO => 'success',
                Sesion::TIPO_MENSAJE_INFORMACION => 'info',
                Sesion::TIPO_MENSAJE_ALERTA => 'warning',
                Sesion::TIPO_MENSAJE_ERROR => 'danger'
            );
            ?>
            <?php foreach ($template_mensajes as $tipo_mensaje => $mensajes_por_tipo): ?>
                <?php if (count($mensajes_por_tipo) > 0): ?>
                    <?php $template_clase_estilo_mensaje = $template_correspondencias_tipos_mensaje[$tipo_mensaje]; ?>
                    <div class="alert alert-<?php echo $template_clase_estilo_mensaje ?>">
                        <?php if (count($mensajes_por_tipo) > 1): ?>
                            <ul>
                                <?php foreach ($mensajes_por_tipo as $template_mensaje): ?>
                                    <li><?php echo $template_mensaje; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <?php echo $mensajes_por_tipo[0]; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
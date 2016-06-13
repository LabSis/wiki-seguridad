<div id="mensajeFormulario">
    <?php if (Session::get_instance()->has_messages()): ?>
        <?php $template_mensajes = Session::get_instance()->get_menssages(); ?>
        <?php if (isset($template_mensajes) && count($template_mensajes) > 0): ?>
            <?php
            $template_correspondencias_tipos_mensaje = array(
                Sesion::TYPE_SUCCESS_MESSAGE => 'success',
                Sesion::TYPE_INFORMATION_MESSAGE => 'info',
                Sesion::TYPE_WARNING_MESSAGE => 'warning',
                Sesion::TYPE_ERROR_MESSAGE => 'danger'
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

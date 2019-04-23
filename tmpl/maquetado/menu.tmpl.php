<div class="row">
    <div class="col-sm-12">
        <a href="<?php echo (isset($LINK_VOLVER)) ? $LINK_VOLVER: $WEB_PATH; ?>">Volver</a> |
        <?php if ($sesion->is_active() && isset($tmpl_contenedor)): ?>
            <span>Visitas:</span> <span><?php echo $tmpl_contenedor["visitas"] ?></span>
        <?php endif; ?>
        <hr class="division-line-menu"/>
    </div>
</div>

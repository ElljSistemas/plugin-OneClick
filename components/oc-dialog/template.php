<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('mc-icon');
?>

<div class="oc-dialog">
    <mc-icon name="one-click-dialog"></mc-icon>
    <div class="triangle"></div>
    <div class="content">
        <slot name="content"></slot>
    </div>
</div>
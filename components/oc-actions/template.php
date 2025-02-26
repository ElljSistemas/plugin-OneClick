<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;
?>

<div class="oc-actions" v-if="useActions">
    <button class="button button--primary" @click="save()"><span><?= i::__('Salvar') ?></span></button>
</div>
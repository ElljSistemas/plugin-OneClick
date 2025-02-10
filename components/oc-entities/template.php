<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    oc-tabs
    mc-alert
    oc-upload
')
?>

<div class="oc-entities">
    <oc-tabs :entity="entity" :groups="tabGroups" initial-group="tabs">

        <template #entitiesSection="{tab, entity}">
            <?php $this->part('text-image-entities--entities') ?>
        </template>

        <template #opportunity="{tab}">
            <div class="entities-tabs"></div>

            <oc-text-image :entity="entity" slug="opportunity">
                <template #opportunity-text="{tab, entity}">
                    <?php $this->part('text-image-entities--opportunity-text') ?>
                </template>

                <template #opportunity-image="{tab, entity}">
                    <mc-alert type="warning">
                        <?= i::__('onfigure aqui a imagem do card de Oportunidades na Home. Certifique-se de que o banner tenha as dimensões de ') ?><span class="color-red"><?= i::__('800x320') ?></span><?= i::__(', mantendo a proporção de 5:2') ?>
                    </mc-alert>

                    <oc-upload :entity="entity" prop="home-opportunities" dir="assets/img/home" :imageSize="[900, 320]"></oc-upload>
                </template>
            </oc-text-image>
        </template>

    </oc-tabs>
</div>
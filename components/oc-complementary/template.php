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
    entity-field
')
?>
<div class="complementary-tabs">
    <oc-tabs :entity="entity" :groups="tabGroups" initial-group="tabs">
        <template #logo="{tab}">
            <?php $this->part('text-image-complementary--logo') ?>
        </template>
    </oc-tabs>
</div>
<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    oc-header
    oc-steps
    oc-tabs
    oc-actions
    mc-entity 
');
?>

<mc-entity :id="settingsId" type="settings" select="*" v-slot="{ entity }">
    <div class="container">
        <div class="configuration-steps">
            <mc-card>
                <oc-header :entity="entity"></oc-header>

                <oc-steps :entity="entity"></oc-steps>
            </mc-card>
        </div>
    </div>

    <div class="menu">
        <oc-tabs :entity="entity" :groups="tabGroups" initial-group="settings">
            <template #email="{tab}">
                <?php $this->part('settings-email') ?>
            </template>

            <template #recaptcha="{tab}">
                <?php $this->part('settings-recaptcha') ?>
            </template>

            <template #georeferencing>
                <?php $this->part('settings-georeferencing') ?>
            </template>

            <template #socialmedia>
                <?php $this->part('settings-socialmedia') ?>
            </template>

            <template #banner>
                <?php $this->part('text-image-banner') ?>
            </template>

            <template #entities>
                <?php $this->part('text-image-entities') ?>
            </template>
        </oc-tabs>
    </div>

    <div class="actions">
        <oc-actions :entity="entity" editable></oc-actions>
    </div>
</mc-entity>
<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-field
    oc-dialog
 ');

?>

<div class="settings-email">
    <oc-dialog>
        <template #content>
            <?= i::__('Configure aqui as credenciais SMTP para o envio de e-mails automáticos, como criação de conta, redefinição de senha e notificações.') ?>
            <?= i::__('Será necessário informar e-mail, porta, protocolo e senha. Consulte o responsável de TI para obter esses dados.') ?>
        </template>
    </oc-dialog>
    <div class="grid-12">
        <entity-field :entity="entity" prop="email" class="col-4"></entity-field>
        <entity-field :entity="entity" prop="port" class="col-4"></entity-field>
        <entity-field :entity="entity" prop="protocol" class="col-4"></entity-field>
        <entity-field :entity="entity" prop="password" class="col-6"></entity-field>
        <entity-field :entity="entity" prop="repassword" class="col-6"></entity-field>
    </div>
</div>
<?php

use yii\helpers\Html;

?>
<fieldset>
    <legend><?= Html::encode(Yii::t('purchase', 'Contact information')) ?></legend>
    <div class="row">
        <div class="col-sm-4"><?= $activeForm->field($form, 'phone') ?></div>
        <div class="col-sm-4"><?= $activeForm->field($form, 'name') ?></div>
    </div>
    <div class="row">
        <div class="col-sm-4"><?= $activeForm->field($form, 'email') ?></div>
    </div>
</fieldset>

<?php

use yii\helpers\Html;
use app\assets\TripCreateAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */

TripCreateAsset::register($this);
$this->title = 'Create Trip';
$this->params['breadcrumbs'][] = ['label' => 'Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (isset($error['message'])) {
        echo '<div class="alert alert-danger">';
        echo nl2br(Html::encode($error['message']));
        echo '</div>';
    }
    ?>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
         
    ?>
    <?= $this->render('_getCoordinates'); ?>
    
</div>

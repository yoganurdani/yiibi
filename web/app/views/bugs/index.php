<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bugs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bugs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bugs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'jumlahBugs',
            'tanggal',
            'tipeBugs',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

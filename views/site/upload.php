<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>

<select name="tipe_laporan" id="tipe_laporan">
    <option value="komplain">Laporan Komplain</option>
    <option value="bugs">Laporan Bugs</option>
</select>
<button type="submit">Submit</button>

<?php ActiveForm::end() ?>
<?php echo $tipee;
?>
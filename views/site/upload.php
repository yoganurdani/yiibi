<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file' )->fileInput(); ?>

<select class="form-control" style="width:170px; float:left;" name="tipe_laporan" id="tipe_laporan">
    <option value="komplain">Laporan Komplain</option>
    <option value="bugs">Laporan Bugs</option>
</select>
<button class="btn btn-success" style="margin-left: 10px;" type="submit">Submit</button>

<?php ActiveForm::end() ?>
<?php echo $tipee;
?>

<br>
<br>
<br>
<div class="panel panel-danger">
  <div class="panel-heading"><div class="glyphicon glyphicon-warning-sign"> Peringatan</div></div>
  <div class="panel-body" style="margin-left:15px;">
    <li>Masukkan file laporan komplain atau laporan bug dalam bentuk <b>csv</b> </li>
    <li>Pada dropdown pilih laporan yang sesuai</li>
    <li>Tekan tombol submit</li>
    <li>Tunggu sampai proses selesai</li>
    <li>Untuk melihat grafik pilih menu bar disamping kiri</li>
  </div>
</div>
<?php
use yii\widgets\ActiveForm; 

	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'importcsv-form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	));

?>
 
<?php echo $form->errorSummary($model,'','',array('class'=>'wrapped left-icon icon-cross-round')); ?>
 
<br>
 
<fieldset class="fieldset">
	<legend class="legend">Harap Isi Form Berikut:</legend>
	<p class="button-height inline-label">
		<?php echo $form->labelEx($model,'file',array('class'=>'label')); ?>
		<?php echo $form->fileField($model,'file',array('class'=>'input')); ?> 
	</p>
</fieldset>
 
<span class="button-group">
	<button type="submit" class="button icon-download">Submit</button>
</span>
 
<?php $this->endWidget(); 
ActiveForm::end(); ?>
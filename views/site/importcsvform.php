<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
 'id'=>'csv-form',
 'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

 <?php //echo $form->errorSummary($model); ?>

 <div class="row">
  <?php echo $form->labelEx($model,'csvfile'); ?>
        <?php 
            $this->widget('CMultiFileUpload', array(
                'model'=>$model,
                'name' => 'csvfile',
                'max'=>1,
                'accept' => 'csv',
                'duplicate' => 'Duplicate file!', 
                'denied' => 'Invalid file type',              
            ));
        ?>
  <?php echo $form->error($model,'csvfile'); ?>
 </div>

 <div class="row buttons">
  <?php echo CHtml::submitButton('Import',array("id"=>"Import",'name'=>'Import')); ?>
 </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
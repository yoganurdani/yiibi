<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "complain".
 *
 * @property integer $id
 * @property integer $jumlahKomplain
 * @property string $tanggal
 * @property integer $responKomplain
 * @property integer $jumlahCS
 */
class ImportCsv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
   public $file;
 
	public function rules() {
		return array(
			array(
				'file',
				'file',
				'types'=>'csv',
				'maxsize'=>1024 * 1024 * 10, //10MB
				'tooLarge'=>'File melebihi 10MB. Pilih file CSV lain.',
				'allowEmpty'=>false,
			),
		);
	}
 
	public function attributeLabels() {
		return array(
			'file'=>'File CSV',
		);
	}
}

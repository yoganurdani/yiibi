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
class Complain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jumlahKomplain', 'tanggal', 'responKomplain', 'jumlahCS'], 'required'],
            [['jumlahKomplain', 'responKomplain', 'jumlahCS'], 'integer'],
            [['tanggal'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jumlahKomplain' => 'Jumlah Komplain',
            'tanggal' => 'Tanggal',
            'responKomplain' => 'Respon Komplain',
            'jumlahCS' => 'Jumlah Cs',
        ];
    }
}

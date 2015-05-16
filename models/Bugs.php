<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Bugs".
 *
 * @property integer $id
 * @property integer $jumlahBugs
 * @property string $tanggal
 * @property string $tipeBugs
 */
class Bugs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Bugs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jumlahBugs', 'tanggal', 'tipeBugs'], 'required'],
            [['jumlahBugs'], 'integer'],
            [['tanggal'], 'safe'],
            [['tipeBugs'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jumlahBugs' => 'Jumlah Bugs',
            'tanggal' => 'Tanggal',
            'tipeBugs' => 'Tipe Bugs',
        ];
    }
}

<?php

namespace sintret\whatsapp\models;

use Yii;

/**
 * This is the model class for table "whatsapp_from".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $type
 * @property string $phoneId
 * @property string $nickName
 * @property string $number
 * @property string $password
 * @property string $email
 * @property string $emailPassword
 * @property string $updateDate
 * @property string $createDate
 *
 * @property Whatsapp[] $whatsapps
 */
class WhatsappFrom extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'whatsapp_from';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'type', 'number'], 'integer'],
            [['password'], 'required'],
            [['email'], 'string'],
            [['updateDate', 'createDate'], 'safe'],
            [['phoneId', 'password', 'emailPassword'], 'string', 'max' => 128],
            [['nickName'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'userId' => 'User',
            'type' => 'Type',
            'phoneId' => 'Phone',
            'nickName' => 'Nick Name',
            'number' => 'Number',
            'password' => 'Password',
            'email' => 'Email',
            'emailPassword' => 'Email Password',
            'updateDate' => 'Update Date',
            'createDate' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhatsapps() {
        return $this->hasMany(Whatsapp::className(), ['fromId' => 'id']);
    }

    public static function getDropdown() {
        $return = [];
        $connection = Yii::$app->db;
        $rows = $connection->createCommand('SELECT id,nickName FROM whatapp_from')->queryAll();
        foreach ($rows as $row) {
            $return[$row['id']] = $row['nickName'];
        }
        return $return;
    }

}

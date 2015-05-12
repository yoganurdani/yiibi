<?php

namespace sintret\whatsapp\models;

use Yii;

/**
 * This is the model class for table "whatsapp".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $type
 * @property integer $fromId
 * @property string $to
 * @property string $message
 * @property string $image
 * @property string $audio
 * @property string $video
 * @property string $location
 * @property string $lat
 * @property string $lon
 * @property string $ip
 * @property string $updateDate
 * @property string $createDate
 *
 * @property WhatsappFrom $from
 */
class Whatsapp extends \yii\db\ActiveRecord
{
    public static $typies = [1=>'Send Message','Update Status','Send Broadcast','Inbox'];
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whatsapp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'type', 'fromId'], 'integer'],
            [['to', 'message'], 'required'],
            [['message'], 'string'],
            [['updateDate', 'createDate'], 'safe'],
            [['to', 'lat', 'lon', 'ip'], 'string', 'max' => 128],
            [['image', 'audio', 'video', 'location'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User',
            'type' => 'Type',
            'fromId' => 'From',
            'to' => 'To',
            'message' => 'Message',
            'image' => 'Image',
            'audio' => 'Audio',
            'video' => 'Video',
            'location' => 'Location',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'ip' => 'Ip',
            'updateDate' => 'Update Date',
            'createDate' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrom()
    {
        return $this->hasOne(WhatsappFrom::className(), ['id' => 'fromId']);
    }
}

<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $senderId
 * @property string $messageText
 * @property integer $channelId
 * @property integer $receiverId
 *
 * @property Channel $channel
 * @property User $sender
 */
class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senderId', 'messageText', 'channelId'], 'required'],
            [['senderId', 'channelId', 'receiverId'], 'integer'],
            [['messageText'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'senderId' => 'Sender ID',
            'messageText' => 'Message Text',
            'channelId' => 'Channel ID',
            'receiverId' => 'Receiver ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channelId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'senderId']);
    }

    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiverId']);
    }

    /**
     * @param array $data
     * Пытается распарсить значения сообщения из массива, что приходит с пост-запросом обычно
     */
    public function parse($data)
    {
        if (!Yii::$app->user->isGuest)
            $this->senderId = Yii::$app->user->identity->id;

        if ($data['message'])
            $this->messageText = $data['message'];

        if ($data['channelName']) {
            $channel = Channel::find()->where(['channelName' => $data['channelName']])->one();
            if ($channel)
                $this->channelId = $channel->id;
        }

        if ($data['receiver'] && strlen($data['receiver']) > 0) {
            $receiverUser = User::find()->where(['username' => $data['receiver']])->one();
            if ($receiverUser)
                $this->receiverId = $receiverUser->id;
            else
                $this->receiverId = 0;
        }
    }
}

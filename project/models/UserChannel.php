<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "userChannel".
 *
 * @property integer $userId
 * @property integer $channelId
 * @property string $lastUserRequest
 *
 * @property Channel $channel
 * @property User $user
 */
class UserChannel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userChannel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'channelId', 'lastUserRequest'], 'required'],
            [['userId', 'channelId'], 'integer'],
            [['lastUserRequest'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'channelId' => 'Channel ID',
            'lastUserRequest' => 'Last User Request',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channelId']);
    }

    public function getChannelByName()
    {
        //return $this->hasOne(Channel::className(), )
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @param \yii\db\ActiveRecord $channel
     * @param int $id
     */
    public static function updateUserLastAccess($channel, $id)
    {
        if ($channel) {
            $userChannel = self::find()->where(['channelId' => $channel->id, 'userId' => $id])->one();
            if ($userChannel == null) {
                $userChannel = new UserChannel();
                $userChannel->channelId = $channel->id;
                $userChannel->userId = $id;
            }

            $userChannel->lastUserRequest = date("Y-m-d H:i:s");
            $userChannel->save();
        }
    }

    public static function clearOfflineUsers()
    {
        $sql = 'select * from userChannel where TIMEDIFF(:now, lastUserRequest) > 5';
        $userChannels = UserChannel::findBySql($sql, [':now' => date("Y-m-d H:i:s")])->all();
        foreach ($userChannels as $userChannel)
            $userChannel->delete();
    }
}

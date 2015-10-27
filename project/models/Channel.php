<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "channel".
 *
 * @property integer $id
 * @property string channelName
 * @property string $description
 *
 * @property Message[] $messages
 * @property UserChannel[] $userChannels
 * @property User[] $users
 */
class Channel extends ActiveRecord
{
    public static function tableName()
    {
        return 'channel';
    }

    public function rules()
    {
        return [
            [['channelName'], 'filter', 'filter' => function($value) {
                return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $value));
            }],
            [['channelName'], 'required'],
            [['channelName'], 'string', 'max' => 20],
            [['channelName'], 'unique'],
            [['description'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channelName' => 'Channel Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['channelId' => 'id']);
    }


    /**
     * @return array
     * Оформление массива сообщений для отправки клиенту
     */
    public function getFormattedMessages()
    {
        $formattedMessages = [];
        foreach ($this->messages as $message) {
            $formattedMessage = [];
            $formattedMessage['id'] = $message->id;
            $formattedMessage['sender'] = $message->sender->username;
            $formattedMessage['messageText'] = $message->messageText;
            $receiver = $message->receiver;
            if ($receiver) {
                $formattedMessage['receiver'] = $receiver->username;
                if (!Yii::$app->user->isGuest) {
                    if ($formattedMessage['receiver'] == Yii::$app->user->identity->username ||
                        $formattedMessage['sender'] == Yii::$app->user->identity->username ||
                        Yii::$app->user->identity->isAdmin()
                    )
                        $formattedMessages[] = $formattedMessage;
                }
            }
            else
                $formattedMessages[] = $formattedMessage;
        }
        return $formattedMessages;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->viaTable('userChannel', ['channelId' => 'id']);
    }


    /**
     * @return array
     * Оформление массива пользователей для отправки клиенту
     */
    public function getFormattedUsers()
    {
        $formattedUsers = [];
        foreach ($this->users as $user) {
            $group = Usersgroup::find()->where(['id' => $user->groupId])->one();
            $formattedUser = [];
            $formattedUser['username'] = $user->username;
            $formattedUser['group'] = $group->name;
            $formattedUsers[] = $formattedUser;
        }
        return $formattedUsers;
    }
}

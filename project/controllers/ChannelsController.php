<?php

namespace app\controllers;

use app\models\Channel;
use app\models\Message;
use app\models\MessageForm;
use app\models\UserChannel;
use Yii;
use yii\web\Controller;

class ChannelsController extends Controller
{
    public function actionIndex()
    {
        UserChannel::clearOfflineUsers();
        $model = new Channel();
        return $this->render('index', ['model' => $model]);
    }

    public function actionView($channelName)
    {
        return $this->render('view', ['channelName' => $channelName]);
    }

    public function actionGetData($channelName)
    {
        $channel = Channel::find()->where(['channelName' => $channelName])->one();

        if (!Yii::$app->user->isGuest) {
            UserChannel::updateUserLastAccess($channel, Yii::$app->user->identity->id);
        }

        UserChannel::clearOfflineUsers();

        $result = json_encode(['usersList' => $channel->formattedUsers, 'messages' => $channel->formattedMessages]);

        return $result;
    }

    public function actionSendMessage()
    {
        if (!Yii::$app->user->isGuest) {
            $post = Yii::$app->request->post();
            $message = new Message();
            $message->parse($post);
            $message->save();
        }
    }

    public function actionDelMessage()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()) {
            $post = Yii::$app->request->post();
            if ($post['id']) {
                $message = Message::find()->where(['id' => $post['id']])->one();
                if ($message)
                    $message->delete();
            }
        }
    }
}
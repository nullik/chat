<?php

$this->title = $channelName;
$this->registerJsFile('@web/js/contextmenu.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/chat.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div id="chat" class="col-md-offset-2 col-md-8">
    <div id="usersList" class="panel panel-default">
    </div>

    <div id="chatBox" class="panel panel-default">
    </div>
    <div id="messageForm">
        <input id="messageInput" class="form-control" type="text">
        <button id="sendMessageBtn" class="form-control btn btn-default">Send</button>
    </div>
</div>

<div class="hidden list-group" id="messageContextMenu">
    <button class="list-group-item" name="sendPrivateMsg">Написать личное сообщение</button>
    <?php
        if (!Yii::$app->user->isGuest)
            if (Yii::$app->user->identity->isAdmin())
                echo '<button class="list-group-item" name="deleteMsg">Удалить</button>';
    ?>
</div>

<div class="hidden list-group" id="usersListContextMenu">
    <button class="list-group-item" name="sendPrivateMsg">Написать личное сообщение</button>
</div>
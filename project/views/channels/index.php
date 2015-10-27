<?php

use yii\helpers\Html;

$this->title = "Channels";
?>

<div class="col-md-offset-3 col-lg-offset-3 col-md-6 col-lg-6">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin())
            echo '<a href="/channel-edit/index" class="btn btn-default" style="margin: 10px 0px 10px 0px;">Manage channels</a>';
    ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Channel</th>
                <th>Members</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $channels = $model->find()->all();
            foreach ($channels as $channel) {
                echo '<tr>';
                echo '<td><a href="/channels/'. $channel->channelName .'">' . $channel->channelName . '</a></td>';
                echo '<td>' . count($channel->users) . '</td>';
                echo '<td>' . $channel->description . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

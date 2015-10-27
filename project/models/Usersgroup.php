<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usersgroup".
 *
 * @property integer $id
 * @property string $name
 *
 * @property User[] $users
 */
class Usersgroup extends \yii\db\ActiveRecord
{
    const USER = 1;
    const ADMIN = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usersgroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['groupId' => 'id']);
    }
}

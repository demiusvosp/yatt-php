<?php

namespace app\models\entities;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\models\queries\CommentQuery;


/**
 * This is the model class for table "comment".
 *
 * @property integer      $id
 * @property string       $object_class
 * @property integer      $object_id
 * @property integer      $author_id
 * @property integer      $type
 * @property string       $text
 * @property string       $created_at
 * @property string       $updated_at
 *
 * @property User         $author
 * @property ActiveRecord $object
 */
class Comment extends ActiveRecord
{
    const TYPE_USUAL = 0; // стандартный тип
    // потом тут появятся коменты системы, комменты бота, комменты важных событий, вроде комент к закрытию задачи

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_class', 'object_id'], 'required'],
            [['object_id', 'author_id', 'type'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['object_class'], 'string', 'max' => 80],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['author_id' => 'id'],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('comment', 'ID'),
            'object_class' => Yii::t('comment', 'Object'),
            'object_id'    => Yii::t('comment', 'Object ID'),
            'author_id'    => Yii::t('comment', 'Author ID'),
            'author'       => Yii::t('comment', 'Author'),
            'type'         => Yii::t('comment', 'Type'),
            'text'         => Yii::t('comment', 'Text'),
            'created_at'   => Yii::t('comment', 'Created At'),
            'updated_at'   => Yii::t('comment', 'Updated At'),
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }


    /**
     * @param User $user
     */
    public function setAuthor($user)
    {
        $this->author_id = $user->id;
    }


    /**
     * @return array|null|ActiveRecord
     */
    public function getObject()
    {
        return (new ActiveQuery($this->object_class))
            ->where(['id' => $this->object_id])
            ->one();
    }


    /**
     * @param ActiveRecord $object
     */
    public function setObject($object)
    {
        $this->object_class = $object->className();
        $this->object_id    = $object->id;
    }


    /**
     * @inheritdoc
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }
}

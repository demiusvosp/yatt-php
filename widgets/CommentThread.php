<?php
/**
 * User: demius
 * Date: 21.11.17
 * Time: 21:09
 */

namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\models\entities\Comment;


class CommentThread extends Widget
{
    public $object;

    /** @var array */
    protected $comments;


    public function init()
    {
        $this->comments = Comment::find()->getThread($this->object);
        parent::init();
    }


    public function run()
    {
        $newComment = new Comment([
            'author' => Yii::$app->user->identity,
            'object' => $this->object
        ]);

        if($newComment->load(Yii::$app->request->post()) && $newComment->validate()) {
            $newComment->save();

            Yii::$app->response->refresh('#comment-'.$newComment->id);
            Yii::$app->end();
        } else {
            Yii::$app->session->addFlash('error', 'Cannot save comment');
        }

        return $this->render('commentThread', [
            'comments' => $this->comments,
            'newComment' => $newComment,
        ]);
    }
}
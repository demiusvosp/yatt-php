<?php
/**
 * User: demius
 * Date: 21.11.17
 * Time: 21:09
 */

namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\base\IInProject;
use app\helpers\Access;
use app\models\entities\Project;
use app\models\entities\Comment;


/**
 * Class CommentThread - widget тред комментариев, с возможность добавить новый или отредактировать существующий
 *
 * @package app\widgets
 */
class CommentThread extends Widget
{
    public $object;

    public $enableNewComment = true;

    /** @var array */
    protected $comments;


    public function init()
    {
        $this->comments = Comment::find()->getThread($this->object);
        parent::init();
    }


    public function run()
    {
        $project = null;
        if ($this->object instanceof IInProject) {
            $project = $this->object->getProject();
        } else if($this->object instanceof Project) {
            $project = $this->object;
        }

        // коммент, который хотят отредактировать
        $editCommentId = Yii::$app->request->get('edit');
        // коммент, который хотят удалить
        $deleteCommentId = Yii::$app->request->get('delete');

        // полномочия менеджмента комментов
        $editAnything = Yii::$app->user->can(Access::projectItem(Access::MANAGE_COMMENT, $project));

        if ($this->enableNewComment &&
            Yii::$app->user->can(Access::projectItem(Access::CREATE_COMMENT, $project)) &&
            !$editCommentId
        ) {
            // Коменты можно создавать и сейчас не редактируем, создаем новый под фрму.
            $newComment = new Comment([
                'author'  => Yii::$app->user->identity,
                'object'  => $this->object,
                'project' => $project,
            ]);

            $this->comments = array_merge($this->comments, [$newComment]);
        }

        // пройдемся по комментам и выполним над ними запрошеное
        if (Yii::$app->request->isPost || $deleteCommentId) {
            /** @var Comment $comment */
            foreach ($this->comments as $comment) {
                if ($comment->id == $editCommentId && // только автор или админ
                    ($editAnything || $comment->author_id == Yii::$app->user->getId())
                ) {
                    // редактирование
                    if ($comment->load(Yii::$app->request->post())) {
                        if ($comment->validate()) {
                            $comment->save();

                            Yii::$app->response->redirect(
                                '/' . Yii::$app->request->pathInfo . '#comment-' . $comment->id
                            );
                            Yii::$app->end();
                        } else {
                            Yii::$app->session->addFlash('error',
                                Yii::t('comment', 'Cannot save comment'));
                        }
                    }
                }

                if ($comment->id == $deleteCommentId && // только автор или админ
                    ($editAnything || $comment->author_id == Yii::$app->user->getId())
                ) {
                    $comment->delete();
                    Yii::$app->session->addFlash('success', Yii::t('comment', 'Comment deleted'));
                    Yii::$app->response->redirect(
                        '/' . Yii::$app->request->pathInfo . '#comment-thread'
                    );
                    Yii::$app->end();
                }
            }
        }

        return $this->render('commentThread', [
            'comments'      => $this->comments,
            'editCommentId' => $editCommentId,
            'editAnything'  => $editAnything,
        ]);
    }
}
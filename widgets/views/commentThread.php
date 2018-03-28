<?php
/**
 * User: demius
 * Date: 21.11.17
 * Time: 21:56
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\helpers\HtmlBlock;
use app\helpers\TextEditorHelper;
use app\models\entities\Comment;


/* @var array $comments */
/* @var Comment $newComment */
/* @var int  $editCommentId */
/* @var bool $editAnything */

?>
<div class="comment-thread col-md-5" id="comment-thread">
    <?php /** @var Comment $comment */ ?>
    <?php foreach ($comments as $comment) { ?>
        <?php if (// редактируем комент если:
            $editCommentId == $comment->id && // хотят отредактировать этот (или он новый)
            (   // свой коммент или можно редактировать любые
                $editAnything || $comment->author_id == Yii::$app->user->getId()
            )
        ) { ?>
            <?php $form = ActiveForm::begin(); ?>
            <div class="comment-form">
                <div
                    class="box box-success comment <?=$comment->type == Comment::TYPE_CLOSE ? 'box-bordered' : '' ?>"
                    id="comment-<?= $comment->id ?>"
                >
                    <div class="box-header">
                        <h3 class="box-title">
                            <?= Yii::t('comment', 'Create comment') ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <?= $form->field($comment, 'text')->editor(['rows' => 5]) ?>
                    </div>
                    <div class="box-footer">
                        <div class="comment-author"><?= HtmlBlock::userItem($comment->author) ?></div>
                        <div class="comment-ctrl">
                            <?= Html::submitButton(
                                $comment->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Edit'),
                                ['class' => $comment->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?php } else { ?>
            <div
                class="box box-success comment <?=$comment->type == Comment::TYPE_CLOSE ? 'box-bordered' : '' ?>"
                id="comment-<?= $comment->id ?>"
            >
                <div class="box-header">
                    <div class="comment-author"><?= HtmlBlock::userItem($comment->author) ?></div>
                    <div class="comment-date"><?= Yii::$app->formatter->asDatetime($comment->created_at) ?></div>
                </div>
                <div class="box-body">
                    <?= TextEditorHelper::render($comment, 'text') ?>
                </div>
                <div class="box-footer">
                    <?php if ($comment->created_at != $comment->updated_at) { ?>
                        <div class="comment-edited">
                            Изменнен: <?= Yii::$app->formatter->asDatetime($comment->updated_at) ?>
                        </div>
                    <?php } ?>
                    <?php if($editAnything || $comment->author_id == Yii::$app->user->getId()) { ?>
                        <div class="comment-ctrl">
                            <a class="btn btn-default" href="?edit=<?= $comment->id ?>#comment-<?=$comment->id ?>">
                                <i class="fa fa-edit"></i><?= Yii::t('common', 'Edit') ?>
                            </a>
                            <a class="btn btn-default" href="?delete=<?= $comment->id ?>">
                                <i class="glyphicon glyphicon-trash"></i><?= Yii::t('common', 'Delete') ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

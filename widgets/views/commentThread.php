<?php
/**
 * User: demius
 * Date: 21.11.17
 * Time: 21:56
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\helpers\HtmlBlock;
use app\models\entities\Comment;

/* @var array $comments */
/* @var Comment $newComment */

?>
<div class="comment-thread col-md-5">
    <?php /** @var Comment $comment */ ?>
    <?php foreach ($comments as $comment) { ?>
        <div class="box box-success comment" id="comment-<?=$comment->id?>">
            <div class="box-header">
                <div class="comment-author"><?= HtmlBlock::userItem($comment->author) ?></div>
                <div class="comment-date"><?= Yii::$app->formatter->asDatetime($comment->created_at)?></div>
            </div>
            <div class="box-body">
                <?= $comment->text ?>
                <?php if($comment->updated_at != $comment->updated_at) { ?>
                    <div class="comment-edited">
                        Изменнен: <?= Yii::$app->formatter->asDatetime($comment->updated_at)?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="comment-form">
        <div class="box box-success comment">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Yii::t('comment', 'Create coment') ?>
                </h3>
            </div>
            <div class="box-body">
                <?=$form->field($newComment, 'text')->textarea(['rows' => 10]) ?>
            </div>
            <div class="box-footer">
                <div class="comment-author"><?= HtmlBlock::userItem($newComment->author) ?></div>
                <div class="comment-ctrl">
                    <?= Html::submitButton(
                            $newComment->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Edit'),
                            ['class' => $newComment->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

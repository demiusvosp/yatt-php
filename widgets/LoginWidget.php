<?php
/**
 * User: demius
 * Date: 05.09.18
 * Time: 22:52
 */

namespace app\widgets;


use Yii;
use yii\jui\Widget;
use app\models\forms\LoginForm;


class LoginWidget extends Widget
{
    public $loginForm;
    public $isModal = true;

    public function init()
    {
        parent::init();
        $this->loginForm = new LoginForm();
        $this->loginForm->load(Yii::$app->request->post());
    }


    public function run()
    {
        return $this->render('login', [
            'formModel' => $this->loginForm,
            'isModal' => $this->isModal,
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 14:20
 */

namespace app\controllers;


class ProjectController extends BaseProjectController
{
    public $defaultAction = 'overview';
    public $layout = 'project';

    public function actionOverview()
    {
        return $this->render('overview', ['project' => $this->project]);
    }

}
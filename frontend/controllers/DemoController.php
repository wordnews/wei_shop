<?php

namespace frontend\controllers;

use common\models\CommentArticle;
use common\models\MemberRank;

class DemoController extends \yii\web\Controller
{
    public function init(){
        echo '<pre>';
    }

    public function actionIndex()
    {
        $model = new MemberRank();
        echo $model->getMemberRank(2);
    }
}
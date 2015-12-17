<?php

namespace backend\controllers;

use common\models\Article;
use Yii;
use common\models\ArticleDownload;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DownloadController implements the CRUD actions for ArticleDownload model.
 */
class DownloadController extends CommonController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Creates a new ArticleDownload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($cat_id)
    {
        $model = new ArticleDownload();
        $Article = new Article();

        $postData = Yii::$app->request->post();

        if ($model->load($postData) && $Article->load($postData) && $Article->save() && $model->addData($Article->article_id)) {

            Yii::$app->session->setFlash('success', '添加成功');

            return $this->redirect(['article/index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加文章';

            return $this->render('create', [
                'model' => $model,
                'Article' => $Article
            ]);
        }
    }

    /**
     * Updates an existing ArticleDownload model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $models = $this->findModel($id);
        $model = $models['ArticleDownload'];
        $Article = $models['Article'];

        $postData = Yii::$app->request->post();
        if ($model->load($postData) && $Article->load($postData) && $Article->save() && $model->addData(1)) {

            Yii::$app->session->setFlash('success', '编辑成功');

            return $this->redirect(['article/index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑文章';

            return $this->render('update', [
                'model' => $model,
                'Article' => $Article
            ]);
        }
    }

    /**
     * Finds the ArticleDownload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleDownload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $ArticleDownload = ArticleDownload::findOne($id);
        $Article = Article::findOne($id);

        if ($ArticleDownload !== null && $Article !== null) {
            return ['ArticleDownload' => $ArticleDownload, 'Article' => $Article];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 删除下载模型的文件
     * @param $id
     */
    public function actionDelfile($id){
        if (Yii::$app->request->isAjax) {
            if ($model = ArticleDownload::findOne($id)) {
                $model->file_path = '';
                $model->save(false);
                exit( '1' );
            }
            echo '0';
        }
    }

}

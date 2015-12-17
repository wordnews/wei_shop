<?php

namespace backend\controllers;

use common\models\Article;
use Yii;
use common\models\ArticleArticle;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticlesController implements the CRUD actions for ArticleArticle model.
 */
class ArticlesController extends CommonController
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
     * Creates a new ArticleArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $cat_id 分类id
     * @return mixed
     */
    public function actionCreate($cat_id)
    {
        $model = new ArticleArticle();
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
     * Updates an existing ArticleArticle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $models = $this->findModel($id);
        $model = $models['ArticleArticle'];
        $Article = $models['Article'];

        $postData = Yii::$app->request->post();
        if ($model->load($postData) && $Article->load($postData) && $model->save() && $Article->save()) {

            Yii::$app->session->setFlash('success', '编辑成功。');

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
     * Finds the ArticleArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $ArticleArticle = ArticleArticle::findOne($id);
        $Article = Article::findOne($id);

        if ($ArticleArticle !== null && $Article !== null) {
            return ['ArticleArticle' => $ArticleArticle, 'Article' => $Article];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

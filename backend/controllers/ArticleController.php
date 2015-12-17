<?php

namespace backend\controllers;

use common\models\ArticleCat;
use Yii;
use common\models\Article;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends CommonController
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('article/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $query = Article::find();

        $request = Yii::$app->request;
        if (($cat_id = $request->get('cat_id')) > 0) {
            $query = $query->where('cat_id = :cat_id', [':cat_id' => $cat_id]);
        }
        if (!empty($title = $request->get('title'))) {
            $query = $query->andWhere("title like '%{$title}%'");
        }
        if (($models = $request->get('models')) && $models != '0') {
            $query = $query->andWhere(['model' => $models]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'article_id' => SORT_DESC
                ]
            ]
        ]);

        Yii::$app->view->params['meta_title'] = '文章列表';

        $ArticleCat = new ArticleCat();
        $catList = $ArticleCat->parentNews();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'catList' => $catList
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('article/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $cat_id = $_REQUEST['cat_id'];
        $models = $_REQUEST['models'];

        if ($models == 'article') {
            return $this->redirect(['articles/create', 'cat_id' => $cat_id]);
        } elseif ($models == 'download') {
            return $this->redirect(['download/create', 'cat_id' => $cat_id]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $models)
    {
        if (!$this->is_access('article/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        if ($models == 'article') {
            return $this->redirect(['articles/update', 'id' => $id]);
        } elseif ($models == 'download') {
            return $this->redirect(['download/update', 'id' => $id]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('article/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);
        $model->delete(); // 删除基础信息

        switch ($model->model) {
            case 'article':
                Yii::$app->db->createCommand()->delete('{{%article_article}}', ['id' => $id])->execute();
                break;
            case 'download':
                Yii::$app->db->createCommand()->delete('{{%article_download}}', ['id' => $id])->execute();
                break;
        }
        Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 热门状态修改
     * @param $article_id
     * @param int $status
     */
    public function actionHot($article_id, $status = 1)
    {
        if (!$this->is_access('article/update')) {
            exit;
        }

        $model = new Article();

        if ($model->setHot($article_id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    /**
     * 显示状态修改
     * @param $article_id
     * @param int $status
     */
    public function actionStatus($article_id, $status = 1)
    {
        if (!$this->is_access('article/update')) {
            exit;
        }

        $model = new Article();

        if ($model->setStatus($article_id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }
}

<?php

namespace backend\controllers;

use common\models\Nav;
use Yii;
use common\models\ArticleCat;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticlecatController implements the CRUD actions for ArticleCat model.
 */
class ArticlecatController extends CommonController
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
     * Lists all ArticleCat models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('articlecat/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new ArticleCat();

        Yii::$app->view->params['meta_title'] = '文章分类';

        $catList = $model->parent(0, false);

        return $this->render('index', [
            'model' => $model,
            'list' => $catList
        ]);
    }

    /**
     * Creates a new ArticleCat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('articlecat/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new ArticleCat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', '添加成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加文章分类';

            $catList = $model->parent(1);

            return $this->render('create', [
                'model' => $model,
                'catList' => $catList
            ]);
        }
    }

    /**
     * Updates an existing ArticleCat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('articlecat/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->addData()) {

            Yii::$app->session->setFlash('success', '编辑成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑文章分类';

            $catList = $model->parent(1);

            return $this->render('update', [
                'model' => $model,
                'catList' => $catList
            ]);
        }
    }

    /**
     * Deletes an existing ArticleCat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('articlecat/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new ArticleCat();
        if ($model->find()->where(['parent_id' => $id])->count()) {
            Yii::$app->session->setFlash('error', '删除失败！请先删除当前分类下得子分类。');
        } else {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', '删除成功。');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleCat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleCat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 分类模型单选框
     * @param int $cat_id 分类id
     */
    public function actionHtmlradio($cat_id)
    {
        if ($cat_id < 1) { exit; }

        $model = new ArticleCat();
        $list = $model->cat_model($cat_id);

        $i = 1;
        $html = '';
        foreach ($list as $k => $val) {
            $checked = $i == 1 ? 'checked' : '';
            $html .= '<label><input type="radio" name="models" value="' . $k . '" ' . $checked . ' > ' . $val . '</label> ';
            $i++;
        }
        echo $html;
    }

}

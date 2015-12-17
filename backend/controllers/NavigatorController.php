<?php

namespace backend\controllers;

use Yii;
use common\models\Nav;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NavigatorController implements the CRUD actions for Nav model.
 */
class NavigatorController extends CommonController
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
     * Lists all Nav models.
     * @return mixed
     */
    public function actionIndex($type = 'all')
    {
        if (!$this->is_access('navigator/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        // 位置搜索
        switch (strtolower($type)) {
            case 'top':
                $query = Nav::find()->where(['type' => 'top']);
                break;
            case 'middle':
                $query = Nav::find()->where(['type' => 'middle']);
                break;
            case 'bottom':
                $query = Nav::find()->where(['type' => 'bottom']);
                break;
            default:
                $query = Nav::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        Yii::$app->view->params['meta_title'] = '自定义导航栏';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Nav model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('navigator/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new Nav();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            \backend\models\UserLog::addData('nav', $model->id, "新增导航[{$model->name}]", 'add');

            Yii::$app->session->setFlash('success', '添加成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加导航栏';

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Nav model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('navigator/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            \backend\models\UserLog::addData('nav', $model->id, "编辑导航[{$model->name}]", 'edit');

            Yii::$app->session->setFlash('success', '编辑成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑导航栏';

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Nav model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('navigator/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);
        $model->delete();

        \backend\models\UserLog::addData('nav', $model->id, "删除导航[{$model->name}]", 'delete');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Nav model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Nav the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nav::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // 是否显示状态修改
    public function actionIfshow($id, $status = 1)
    {
        if (!$this->is_access('navigator/update')) {
            exit;
        }

        $model = new Nav();
        if ($model->setIfshow($id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }

    // 是否新窗口打开状态修改
    public function actionOpennew($id, $status = 1)
    {
        if (!$this->is_access('navigator/update')) {
            exit;
        }

        $model = new Nav();
        if ($model->setOpennew($id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }

}

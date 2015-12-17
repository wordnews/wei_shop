<?php

namespace backend\controllers;

use Yii;
use common\models\ShopConfig;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopconfigController implements the CRUD actions for ShopConfig model.
 */
class ShopconfigController extends CommonController
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
     * Lists all ShopConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('shopconfig/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new ShopConfig();

        if (Yii::$app->request->isPost) {
            if (!$this->is_access('shopconfig/index')) {
                Yii::$app->session->setFlash('error', $this->errorInfo);
                return $this->redirect($this->redirectUrl);
            }

            $model->editData($_POST['ShopConfig']);

            $this->redirect(['index']);
        }
        $model->setData();

        Yii::$app->view->params['meta_title'] = '系统设置';

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('shopconfig/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', '编辑成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑配置';

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the ShopConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

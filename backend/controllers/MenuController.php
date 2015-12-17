<?php

namespace backend\controllers;

use Yii;
use common\models\Menu;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends CommonController
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
     * Lists all Menu models.
     * @param int $pid
     * @return mixed
     */
    public function actionIndex($pid = 0)
    {
        if (!$this->is_access('menu/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Menu::find()->where(['pid' => $pid]),
        ]);

        Yii::$app->view->params['meta_title'] = '菜单列表';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('menu/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            \backend\models\UserLog::addData('menu', $model->menu_id, "新增菜单[{$model->title}]", 'add');

            Yii::$app->session->setFlash('success', '创建菜单成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '创建菜单';

            $menuTop = $model->menuTop();

            return $this->render('create', [
                'model' => $model,
                'menuTop' => $menuTop
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('menu/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            \backend\models\UserLog::addData('menu', $model->menu_id, "编辑菜单[{$model->title}]", 'edit');

            Yii::$app->session->setFlash('success', '编辑菜单成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑菜单';

            $menuTop = $model->menuTop();

            return $this->render('update', [
                'model' => $model,
                'menuTop' => $menuTop
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('menu/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);
        $model->delete();

        \backend\models\UserLog::addData('menu', $id, "删除菜单[{$model->title}]", 'delete');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 修改状态
     * @param int $menu_id 菜单id
     * @param int $status
     */
    public function actionStatus($menu_id, $status = 1)
    {
        if (!$this->is_access('menu/update')) {
            exit;
        }

        $model = new Menu();
        if ($model->setStatus($menu_id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\SignupForm;
use common\models\Role;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CommonController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('user/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['<>', 'id', '1']),
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        Yii::$app->view->params['meta_title'] = '管理员列表';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('user/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new SignupForm();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {

            Yii::$app->session->setFlash('success', '添加成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加管理员';
            $role_list = (new Role())->getRole();

            return $this->render('create', [
                'model' => $model,
                'role_list' => $role_list,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('user/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        // 超级管理员不允许在这里被编辑
        if ($id == 1) {
            Yii::$app->session->setFlash('error', '不允许编辑超级管理员');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->editData($_POST['User']['password'])) {

            Yii::$app->session->setFlash('success', '编辑成功');

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑管理员';
            $role_list = (new Role())->getRole();

            return $this->render('update', [
                'model' => $model,
                'role_list' => $role_list,
            ]);
        }
    }

    // 管理员修改自己的账号和密码
    public function actionProfile()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = 'profile';

        if ($model->load(Yii::$app->request->post()) && $model->editProfile()) {

            Yii::$app->session->setFlash('success', '修改成功');

            return $this->redirect(['index']);

        } else {
            Yii::$app->view->params['meta_title'] = '修改资料';

            return $this->render('profile', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('user/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        if ($id != 1) {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', '删除成功');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStatus($id, $status = 1)
    {
        if (!$this->is_access('user/update')) {
            exit;
        }

        $model = new User();
        if ($model->setStatus($id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }

}

<?php

namespace backend\controllers;

use common\helpers\File;
use Yii;
use common\models\FriendLink;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LinksController implements the CRUD actions for FriendLink model.
 */
class LinksController extends CommonController
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
     * Lists all FriendLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('links/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => FriendLink::find(),
        ]);

        Yii::$app->view->params['meta_title'] = '友情链接列表';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FriendLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('links/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new FriendLink();

        if ($model->load(Yii::$app->request->post())) {
            /* 处理上传的图片 */
            if ($_FILES['FriendLink']['error']['link_logo'] === 0) {
                /* 把文件的路劲赋值给image字段 */
                $model->link_logo = File::uploadImage($model, 'link_logo', 'other');
            }
            if ($model->save()) {
                \backend\models\UserLog::addData('friend_link', $model->link_id, "新增友情链接[{$model->link_name}]", 'add');
                Yii::$app->session->setFlash('success', '新增成功');
            }

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加新链接';

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FriendLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('links/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            /* 处理上传的图片 */
            if ($_FILES['FriendLink']['error']['link_logo'] === 0) {
                /* 把文件的路劲赋值给image字段 */
                $model->link_logo = File::uploadImage($model, 'link_logo', 'other');
            }
            if ($model->save()) {
                \backend\models\UserLog::addData('friend_link', $model->link_id, "编辑友情链接[{$model->link_name}]", 'edit');
                Yii::$app->session->setFlash('success', '编辑成功');
            }

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑链接';

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FriendLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('links/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }
        $model = $this->findModel($id);
        $model->delete();

        \backend\models\UserLog::addData('friend_link', $model->link_id, "删除友情链接[{$model->link_name}]", 'delete');

        return $this->redirect(['index']);
    }

    /**
     * Finds the FriendLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FriendLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FriendLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 修改状态
     * @param int $link_id 菜单id
     * @param int $status
     */
    public function actionStatus($link_id, $status = 1)
    {
        if (!$this->is_access('links/update')) {
            exit;
        }

        $model = new FriendLink();
        if ($model->setStatus($link_id, $status)) {
            echo '1';
        } else {
            echo '0';
        }
    }
}

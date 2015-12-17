<?php

namespace backend\controllers;

use Yii;
use frontend\models\MemberAddress;
use common\models\Member;
use common\models\MemberRank;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends CommonController
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!$this->is_access('member/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $requert = Yii::$app->request;
        $username = $requert->get('username');
        $query = Member::find();

        if (!empty($username)) {
            $query = $query->andWhere("username like '%{$username}%'");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'Sort' => [
                'defaultOrder' => ['uid' => SORT_DESC]
            ]
        ]);

        Yii::$app->view->params['meta_title'] = '会员列表';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!$this->is_access('member/create')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = new Member();
        $model->scenario = 'add';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->addData()) {
                    Yii::$app->session->setFlash('success', '添加成功');
                } else {
                    Yii::$app->session->setFlash('error', '添加失败');
                }
            } else {
                $error = \common\helpers\Functions::getErrors($model->errors);
                Yii::$app->session->setFlash('error', $error);
            }

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '添加会员';

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->is_access('member/update')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->editData()) {
                    Yii::$app->session->setFlash('success', '编辑成功');
                } else {
                    Yii::$app->session->setFlash('error', '编辑失败');
                }
            } else {
                $error = \common\helpers\Functions::getErrors($model->errors);
                Yii::$app->session->setFlash('error', $error);
            }

            return $this->redirect(['index']);
        } else {

            Yii::$app->view->params['meta_title'] = '编辑会员';

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('member/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 会员的收获地址列表
     * @param int $id 会员id
     * @return \yii\web\Response
     */
    public function actionAddress($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MemberAddress::find()->where(['uid' => $id]),
            'Sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        Yii::$app->view->params['meta_title'] = '收货地址';

        return $this->render('address', [
            'dataProvider' => $dataProvider,
        ]);
    }
}

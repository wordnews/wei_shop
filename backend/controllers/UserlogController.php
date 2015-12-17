<?php

namespace backend\controllers;

use Yii;
use backend\models\UserLog;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserlogController implements the CRUD actions for UserLog model.
 */
class UserlogController extends CommonController
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
     * Lists all UserLog models.
     * @return mixed
     */
    public function actionIndex($action = '')
    {
        if (!$this->is_access('userlog/index')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $query = UserLog::find();
        if ($action) {
            $query = $query->andWhere(['action' => $action]);
        }
        if (isset($_GET['UserLog'])) {
            $first_time = $_GET['UserLog']['first_time'];
            $first_time = empty($first_time) ? 0 : strtotime($first_time);
            $last_time = $_GET['UserLog']['last_time'];
            $last_time = empty($last_time) ? 2446647875 : strtotime($last_time) + 86400;

            $query->andWhere(['between', 'add_time', $first_time, $last_time]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        Yii::$app->view->params['meta_title'] = '后台操作日志';

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => new UserLog()
        ]);
    }

    /**
     * Deletes an existing UserLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->is_access('userlog/delete')) {
            Yii::$app->session->setFlash('error', $this->errorInfo);
            return $this->redirect($this->redirectUrl);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 列表页批量删除提交的地方
     */
    public function actionDelall()
    {
        if (Yii::$app->request->isAjax) {
            if (UserLog::deleteAll(['id' => array_filter($_GET['id'])])){
                echo '1';
            } else {
                echo '0';
            }
        }
    }
    
}

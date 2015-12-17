<?php
namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * 后台不受权限控制的控制器
 */
class PublicController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * 后台登陆
	 */
	public function actionLogin()
	{
		$model = new LoginForm();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->renderPartial('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * 退出登陆
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->redirect(['login']);
	}

}

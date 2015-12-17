<?php

namespace backend\controllers;

use common\models\Role;
use yii\web\Controller;
use Yii;

/**
 * 后台公共控制器
 */
class CommonController extends Controller
{
    protected $redirectUrl; // 没有权限跳转的地址
    protected $errorInfo = '您没有权限操作'; // 没有权限的错误提示信息

    public function init()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/public/login']);
        }
    }

    /**
     * 检查权限
     * @param string $name 权限标识
     * @return bool
     *
     * if (!$this->is_access('权限节点标识')) {
     *     Yii::$app->session->setFlash('error', $this->errorInfo);
     *     return $this->redirect($this->redirectUrl);
     * }
     */
    protected function is_access($name)
    {
        if ((new Role())->isAccess($name)) {
            return true;
        }
        $url = Yii::$app->request->getReferrer();
        $this->redirectUrl = $url === null ? Yii::$app->homeUrl : $url;
        return false;
    }


}

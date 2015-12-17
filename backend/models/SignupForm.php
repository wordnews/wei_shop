<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个用户名已经被使用了。'],
            ['username', 'string', 'min' => 2, 'max' => 20],

            /*['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],*/

            ['password', 'required', 'on' => ['create']],
            ['password', 'string', 'min' => 6],

            [['role_id'], 'integer'],
            ['role_id', 'default', 'value' => function(){
                return 0;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '管理员账号',
            'password' => '管理员密码',
            'role_id' => '角色选择',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->role_id = $this->role_id;
//            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save(false)) {
                return $user;
            }
        }

        return null;
    }

    public function getFindModel($id){
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

<?php

namespace backend\models;

use Yii;

class User extends \yii\db\ActiveRecord
{
    public $password;
    public $repassword;
    public $newpassword;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

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

            ['password', 'required', 'on' => ['create', 'profile']],
            [['password', 'newpassword', 'repassword'], 'string', 'min' => 6],
            ['repassword', 'check_password', 'on' => ['profile']],
            ['password', 'check_is_password', 'on' => ['profile']],

            [['role_id'], 'integer'],
            ['role_id', 'default', 'value' => function(){
                return 0;
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '管理员账号',
            'password' => '管理员密码',
            'repassword' => '确认密码',
            'newpassword' => '新密码',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role_id' => '角色选择'
        ];
    }

    /**
     * 修改管理员信息
     * @param $password string 新密码
     * @return bool
     */
    public function editData($password = ''){
        if ($this->validate()) {
            if (!empty($password)) {
                $User = new \common\models\User();
                $this->password_hash = $User->setPasswordShop($password);
            }
            return $this->save(false);
        }
        return false;
    }

    // 修改资料
    public function editProfile(){
        if ($this->validate()) {
            $User = new \common\models\User();
            if (!empty($this->newpassword)) {
                $this->password_hash = $User->setPasswordShop($this->newpassword);
            }
            return $this->save(false);
        }
        return false;
    }

    public function setStatus($id, $status = 1)
    {
        if (!in_array($status, [0, 1]) || $id == 1) {
            return false;
        }
        return User::updateAll(['status' => $status], 'id = :id', [':id' => $id]);
    }

    // 新密码与确认密码是否一致
    public function check_password($attribute)
    {
        if (!empty($this->newpassword) && !$this->hasErrors()) {
            if ($this->repassword !== $this->newpassword) {
                $this->addError($attribute, '确认密码与新密码不一致');
            }
        }
    }

    // 密码是否输入正确
    public function check_is_password($attribute)
    {
        if (!$this->hasErrors()) {
            if (!Yii::$app->security->validatePassword($this->password, $this->password_hash)) {
                $this->addError($attribute, '原密码验证失败');
            }
        }
    }

}

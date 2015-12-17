<?php

namespace common\models;

use Yii;

/**
 * 会员表模型
 */
class Member extends \yii\db\ActiveRecord
{
    public $repassword; // 确认密码
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['add']],
            [['sex', 'reg_time', 'last_login', 'login_count'], 'integer'],
            [['money'], 'number'],
            [['email', 'username'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 80],
            [['username', 'email'], 'unique'],
            [['username'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [['username'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => '确认密码与密码不一致'],
            [['reg_time'], 'default', 'value' => function($model, $attribute){
                return time();
            }],

            [['sex'], 'default', 'value' => function(){
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
            'uid' => 'Id',
            'email' => '会员邮箱',
            'username' => '会员名称',
            'password' => '登陆密码',
            'repassword' => '确认密码',
            'sex' => '性别',
            'money' => '可用资金',
            'score' => '积分',
            'reg_time' => '注册时间',
            'last_login' => '最后一次登录时间',
            'login_count' => '登录次数',
            'rank_id' => '会员等级',
        ];
    }

    /**
     * 新增会员
     * @return bool
     */
    public function addData(){
        $User = new User();

        $this->password = $User->setPasswordShop($this->password);
        $this->auth_key = $User->generateAuthKeyShop();

        if ($this->save(false)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 编辑会员
     * @return bool
     */
    public function editData(){
        if ($this->password && strlen($this->password) > 5) {
            $User = new User();
            $this->password = $User->setPasswordShop($this->password);
        } else {
            unset($this->password);
        }
        if ($this->save(false)) {
            return true;
        } else {
            return false;
        }
    }


}

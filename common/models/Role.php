<?php

namespace common\models;

use Yii;

class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name'], 'required'],
            [['action_list'], 'string'],
            [['role_name'], 'string', 'max' => 60],
            [['role_descript'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => '角色名称',
            'role_descript' => '角色描述',
            'action_list' => '权限标识列表',
        ];
    }

    /**
     * 新增（编辑）数据
     * @return bool
     */
    public function addData()
    {
        if ($this->validate()) {
            if ($_POST['actions_list']) {
                $this->action_list = implode(',', $_POST['actions_list']);
            }
            return $this->save(false);
        }
        return false;
    }

    /**
     * 字符串（$str）是否在字符串($strList)出现
     * @param $strList
     * @param $str
     * @return bool
     */
    public function isChecked($strList, $str)
    {
        $arr = explode(',', $strList);
        return in_array($str, $arr);
    }

    // 获取所有角色
    public function getRole(){
        return $this->find()->orderBy('role_id DESC')->asArray()->all();
    }

    /**
     * 检查权限
     * @param string $name 权限节点标识
     * @return bool
     */
    public function isAccess($name)
    {
        $user = Yii::$app->user;
        if ($user->id == 1) { // 超级管理员不检查权限
            return true;
        }
        $role_id = $user->identity->role_id;
        if ($role_id > 0 && !empty($name)) {
            // 检查权限
            $role_list = $this->getActionList($role_id);
            if (in_array($name, $role_list)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获得角色的权限节点
     * @param int $role_id 角色id
     * @return array|mixed
     */
    public function getActionList($role_id)
    {
        if (($actionList = Yii::$app->cache->get('role_list_' . $role_id)) === false) {
            $actionList = self::findOne($role_id)->action_list;
            $actionList = explode(',', $actionList);
            Yii::$app->cache->set('role_list_' . $role_id, $actionList, 20);
        }
        return $actionList;
    }

}

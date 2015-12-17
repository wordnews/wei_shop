<?php

namespace common\models;

use Yii;

class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'upid'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '地区id',
            'name' => '地区名称',
            'level' => '地区级数',
            'upid' => '上级地区id',
        ];
    }

    /**
     * 获取中国省份信息（一级地区）
     * @param int $pid 地区id（默认选中的id）
     * @return string
     */
    public function getProvince($pid = 0)
    {
        $list = $this->find()->where(['level' => 1, 'upid' => 0])->asArray()->all();
        $data = "<option value =''>-省份-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if( $pid == $vo['id'] ){
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        return $data;
    }

    /**
     * 获取城市信息（二级地区）
     * @param int $pid 省份id
     * @param int $cid 城市id（作为默认选中的城市id）
     * @return string
     */
    public function getCity($pid, $cid = 0)
    {
        $list = $this->find()->where(['level' => 2, 'upid' => $pid])->asArray()->all();
        $data = "<option value =''>-城市-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if( $cid == $vo['id'] ){
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        return $data;
    }

    /**
     * 获取区县市信息
     * @param int $cid 城市id
     * @param int $did 区县id（用来默认选中状态的）
     * @return string
     */
    public function getDistrict($cid, $did = 0)
    {
        $list = $this->find()->where(['level' => 3, 'upid' => $cid])->asArray()->all();
        $data = "<option value =''>-区县-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if( $did == $vo['id'] ){
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        return $data;
    }

}

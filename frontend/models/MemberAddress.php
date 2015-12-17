<?php

namespace frontend\models;

use Yii;

class MemberAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'province', 'city', 'district', 'mobile', 'default'], 'integer'],
            [['consignee', 'address', 'mobile'], 'required'],
            [['consignee', 'zipcode'], 'string', 'max' => 60],
            [['address'], 'string', 'max' => 120],
            [['mobile'], 'string', 'min' => 11, 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'uid' => 'Uid',
            'consignee' => '收货人的名字',
            'province' => '收货人的省份',
            'city' => '收货人的城市',
            'district' => '收货人的地区',
            'address' => '详细地址',
            'zipcode' => '邮编',
            'mobile' => '手机号码',
            'default' => '默认地址',
        ];
    }

    /**
     * 获取会员的收货地址列表
     * @param int $uid 会员id
     * @return mixed
     */
    public function getMemberAddress($uid)
    {
        return $this::find()->where(['uid' => $uid])->asArray()->all();
    }

    /**
     * 获取一条收货地址信息（无条件限制）
     * @param int $id 收货地址id
     * @return array
     */
    public function getOne($id)
    {
        return $this::find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * 获取一条收货地址信息（只能获取自己的）
     * @param int $id 收货地址id
     * @param int $uid 会员id
     * @return mixed
     */
    public function getMemberOne($id, $uid)
    {
        return $this::find()->where(['id' => $id, 'uid' => $uid])->asArray()->one();
    }
}

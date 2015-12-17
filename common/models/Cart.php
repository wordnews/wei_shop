<?php

namespace common\models;

use Yii;

/**
 * 购物车模型
 * Class Cart
 * @package common\models
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'goods_id', 'goods_number', 'is_real', 'type'], 'integer'],
            [['market_price', 'goods_price'], 'number'],
            [['goods_attr'], 'required'],
            [['goods_attr'], 'string'],
            [['goods_sn'], 'string', 'max' => 60],
            [['goods_name'], 'string', 'max' => 120]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键自增ID',
            'uid' => '会员id',
            'goods_id' => '商品id',
            'goods_sn' => '商品货号',
            'goods_name' => '商品名称',
            'market_price' => '商品的市场价',
            'goods_price' => '商品的本店价',
            'goods_number' => '商品的购买数量',
            'goods_attr' => '商品的属性',
            'is_real' => '是否为实体商品 1=》实体，0=》虚拟',
            'type' => '购物车商品类型，0，普通；1，团够；2，拍卖；3，夺宝奇兵',
        ];
    }

}

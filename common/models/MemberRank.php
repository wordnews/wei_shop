<?php

namespace common\models;

use Yii;

class MemberRank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_rank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank_name', 'score'], 'required'],
            [['rank_name'], 'unique'],
            [['score'], 'integer'],
            [['rank_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rank_id' => 'Rank ID',
            'rank_name' => '等级名称',
            'score' => '所需积分',
        ];
    }

    /**
     * 获得会员的等级
     * @param int $uid 会员id
     * @return string
     */
    public function getMemberRank($uid)
    {
        $uScore = Member::findOne($uid)->score;
        $list = $this->find()->orderBy('score DESC')->all();

        $title = '';
        foreach ($list as $val) {
            if ($uScore >= $val['score']) {
                $title = $val['rank_name'];
                break;
            }
        }
        return $title;
    }

}

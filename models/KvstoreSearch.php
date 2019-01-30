<?php
/**
 * yiiplus/yii2-kvstore
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\kvstore\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 搜索
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class KvstoreSearch extends Kvstore
{
    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['active'], 'boolean'],
            [['group', 'key', 'value'], 'safe'],
        ];
    }

    /**
     * 场景
     *
     * @return Model::scenarios
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 查询
     *
     * @param array $params 请求参数
     *
     * @return object
     */
    public function search($params)
    {
        $query = Kvstore::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'active' => $this->active,
                'group' => $this->group,
            ]
        );

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}

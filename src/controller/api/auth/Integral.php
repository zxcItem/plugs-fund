<?php

declare (strict_types=1);

namespace plugin\fund\controller\api\auth;

use plugin\account\controller\api\Auth;
use plugin\fund\model\PluginFundIntegral;
use think\admin\helper\QueryHelper;

/**
 * 积分数据接口
 * @class Integral
 * @package plugin\fund\controller\api\auth
 */
class Integral extends Auth
{
    /**
     * 获取余额记录
     * @return void
     */
    public function get()
    {
        PluginFundIntegral::mQuery(null, function (QueryHelper $query) {
            $query->where(['unid' => $this->unid, 'deleted' => 0, 'cancel' => 0])->order('id desc');
            $this->success('获取积分记录！', $query->page(intval(input('page', 1)), false, false, 20));
        });
    }
}
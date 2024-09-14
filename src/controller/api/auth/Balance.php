<?php

declare (strict_types=1);

namespace plugin\fund\controller\api\auth;

use plugin\account\controller\api\Auth;
use plugin\fund\model\PluginFundBalance;
use think\admin\helper\QueryHelper;

/**
 * 余额数据接口
 * @class Balance
 * @package plugin\fund\controller\api\auth
 */
class Balance extends Auth
{
    /**
     * 获取余额记录
     * @return void
     */
    public function get()
    {
        PluginFundBalance::mQuery(null, function (QueryHelper $query) {
            $query->where(['unid' => $this->unid, 'deleted' => 0, 'cancel' => 0])->order('id desc');
            $this->success('获取余额记录！', $query->page(intval(input('page', 1)), false, false, 20));
        });
    }
}
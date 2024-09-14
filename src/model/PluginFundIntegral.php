<?php

declare (strict_types=1);

namespace plugin\fund\model;

/**
 * 用户积分模型
 * @class PluginFundIntegral
 * @package plugin\fund\model
 */
class PluginFundIntegral extends PluginFundBalance
{
    /**
     * 积分扩展数据
     * @var array[]
     */
    public static $Types = [
        ['value' => '充值积分', 'amount' => 0, 'name' => 'integral_total'],
        ['value' => '锁定积分', 'amount' => 0, 'name' => 'integral_lock'],
        ['value' => '剩余积分', 'amount' => 0, 'name' => 'integral_usable'],
        ['value' => '支出积分', 'amount' => 0, 'name' => 'integral_used'],
    ];
}
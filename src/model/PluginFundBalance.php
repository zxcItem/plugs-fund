<?php

declare (strict_types=1);

namespace plugin\fund\model;

use plugin\account\model\Abs;
use plugin\account\model\PluginAccountUser;
use think\model\relation\HasOne;

/**
 * 用户余额模型
 * @class PluginFundBalance
 * @package plugin\fund\model
 */
class PluginFundBalance extends Abs
{
    /**
     * 余额扩展数据
     * @var array[]
     */
    public static $Types = [
        ['value' => '充值余额', 'amount' => 0, 'name' => 'balance_total'],
        ['value' => '锁定余额', 'amount' => 0, 'name' => 'balance_lock'],
        ['value' => '剩余余额', 'amount' => 0, 'name' => 'balance_usable'],
        ['value' => '支出余额', 'amount' => 0, 'name' => 'balance_used'],
    ];

    /**
     * 关联用户数据
     * @return \think\model\relation\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(PluginAccountUser::class, 'id', 'unid');
    }

    /**
     * 格式化输出时间
     * @param mixed $value
     * @return string
     */
    public function getCancelTimeAttr($value): string
    {
        return format_datetime($value);
    }

    public function setCancelTimeAttr($value): string
    {
        return $this->setCreateTimeAttr($value);
    }

    /**
     * 格式化输出时间
     * @param mixed $value
     * @return string
     */
    public function getUnlockTimeAttr($value): string
    {
        return format_datetime($value);
    }

    public function setUnlockTimeAttr($value): string
    {
        return $this->setCreateTimeAttr($value);
    }
}
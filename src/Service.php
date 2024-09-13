<?php

declare (strict_types=1);

namespace plugin\fund;

use think\admin\Plugin;

/**
 * 组件注册服务
 * @class Service
 * @package app\fund
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '资金管理';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'xiaochao/plugs-fund';


    /**
     * 签到模块菜单配置
     * @return array[]
     */
    public static function menu(): array
    {
        // 设置插件菜单
        $code = app(static::class)->appCode;
        // 设置插件菜单
        return [
            [
                'name' => '资金管理',
                'subs' => [
                    ['name' => '用户资产管理', 'icon' => 'layui-icon layui-icon-dollar', 'node' => "{$code}/master/index"],
                    ['name' => '用户余额管理', 'icon' => 'layui-icon layui-icon-cellphone', 'node' => "{$code}/balance/index"],
                    ['name' => '用户积分管理', 'icon' => 'layui-icon layui-icon-find-fill', 'node' => "{$code}/integral/index"],
                ],
            ]
        ];
    }
}
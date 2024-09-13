<?php

declare (strict_types=1);

namespace plugin\fund\controller;

use plugin\account\model\PluginAccountUser;
use plugin\fund\model\PluginFundIntegral;
use plugin\fund\service\Integral as IntegralService;
use think\admin\Controller;
use think\admin\extend\CodeExtend;
use think\admin\helper\QueryHelper;
use think\admin\service\AdminService;
use think\exception\HttpResponseException;

/**
 * 积分明细管理
 * @class Integral
 * @package plugin\fund\controller
 */
class Integral extends Controller
{
    /**
     * 积分明细管理
     * @auth true
     * @menu true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $this->type = $this->get['type'] ?? 'index';
        PluginFundIntegral::mQuery()->layTable(function () {
            $this->title = '积分明细管理';
            $map = ['cancel' => 0, 'deleted' => 0];
            $this->integralTotal = PluginFundIntegral::mk()->where($map)->whereRaw("amount>0")->sum('amount');
            $this->integralCount = PluginFundIntegral::mk()->where($map)->whereRaw("amount<0")->sum('amount');
        }, function (QueryHelper $query) {
            $db = PluginAccountUser::mQuery()->like('email|nickname|username|phone#user')->db();
            if ($db->getOptions('where')) $query->whereRaw("unid in {$db->field('id')->buildSql()}");
            $query->with(['user'])->like('code,remark')->dateBetween('create_time');
            $query->where(['deleted' => 0, 'cancel' => intval($this->type !== 'index')]);
        });
    }

    /**
     * 积分充值
     * @auth true
     */
    public function add()
    {
        PluginFundIntegral::mForm('form');
    }

    /**
     * 表单回调处理
     * @param array $data
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function _form_filter(array &$data)
    {
        if (empty($data['code'])) {
            $data['code'] = CodeExtend::uniqidNumber(16, 'CZ');
        }
        if ($this->request->isGet()) {
            $data['unid'] = $data['unid'] ?? input('unid', 0);
            $this->user = PluginAccountUser::extraItem(intval($data['unid']),PluginFundIntegral::$Types);
            if (empty($this->user)) $this->error('无效用户信息！');
        } else try {
            $data = $this->_vali([
                'name.default'   => '平台充值',
                'code.require'   => '单号不能为空！',
                'unid.require'   => '用户不能为空！',
                'amount.require' => '金额不能为空！',
                'remark.default' => '后台余额操作！',
            ], $data);
            if (empty(floatval($data['amount']))) {
                $this->error('充值金额不能为零！');
            }
            $this->app->db->transaction(static function () use ($data) {
                $data['create_by'] = AdminService::getUserId();
                // 创建余额变更
                IntegralService::create(intval($data['unid']), $data['code'], $data['name'], floatval($data['amount']), $data['remark'], true);
            });
            $this->success('积分充值成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }


    /**
     * 交易锁定处理
     * @auth true
     */
    public function unlock()
    {
        try {
            $data = $this->_vali([
                'code.require'   => '单号不能为空！',
                'unlock.require' => '状态不能为空！'
            ]);
            IntegralService::unlock($data['code'], intval($data['unlock']));
            $this->success('交易操作成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * 交易状态处理
     * @auth true
     */
    public function cancel()
    {
        try {
            $data = $this->_vali([
                'code.require'   => '单号不能为空！',
                'cancel.require' => '状态不能为空！'
            ]);
            IntegralService::cancel($data['code'], intval($data['cancel']));
            $this->success('交易操作成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * 删除余额记录
     * @auth true
     */
    public function remove()
    {
        try {
            $data = $this->_vali([
                'code.require' => '单号不能为空！',
            ]);
            IntegralService::remove($data['code']);
            $this->success('交易操作成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
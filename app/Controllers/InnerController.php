<?php
/**
 * @desc: 内部请求控制器基类
 * @author: leandre <niulingyun@camera360.com>
 * @date: 2017/2/9
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\Helper\ParameterValidatorHelper;
use PG\MSF\Controllers\BaseController;
use PG\Filter\{
    Request, Acf\AccessControl
};

class InnerController extends BaseController
{
    public function initialization($controllerName, $methodName)
    {
        parent::initialization($controllerName, $methodName);
        $this->runFilter($controllerName, $methodName);
        $this->PGLog->pushLog('params', $this->input->getAllPostGet());
        return true;
    }

    /**
     * 执行filter逻辑
     * @throws \Exception
     */
    protected function runFilter($controllerName, $methodName)
    {
        if (empty($this->filters())) {
            return;
        }

        /* @var $request Request */
        $request             = $this->objectPool->get(Request::class);
        $request->ip         = $this->input->getRemoteAddr();
        $request->controller = $controllerName;
        $request->method     = $methodName;

        foreach ($this->filters() as $filter) {
            if (!isset($filter['class'])) {
                throw new \Exception('Filter config need class prop.');
            }
            $clazz = $this->objectPool->get($filter['class']);
            unset($filter['class']);
            foreach ($filter as $prop => $val) {
                $clazz->$prop = $val;
            }

            if (call_user_func([$clazz, 'beforeMethod'], $request) === false) {
                $clazz->denyCallback();
            }
        }
    }
    /**
     * 过滤器
     * [
     *     'access' => [
     *         'class' => AccessCheckFilter::class,
     *         'rules' => $this->accessRules()
     *     ]
     * ]
     * @return array
     */
    protected function filters()
    {
        // 注意先后顺序，建议 CheckCommonParameters 放在最前面
        return [
            'access' => [
                'class'        => AccessControl::class,
                'rules'        => $this->accessRules(),
                'denyCallback' => [$this, 'denyCallback'],
            ]
        ];
    }
    /**
     * 访问控制 Acf 规则
     * [
     *     [
     *         'allow' => true,
     *         'methods' => [],
     *         'roles' => ['@'], // @：登录用户；?：guest 用户；如果不设置 roles 属性则所有用户均通过
     *         'denyCallback' => [$this, 'accessDenied'],
     *     ]
     * ];
     */
    protected function accessRules()
    {
        return [
            [
                'ips' => [
                    '10.*.*.*',
                    '127.0.0.1',
                    '172.17.*.*',
                    '182.150.28.13',
                    '101.204.240.34',
                    '182.150.56.79',
                    '54.223.161.219',
                    '54.223.196.60',
                ],
                'methods' => [],
                'allow' => true,
                'roles' => ['?'],
            ],
        ];
    }

    public function denyCallback($user, $request)
    {
        throw new \Exception("inner api", \PG\Exception\Errno::PRIVILEGE_NOT_PASS);
    }
}

<?php
/**
 * 测试框架基类
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 12/10/2017
 */

namespace Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class BaseTestCase extends TestCase
{
    public static $__serverHost;

    /** @var ResponseInterface */
    public static $response = null;

    public static function setUpBeforeClass()
    {
        self::$__serverHost = $GLOBALS['SERVER_HOST'];
    }
}

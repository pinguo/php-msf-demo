<?php
/**
 * monitor接口测试
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 12/10/2017
 */

namespace Test\Base\Init;

use GuzzleHttp\Client;
use Test\BaseTestCase;
use Test\TraitTestCase;

class MonitorTest extends BaseTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $client = new Client([
            'base_uri' => self::$__serverHost,
            'timeout' => 100,
        ]);
        self::$response = $client->get('monitor');
    }

    public function testHttpBase()
    {
        $this->assertEquals(200, self::$response->getStatusCode());
        $this->assertStringMatchesFormat('%s', self::$response->getHeader('X-Ngx-Logid')[0]);

        return self::$response->getBody()->getContents();
    }

    /**
     * @depends testHttpBase
     * @param string $body
     * @return mixed
     */
    public function testResult(string $body)
    {
        $this->assertJson($body);
        $result = json_decode($body, true);
        $this->assertNotEmpty($result);

        return $result;
    }

    /**
     * @depends testResult
     * @param array $result
     */
    public function testWorker(array $result)
    {
        $this->assertArrayHasKey('worker', $result);
        $worker = $result['worker'];

        $this->assertArrayHasKey('worker0', $worker);

        $worker0 = $worker['worker0'];

        $this->assertArrayHasKey('pid', $worker0);
        $this->assertArrayHasKey('coroutine', $worker0);
        $this->assertArrayHasKey('memory', $worker0);
        $this->assertArrayHasKey('request', $worker0);
        $this->assertArrayHasKey('object_pool', $worker0);
        $this->assertArrayHasKey('dns_cache_http', $worker0);
        $this->assertArrayHasKey('exit', $worker0);

        $this->assertArrayHasKey('total', $worker0['coroutine']);

        $this->assertArrayHasKey('peak', $worker0['memory']);
        $this->assertArrayHasKey('usage', $worker0['memory']);
        $this->assertArrayHasKey('peak_byte', $worker0['memory']);
        $this->assertArrayHasKey('usage_byte', $worker0['memory']);

        $this->assertArrayHasKey('worker_request_count', $worker0['request']);

        $this->assertNotEmpty($worker0['object_pool']);
    }

    /**
     * @param array $result
     * @depends testResult
     */
    public function testTcp(array $result)
    {
        $this->assertArrayHasKey('tcp', $result);
        $tcp = $result['tcp'];

        $this->assertArrayHasKey('start_time', $tcp);
        $this->assertArrayHasKey('connection_num', $tcp);
        $this->assertArrayHasKey('accept_count', $tcp);
        $this->assertArrayHasKey('close_count', $tcp);
        $this->assertArrayHasKey('tasking_num', $tcp);
        $this->assertArrayHasKey('request_count', $tcp);
    }

    /**
     * @param array $result
     * @depends testResult
     */
    public function testRunning(array $result)
    {
        $this->assertArrayHasKey('running', $result);
        $running = $result['running'];

        $this->assertArrayHasKey('qps', $running);
        $this->assertArrayHasKey('last_qpm', $running);
        $this->assertArrayHasKey('qpm', $running);
        $this->assertArrayHasKey('concurrency', $running);
    }

    /**
     * @param array $result
     * @depends testResult
     */
    public function testSysCache(array $result)
    {
        $this->assertArrayHasKey('sys_cache', $result);
        $this->assertNotEmpty($result['sys_cache']);
    }
}

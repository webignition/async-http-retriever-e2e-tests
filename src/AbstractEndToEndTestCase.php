<?php

namespace WebResourceCacheEndToEndTests;

use GuzzleHttp\Client as HttpClient;

abstract class AbstractEndToEndTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    protected function setUp()
    {
        $this->httpClient = new HttpClient();
    }

    protected function clearRedis()
    {
        $output = array();
        $returnValue = null;

        exec('redis-cli -r 1 flushall', $output, $returnValue);

        if ($output !== array('OK')) {
            return false;
        }

        return $returnValue === 0;
    }
}

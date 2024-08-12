<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/8/2024
 * @time: 7:37 PM
 */

namespace unit\modules\fees\controllers\web;

use Codeception\Test\Unit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FeesStructureTest extends Unit
{
    private ?Client $http;

    public function _before()
    {
        $this->http = new Client([
            'base_url' => 'http://localhost:81/smis/web/fees-management/fees-structure/'
        ]);
    }

    public function _after()
    {
        $this->http = null;
    }

    /**
     * @throws GuzzleException
     */
    public function testIndex()
    {
        $response = $this->http->request('GET', 'http://localhost:81/smis/web/fees-management/fees-structure');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
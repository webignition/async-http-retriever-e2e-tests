<?php

namespace WebResourceCacheEndToEndTests;

use GuzzleHttp\Exception\ClientException;

class RequestResourceTest extends AbstractEndToEndTestCase
{
    const URL = 'http://127.0.0.1:8000';

    public function testGetRequest()
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(405);

        $this->httpClient->get(self::URL);
    }

    /**
     * @dataProvider badRequestDataProvider
     *
     * @param array $postData
     */
    public function testBadRequest(array $postData)
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);

        $this->httpClient->post(
            self::URL,
            [
                'form_params' => $postData,
            ]
        );
    }

    public function badRequestDataProvider(): array
    {
        return [
            'no request data' => [
                'requestData' => [],
            ],
            'empty url' => [
                'requestData' => [
                    'url' => '',
                ],
            ],
            'missing callback url' => [
                'requestData' => [
                    'url' => 'http://example.com/',
                ],
            ],
            'empty callback url' => [
                'requestData' => [
                    'url' => 'http://example.com/',
                    'callback' => '',
                ],
            ],
            'invalid callback url' => [
                'requestData' => [
                    'url' => 'http://example.com/',
                    'callback' => 'foo',
                ],
            ],
        ];
    }

    public function testSuccessfulRequest()
    {
        $response = $this->httpClient->post(
            self::URL,
            [
                'form_params' => [
                    'url' => 'http://example.com/',
                    'callback' => 'http://callback.localhost/',
                ],
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('content-type'));

        $responseData = json_decode($response->getBody()->getContents());

        $this->assertRegExp('/[a-f0-9]{32}/', $responseData);
    }
}

<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Supports;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

use Hyperf\Guzzle\ClientFactory;
// use Hyperf\Guzzle\HandlerStackFactory;
use myttyy\Hyperf\Payment\Helpers\DataParser;

/**
 * Trait HasHttpRequest.
 */
trait HttpRequest
{
    /**
     * 设置请求选项
     * @var array
     */
    private $options = [];

    /**
     * @param string $url
     * @param array $query
     * @param array $headers
     * @return mixed|string
     */
    protected function get(string $url, array $query = [], array $headers = [])
    {
        return $this->sendRequest('get', $url, [
            'headers'     => $headers,
            'query'       => $query,
            'http_errors' => false,
        ]);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return mixed|string
     */
    protected function post(string $url, array $params = [], array $headers = [])
    {
        return $this->sendRequest('post', $url, [
            'headers'     => $headers,
            'form_params' => $params,
            'http_errors' => false,
        ]);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return mixed|string
     */
    protected function postJson(string $url, array $params = [], array $headers = [])
    {
        return $this->sendRequest('post', $url, [
            'headers'     => $headers,
            'json'        => $params,
            'http_errors' => false,
        ]);
    }

    /**
     * @param string $url
     * @param string $xmlData
     * @param array $headers
     * @return mixed|string
     */
    protected function postXML(string $url, string $xmlData, array $headers = [])
    {
        return $this->sendRequest('post', $url, [
            'headers'     => $headers,
            'body'        => $xmlData,
            'http_errors' => false,
        ]);
    }

    /**
     * 发送表单数据
     * @param string $url
     * @param array $formData
     * @param array $headers
     * @return array|mixed|ResponseInterface|string
     */
    protected function postForm(string $url, array $formData, array $headers = [])
    {
        return $this->sendRequest('post', $url, [
            'headers'     => $headers,
            'multipart'   => $formData,
            'http_errors' => false,
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array|ResponseInterface|string|mixed
     */
    private function sendRequest(string $method, string $url, array $options = [])
    {
        return $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->{$method}($url, $options));
    }

    /**
     * @param array $options
     */
    protected function setHttpOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    private function getBaseOptions()
    {
        $options = [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout'  => method_exists($this, 'getTimeout') ? $this->getTimeout() : 30.0,
        ];

        $options = array_merge($options, $this->options);
        return $options;
    }

    /**
     * myttyy 这里修改为使用
     * @param array $options
     * @return Client
     */
    private function getHttpClient(array $options = [])
    {

        $clientFactory = $this->container()->get(ClientFactory::class);
        return $clientFactory->create($options);

        // $factory = new HandlerStackFactory();
        // $stack = $factory->create($options);
        // return make(Client::class, [
        //     'config' => [
        //         'handler' => $stack,
        //     ],
        // ]);

        // 替换注入GuzzleHttp\Client客户端，以协程方式实现
        // return new Client($options);
    }

    /**
     * 获取系统容器实例
     *
     * @return \Psr\Container\ContainerInterface
     */
    private function container(): \Psr\Container\ContainerInterface
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
    }

    /**
     * @param ResponseInterface $response
     * @return mixed|string
     */
    private function unwrapResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents    = $response->getBody()->getContents();

        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        } elseif (false !== stripos($contentType, 'xml')) {
            return DataParser::toArray($contents);
        }

        return $contents;
    }
}

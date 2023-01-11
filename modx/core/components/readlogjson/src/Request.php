<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 26.09.2022
 * Time: 12:27
 */

namespace Readlogjson;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Query;

class Request
{

    public function getResponse()
    {
        return [
            'status' => '',
        ];
    }


    public function send($url, $method, $data = null, $timeout)
    {


        $method = mb_strtoupper($method);

        $status = 0;
        $body = null;
        $e = null;
        try {
            $client = new \GuzzleHttp\Client(['verify' => false]);

            $options = [
                'timeout' => $timeout,
                'headers' => [
                    'User-Agent' => 'Mozilla/1.0.0'
                ],
            ];

            if (is_array($data)) {
                if ($method === 'GET' && !empty($data)) {
                    $url .= '?' . Query::build($data);
                } else {
                    $options['json'] = $data;
                }
            }


            $response = $client->request($method, $url, $options);

            $status = $response->getStatusCode();
        } catch (BadResponseException $e) {
            $error = true;
            $status = 500;
        } catch (RequestException $e) {
            $error = true;
            $status = 400;
        } catch (\Exception $e) {
            $error = true;
        }

        if ($e) {
            if ($e->getCode()) {
                $status = $e->getCode();
            }

            if (method_exists($e, 'hasResponse')) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                }
            }

            $msg = $e->getMessage();
        }


        if ($status === 200) {
            if (method_exists($response, 'getBody')) {
                #  $content = $response->getBody();
                $res = $response->getBody()->getContents();
            }

        } else {
            $res = [
                'status_error' => true,
                'status_code' => $status,
                'msg' => $msg
            ];
        }
        return $res;
    }
}

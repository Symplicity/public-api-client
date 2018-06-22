<?php
namespace Symplicity\PublicApiClient;

class ApiClient
{
    private $instance;
    private $token;
    private $client;

    public function __construct($instance, $token)
    {
        $this->setInstance($instance);
        $this->token = $token;
    }

    public function getStudent($id, $params = [])
    {
        $data = $this->get("students/$id", $params);
        return $data;
    }

    public function getStudents($filters = [])
    {
        $data = $this->get('students', $filters);
        return $data;
    }

    public function saveJob($data, $args = [])
    {
        $data = $this->create('jobs', $data, $args);
        return $data;
    }

    private function get($route, $params = [])
    {
        $this->initClient();

        try {
            if (count($params)) {
                $params = ['query' => $params];
            }
            $params['headers'] = $this->getHeaders();

            $url = $this->prepareRouteUrl($route);

            $response = $this->client->get($url, $params);
            $httpCode = $response->getStatusCode();
            if ($httpCode === 200) {
                return $this->decodeOutput($response);
            }
            throw $this->getException($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function create($route, $data, $args = [])
    {
        $this->initClient();

        try {
            $params = [
                'http_errors' => false,
                'headers' => $this->getHeaders(),
                'json' => $data
            ];

            $url = $this->prepareRouteUrl($route, $args);

            $response = $this->client->post($url, $params);
            $httpCode = $response->getStatusCode();
            if (in_array($httpCode, [200, 201])) {
                return $this->decodeOutput($response);
            }
            throw $this->getException($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function initClient()
    {
        if (!$this->client) {
            $this->client = new \GuzzleHttp\Client();
        }
    }

    private function prepareRouteUrl($route, array $args = [])
    {
        $url = "{$this->instance}/api/public/v1/$route";
        if (!empty($args['bulk'])) {
            $url .= '?bulk=1';
        }
        return $url;
    }

    private function getHeaders()
    {
        return [
            'content-type' => 'application/json',
            'Authorization' => "Token {$this->token}"
        ];
    }

    private function decodeOutput($data)
    {
        $data = $data->getBody()->getContents();
        $data = json_decode($data, true);
        if (empty($data)) {
            $data = [];
        }
        return $data;
    }

    private function isResponseHtml($string)
    {
        return ($string != strip_tags($string));
    }

    private function setInstance($url)
    {
        $this->instance = $url;
        if (substr($this->instance, -1) === '/') {
            $this->instance = substr($this->instance, 0, strlen($this->instance) - 1);
        }
    }

    private function getException($response)
    {
        $e = new \Symplicity\PublicApiClient\ApiException($response->getBody()->getContents(), $response->getStatusCode());
        return $e;
    }
}

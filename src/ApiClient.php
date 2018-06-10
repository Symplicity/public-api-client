<?php
namespace Symplicity\PublicApiClient;

class ApiClient
{
    private $instance;
    private $token;
    private $client;
    private $errors;

    public function __construct($instance, $token)
    {
        $this->instance = $instance;
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
            $response = $this->client->get($this->prepareRouteUrl($route), $params);
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

            $url = $this->prepareRouteUrl($route);
            if (!empty($args['bulk'])) {
                $url .= '?bulk=1';
            }

            $response = $this->client->post($url, $params);
            $httpCode = $response->getStatusCode();
            if (in_array($httpCode, array(200, 201))) {
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

    private function prepareRouteUrl($route)
    {
        return "{$this->instance}/api/public/v1/$route";
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

    /**
     * If (and only if) the entire $in parameter is enclosed in single or double quotes, strip them
     *
     * @param string $in
     * @return string
     **/
    public function deQuote($in)
    {
        $_first = substr($in, 0, 1);
        $_last = substr($in, -1, 1);
        if ($_first == $_last && ($_first == '"' || $_first == '\'')) {
            $in = substr($in, 1, strlen($in) - 2);
        }
        return $in;
    }

    private function getException($response)
    {
        return new \Symplicity\PublicApiClient\ApiException($response->getBody()->getContents(), $response->getStatusCode());
    }
}

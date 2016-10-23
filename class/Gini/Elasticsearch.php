<?php

namespace Gini;

class Elasticsearch {
    
    private static $instance = [];

    private $_client;

    private function __construct() {
        $hosts = \Gini\Config::get('elasticsearch.hosts');
        $this->_client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
    }

    public static function of() {
        if (!isset(static::$instance)) {
            static::$instance = \Gini\IoC::construct('\Gini\Elasticsearch');    
        }
        return static::$instance;
    }

    public function setup($index, $settings = [], $mappings = []) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10001); 
        }

        if (!is_array($settings) || !is_array($mappings)) {
            throw new \ErrorException('Wrong Settings or Mappings', 10002); 
        }

        $params = [
            'index' => $index,
            'body' => [
                'settings' => [$settings],
                'mappings' => [$mappings]
            ]
        ];

        $response = $this->_client->indices()->create($params);
    }

    public function index($params) {
        if (!is_array($params)) {
            throw new \ErrorException('Wrong Params', 10003);
        }

        $response = $this->_client->index($params);
    }
}


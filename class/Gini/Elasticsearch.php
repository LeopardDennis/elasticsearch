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
            throw new \ErrorException('Wrong Index', 10000);
        }

        if (!is_array($settings) || !is_array($mappings)) {
            throw new \ErrorException('Wrong Settings or Mappings', 10001); 
        }

        $params = [
            'index' => $index,
            'body' => $body
        ];

        if ($settins) {
            $params['body'] = $settings;
            $response = $this->_client->indices()->putSettings($params);
        }

        if ($mappings) {
            $params['body'] = $mappings;
            $response = $this->_client->indices()->putMapping($params);
        }
    }

    public function index($index, $type, $id, $document) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10000);
        }

        if (!$type) {
            throw new \ErrorException('Wrong Type', 10002);
        }

        if (!$id) {
            throw new \ErrorException('Wrong ID', 10003);
        }

        if (!is_array($document)) {
            throw new \ErrorException('Wrong Document', 10004);
        }

        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $document
        ];

        return $this->_client->index($params);
    }
}


<?php

namespace Gini;

class Elasticsearch {
    
    private static $_instance = null;

    private $_client;

    public function __construct() {
        $hosts = \Gini\Config::get('elasticsearch.hosts');
        $this->_client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
    }

    public static function of() {
        if (!isset(self::$_instance)) {
            self::$_instance = \Gini\IoC::construct('\Gini\Elasticsearch');
        }
        return self::$_instance;
    }

    public function create($index) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10000);
        }

        $params = [
            'index' => $index,
        ];

        return $this->_client->indices()->create($params);
    }

    public function setSettings($index, $settings = []) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10000);
        }

        if (!is_array($settings)) {
            throw new \ErrorException('Wrong Settings', 10002); 
        }

        $params = [
            'index' => $index,
            'body' => $settings
        ];

        return $this->_client->indices()->putSettings($params);
    }

    public function setMappings($index, $type, $mappings = []) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10000);
        }

        if (!$type) {
            throw new \ErrorException('Wrong Type', 10001);
        }

        if (!is_array($mappings)) {
            throw new \ErrorException('Wrong Mappings', 10003); 
        }

        $params = [
            'index' => $index,
            'type' => $type,
            'body' => $mappings
        ];

        return $this->_client->indices()->putMapping($params);
    }

    public function index($index, $type, $id, $document) {
        if (!$index) {
            throw new \ErrorException('Wrong Index', 10000);
        }

        if (!$type) {
            throw new \ErrorException('Wrong Type', 10001);
        }

        if (!$id) {
            throw new \ErrorException('Wrong ID', 10004);
        }

        if (!is_array($document)) {
            throw new \ErrorException('Wrong Document', 10005);
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


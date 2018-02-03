<?php

class SuperSaaS_SDK_API_BaseApi
{
    public $client;

    public function __construct ($client) {
        $this->client = $client;
    }
}
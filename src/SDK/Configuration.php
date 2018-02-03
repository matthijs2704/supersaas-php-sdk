<?php

class SuperSaaS_SDK_Configuration
{
    const DEFAULT_HOST	= "http://localhost:3000";

    public $account_name;
    public $host;
    public $password;
    public $user_name;
    public $test;

    public function __construct () {
        $this->account_name = '';
        $this->host = SuperSaaS_SDK_Configuration::DEFAULT_HOST;
        $this->password = '';
        $this->user_name = '';
        $this->test = FALSE;
    }
}
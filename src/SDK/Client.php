<?php

class SuperSaaS_SDK_Client
{
    const API_VERSION = "1";
    const VERSION = "0.1.0";

    public $account_name;
    public $password;
    public $user_name;
    public $host;
    public $test;

    public $appointments;
    public $forms;
    public $users;

    public $lastRequest;

    public static function Instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self(new SuperSaaS_SDK_Configuration());
        }
        return $instance;
    }

    public static function configure($account_name, $password, $user_name=NULL, $test=FALSE, $host=NULL) {
        self::Instance()->account_name = $account_name;
        self::Instance()->password = $password;
        self::Instance()->user_name = $user_name;
        self::Instance()->test = $test;
        self::Instance()->host = $host;
    }

    public function __construct ($configuration)
    {
        $this->account_name = $configuration->account_name;
        $this->password = $configuration->password;
        $this->user_name = $configuration->user_name;
        $this->host = $configuration->host;
        $this->test = $configuration->test;

        $this->appointments = new SuperSaaS_SDK_API_Appointments($this);
        $this->forms = new SuperSaaS_SDK_API_Forms($this);
        $this->users = new SuperSaaS_SDK_API_Users($this);
    }

    public function request ($http_method, $path, $params = array(), $query = array()) {
        if (empty($this->account_name))
        {
            throw new SuperSaaS_SDK_Exception("Account name not configured. Call `SuperSaaS_SDK_Client.configure`.");
        }
        if (empty($this->password))
        {
            throw new SuperSaaS_SDK_Exception("Account password not configured. Call `SuperSaaS_SDK_Client.configure`.");
        }
        $params = $this->removeEmptyKeys($params);
        $query = $this->removeEmptyKeys($query);

        $params['account_name'] = $this->account_name;

        if (!in_array($http_method, array("GET", "POST", "PUT", "DELETE"))) {
            throw new SuperSaaS_SDK_Exception("Invalid HTTP Method: " . $http_method . ". Only `GET`, `POST`, `PUT`, `DELETE` supported.");
        }

        $url = $this->host . "/api" . $path;
        if ($http_method == 'GET') {
            $query = $params;
        }
        if (!empty($query)) {
            $url = $url . '?' . http_build_query($query);
        }

        $http = array(
            'method' => $http_method,
            'header'  => array(
                'Authorization: Basic ' . base64_encode($this->account_name . ':' . $this->password),
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $this->userAgent(),
            )
        );
        if ($http_method !== 'GET' && !empty($params)) {
            $http['content'] = json_encode($params);
        }

        $this->lastRequest = $http;
        if ($this->test) {
            return array();
        }

        $req  = stream_context_create(array('http' => $http));
        $res = file_get_contents($url, false, $req);
        if ($res == FALSE) {
            throw new SuperSaaS_SDK_Exception("HTTP Request Error " . $url);
        } else if (!empty($res)) {
            $obj = json_decode($res);
            return $obj;
        } else {
            return array();
        }
    }

    private function removeEmptyKeys ($arr) {
        $valueArr = array();
        foreach ($arr as $key=>$val) {
            if ($val !== NULL && $val !== "")
                $valueArr[$key] = $val;
        }
        return $valueArr;
    }

    private function userAgent () {
        return "SSS/" . self::VERSION . " PHP/" . phpversion() . " API/" . self::API_VERSION;
    }
}
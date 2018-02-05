<?php

class SuperSaaS_SDK_API_Forms extends SuperSaaS_SDK_API_BaseApi
{
    public function get($form_id)
    {
        $path = "/forms.json";
        $params = array('form_id' => $this->validateId($form_id));
        $res = $this->client->request('GET', $path, $params);
        $arr = array();
        foreach ($res as $attributes) {
            $arr[] = $attributes;
        }
        return $arr;
    }

    public function find($form_id, $from_time = NULL) {
        $path = "/forms.json";
        $params = array('id' => $this->validateId($form_id));
        if ($from_time) {
            $params['from'] = $this->validateDatetime(from_time);
        }
        $res = $this->client->request('GET', $path, $params);
        return new SuperSaaS_SDK_Models_Form($res);
    }
}
<?php

class SuperSaaS_SDK_API_Users extends SuperSaaS_SDK_API_BaseApi
{
    public function get($user_id=NULL, $form=NULL, $limit=NULL, $offset=NULL)
    {
        $path = $this->userPath($user_id);
        if ($user_id) {
            $res = $this->client->request('GET', $path);
            return new SuperSaaS_SDK_Models_User($res);
        } else {
            $params = array(
                'form' => empty($form) ? NULL : 'true',
                'limit' => empty($limit) ? NULL : $this->validateNumber($limit),
                'offset' => empty($offset) ? NULL : $this->validateNumber($offset)
            );
            $res = $this->client->request('GET', $path, $params);
            $arr = array();
            foreach ($res as $attributes) {
                $arr[] = new SuperSaaS_SDK_Models_User($attributes);
            }
            return $arr;
        }
    }

    public function create($attributes, $user_id=NULL)
    {
        $path = $this->userPath($user_id);
        $params = array(
            'webhook' => $attributes['webhook'],
            'user' => array(
                'name' => $this->validatePresent($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : NULL,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : NULL
            )
        );
        $res = $this->client->request('POST', $path, $params);
        return new SuperSaaS_SDK_Models_User($res);
    }

    public function update($user_id, $attributes) {
        $path = $this->userPath($this->validateId($user_id));
        $params = array(
            'webhook' => $attributes['webhook'],
            'user' => array(
                'name' => $this->validatePresent($attributes['name']),
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'super_field' => $attributes['super_field'],
                'credit' => isset($attributes['credit']) ? $this->validateNumber($attributes['credit']) : NULL,
                'role' => isset($attributes['role']) ? $this->validateOptions($attributes['role'], array(3, 4, -1)) : NULL
            )
        );
        return $this->client->request('PUT', $path, $params);
    }

    public function delete($user_id)
    {
        $path = $this->userPath($this->validateId($user_id));
        return $this->client->request('DELETE', $path);
    }

    private function userPath($id)
    {
        if (empty($id)) {
            return "/users.json";
        } else {
            return "/users/" . $id . ".json";
        }
    }
}
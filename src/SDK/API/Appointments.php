<?php

class SuperSaaS_SDK_API_Appointments extends SuperSaaS_SDK_API_BaseApi
{
    public function agenda($schedule_id, $user_id, $from_time = NULL, $slot = NULL)
    {
        $path = '/agenda/' . $this->validateId($schedule_id) . '.json';
        $params = array(
            'user' => $this->validatePresent($user_id),
            'slot' => empty($slot) ? NULL : 'true',
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time)
        );
        $res = $this->client->request('GET', $path, $params);
        return $this->mapSlotOrBookings($res);
    }

    public function available($schedule_id, $from_time = NULL, $length_minutes = NULL, $resource = NULL, $full = NULL, $limit = NULL)  {
        $path = '/free/' . $this->validateId($schedule_id) . '.json';
        $params = array(
            'length' => empty($length_minutes) ? NULL : $this->validateNumber($length_minutes),
            'from' => empty($from_time) ? NULL : $this->validateDatetime($from_time),
            'resource' => $resource,
            'full' => empty($full) ? NULL : 'true',
            'maxresults' => empty($limit) ? NULL : $this->validateNumber($limit)
        );
        $res = $this->client->request('GET', $path, $params);
        return $this->mapSlotOrBookings($res);
    }

    public function get($schedule_id, $appointment_id=NULL, $form=NULL, $start_time=NULL, $limit=NULL)
    {
        if ($appointment_id) {
            $params = array('schedule_id' => $this->validateId($schedule_id));
            $path = '/bookings/' . $this->validateId($appointment_id) . '.json';
            $res = $this->client->request('GET', $path, $params);
            return new SuperSaaS_SDK_Models_Appointment($res);
        } else {
            $path = '/bookings.json';
            $params = array(
                'form' => empty($form) ? NULL : 'true',
                'limit' => empty($limit) ? NULL : $this->validateNumber($limit),
                'start' => empty($start_time) ? NULL : $this->validateDatetime($start_time)
            );
            $res = $this->client->request('GET', $path, $params);
            return $this->mapSlotOrBookings($res);
        }
    }

    public function create($schedule_id, $user_id, $attributes, $form=NULL, $webhook=NULL)
    {
        $path = '/bookings.json';
        $params = array(
            'schedule_id' => $schedule_id,
            'user_id' => empty($user_id) ? NULL : $this->validateId($user_id),
            'form' => empty($form) ? NULL : 'true',
            'webhook' => $attributes['webhook'],
            'booking' => array(
                'start' => $attributes['start'],
                'finish' => $attributes['finish'],
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'field_1_r' => $attributes['field_1_r'],
                'field_2_r' => $attributes['field_2_r'],
                'super_field' => $attributes['super_field'],
                'resource_id' => $attributes['resource_id'],
                'slot_id' => $attributes['slot_id']
            )
        );
        $res = $this->client->request('POST', $path, $params);
        return new SuperSaaS_SDK_Models_Appointment($res);
    }

    public function update($schedule_id, $appointment_id, $attributes, $form=NULL, $webhook=NULL)
    {
        $path = '/bookings/' . $this->validateId($appointment_id) . '.json';
        $params = array(
            'schedule_id' => $schedule_id,
            'form' => empty($form) ? NULL : 'true',
            'webhook' => $attributes['webhook'],
            'booking' => array(
                'start' => $attributes['start'],
                'finish' => $attributes['finish'],
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'full_name' => $attributes['full_name'],
                'address' => $attributes['address'],
                'mobile' => $attributes['mobile'],
                'phone' => $attributes['phone'],
                'country' => $attributes['country'],
                'field_1' => $attributes['field_1'],
                'field_2' => $attributes['field_2'],
                'field_1_r' => $attributes['field_1_r'],
                'field_2_r' => $attributes['field_2_r'],
                'super_field' => $attributes['super_field'],
                'resource_id' => $attributes['resource_id'],
                'slot_id' => $attributes['slot_id']
            )
        );
        $res = $this->client->request('POST', $path, $params);
        return new SuperSaaS_SDK_Models_Appointment($res);
    }

    public function delete($appointment_id)
    {
        $path = '/bookings/' . $this->validateId($appointment_id) . '.json';
        return $this->client->request('DELETE', $path);
    }

    private function mapSlotOrBookings($res, $slot = FALSE) {
        $arr = array();
        foreach ($res as $attributes) {
            if ($slot) {
                $arr[] = new SuperSaaS_SDK_Models_Slot($attributes);
            } else {
                $arr[] = new SuperSaaS_SDK_Models_Appointment($attributes);
            }
        }
        return $arr;
    }
}
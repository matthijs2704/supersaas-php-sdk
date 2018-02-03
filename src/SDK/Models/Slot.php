<?php

class SuperSaaS_SDK_Models_Slot extends SuperSaaS_SDK_Models_BaseModel
{
    public $description;
    public $finish;
    public $id;
    public $location;
    public $start;
    public $title;

    public $bookings;

    public function __construct ($attributes=array()) {
        $this->description = $attributes['description'];
        $this->finish = $attributes['finish'];
        $this->id = $attributes['id'];
        $this->location = $attributes['location'];
        $this->start = $attributes['start'];
        $this->title = $attributes['title'];

        $this->errors = $attributes['errors'];

        if (!empty($attributes['bookings'])) {
            $this->bookings = array();
            foreach ($attributes['bookings'] as $booking) {
                $this->bookings[] = new SuperSaaS_SDK_Models_Appointment($booking);
            }
        }
    }
}
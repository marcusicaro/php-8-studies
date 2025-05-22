<?php

class AppointmentMaker
{
    public function makeAppointment(): string
    {
        $encoder = new BloggsApptEncoder();
        return $encoder->encode();
    }
}

class AppointmentMaker2
{
    public function __construct(private ApptEncoder $encoder) {}
    public function makeAppointment(): string
    {
        return $this->encoder->encode();
    }
}

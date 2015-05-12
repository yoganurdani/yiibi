<?php

namespace sintret\whatsapp;

include 'src/whatsprot.class.php';

class WhatsApp {

    public $number;
    public $app;
    public $password;
    public $connect;
    public $debug;

    public function __construct($number, $app, $password,$debug=false) {
        $this->number = $number;
        $this->app = $app;
        $this->password = $password;
        $this->debug=$debug;
        $this->getConnection();
    }

    public function getConnection() {
        $w = new \WhatsProt($this->number, 0, $this->app, $this->debug);
        $w->connect();
        $w->loginWithPassword($this->password);
        $this->connect = $w;
    }

    public function send($target, $message) {
        $this->connect->SendPresenceSubscription($target); //Let us first send presence to user
        $this->connect->sendMessage($target, $message); // Send Message
    }

}

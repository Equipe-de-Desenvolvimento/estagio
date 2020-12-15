<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class auth_model extends Model
{


    public function __construct()
    {
        parent::__construct();
    }

     function logingoogle(){

        if (!$this->googlecalendar->isLogin()) {
            return 0;
        }
            return 1;
        
    }


}
<?php
//Require the user file
require_once("User.php");

class Admin extends User
{
    //Attributes
    //Functions

    /**
     * Admin constructor.
     */
    public function __construct($db)
    {
        parent::__construct($db);
    }
}
<?php
class Session
{
    //Attributes
    //Functions
    public function __construct()
    {
        //Check if the user session has not been started yet
        if(!isset($_SESSION["user"]["token"])){
            //Create the user session with the authentication token
            $_SESSION["user"]["token"] = md5($this->generateRandomToken());
        }
    }

    private function generateRandomToken()
    {
        //Prepare token
        $token = "";
        //Prepare a list of alphabets
        $alphabets = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        //Generate the random token
        for($i=0; $i < 8; $i++){
            $num= rand(0, 25);
            $token += $alphabets[$num];
            $num = rand(0, 9);
            $token += $num;
        }
        //Return generated token
        return $token;
    }
}
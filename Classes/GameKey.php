<?php

/**
 * Created by PhpStorm.
 * User: Don Smoke
 * Date: 11/2/2016
 * Time: 8:26 AM
 */
class GameKey
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $game_key_serial_no;
    private $status;
    private $game_ref_no;

    /**
     * Supplier constructor.
     * @param Mysqli $db
     */
    public function __construct(Mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getGameKeySerialNumber()
    {
        return $this->game_key_serial_no;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getGameReferenceNumber()
    {
        return $this->game_ref_no;
    }

    /**
     * @param mixed $game_key_serial_no
     */
    public function setGameKeySerialNumber($game_key_serial_no)
    {
        $this->game_key_serial_no = $game_key_serial_no;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $game_ref_no
     */
    public function setGameReferenceNumber($game_ref_no)
    {
        $this->game_ref_no = $game_ref_no;
    }
    //Function
    //Add a game key
    public function create()
    {
        $this->generate();
        $sql = "INSERT INTO GameKey(Game_key_serial_no, Status, Game_ref_no) VALUES(?, ?, ?)";
        $mysql_statement = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($mysql_statement, "ssi", $this->game_key_serial_no, $this->status, $this->game_ref_no);
        //Check if the sale was added successfully
        if (mysqli_stmt_execute($mysql_statement) == true) {
            //Close the database connection
            mysqli_stmt_close($mysql_statement);
            //The game was added successfully
            return true;
        } else {
            //Close the database connection
            mysqli_stmt_close($mysql_statement);
            //There was an error adding the game
            return false;
        }
    }

    //Fetches all the keys for a particular game
    public function game($game_ref_no)
    {
        $sql = "SELECT Game.Game_ref_no, Name, Game_key_serial_no, Status FROM GameKey, Game WHERE GameKey.Game_ref_no = Game.Game_ref_no AND Game_ref_no = ?";
        $mysql_statement = mysqli_prepare($this->db, $sql);
        if(mysqli_stmt_execute($mysql_statement) == true)
        {
            $result = mysqli_stmt_get_result($mysql_statement);
            $games = array();
            //Retrieve all the games
            while($db_row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
                $games[] = $db_row;
            }
            //Return the games
            return $games;
        }
    }

    //Creates a product key
    public function generate()
    {
        $alphabets = range("A","Z");
        $numbers = range(0, 9);
        $ba = false;
        $bn = false;
        $lena = count($alphabets) - 1;
        $lenn = count($numbers) - 1;
        $key = "";
        //First 5
        for($i=0; $i < 2; $i++)
        {
            if($bn == false){
                $key .= $numbers[rand(0, $lenn)];
                $bn = true;
                $ba = false;
            }
            if($ba == false){
                $key .= $alphabets[rand(0, $lena)];
                $ba = true;
                $bn = false;
            }
        }
        //Next 5
        $key .= "-";
        for($i=0; $i < 2; $i++)
        {
            if($bn == false){
                $key .= $numbers[rand(0, $lenn)];
                $bn = true;
                $ba = false;
            }
            if($ba == false){
                $key .= $alphabets[rand(0, $lena)];
                $ba = true;
                $bn = false;
            }
        }
        //Next 5
        $key .= "-";
        for($i=0; $i < 2; $i++)
        {
            if($bn == false){
                $key .= $numbers[rand(0, $lenn)];
                $bn = true;
                $ba = false;
            }
            if($ba == false){
                $key .= $alphabets[rand(0, $lena)];
                $ba = true;
                $bn = false;
            }
        }
        //Next 5
        $key .= "-";
        for($i=0; $i < 2; $i++)
        {
            if($bn == false){
                $key .= $numbers[rand(0, $lenn)];
                $bn = true;
                $ba = false;
            }
            if($ba == false){
                $key .= $alphabets[rand(0, $lena)];
                $ba = true;
                $bn = false;
            }
        }
        //Last 5
        $key .= "-";
        for($i=0; $i < 2; $i++)
        {
            if($bn == false){
                $key .= $numbers[rand(0, $lenn)];
                $bn = true;
                $ba = false;
            }
            if($ba == false){
                $key .= $alphabets[rand(0, $lena)];
                $ba = true;
                $bn = false;
            }
        }

        $this->game_key_serial_no = $key;
    }

    //Remove a game key
    public function remove()
    {
        $sql = "DELETE FROM GameKey WHERE Game_serial_key_no = ?";
        $mysql_statement = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($mysql_statement, "");
        //Check if the sale was added successfully
        if (mysqli_stmt_execute($mysql_statement) == true) {
            //Close the database connection
            mysqli_stmt_close($mysql_statement);
            //The game was added successfully
            return true;
        } else {
            //Close the database connection
            mysqli_stmt_close($mysql_statement);
            //There was an error adding the game
            return false;
        }
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Don Smoke
 * Date: 11/2/2016
 * Time: 10:10 AM
 */
class CashReceipt
{
    //Attributes
    //Functions
    //Add a game key
    public function create()
    {
        $sql = "INSERT INTO VALUES()";
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

    //Fetches all the games that are available in store
    public function fetchAllUsingDate()
    {
        $sql = "SELECT * FROM CashReceipt WHERE Date = ?";
    }
}
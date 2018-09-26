<?php

/**
 * Created by PhpStorm.
 * User: Don Smoke
 * Date: 10/16/2016
 * Time: 12:02 PM
 */
class Category
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $category_id;
    private $name;
    private $description;
    //Getters
    /**
     * Product constructor.
     * @param Mysqli $db
     */
    public function __construct(Mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    //Setters
    /**
     * @param mixed $category_id
     */
    public function setCategory($category)
    {
        $this->category_id = $category;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    //Functions
    public function create()
    {
        $sql = "INSERT INTO Category(Name, Description)";
        $sql .= " ";
        $sql .= "VALUES(?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the game data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "ss", $this->name, $this->description);
        //Add the game to the database
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function del()
    {
        $sql = "DELETE FROM Category WHERE Category_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->category_id);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function get()
    {
        $sql = "SELECT * FROM Category WHERE Category_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->category_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->category_id, $this->name, $this->description);
        if(mysqli_stmt_fetch($stmt) == true){
            mysqli_stmt_close($stmt);
            return true;
        }else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Category";
        $res = mysqli_query($this->db, $sql);
        $games = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $games[] = $row;
        }
        return $games;
    }

    public function getAllUsingID($id)
    {
        //Check if the passed argument is not an array
        if(!is_array($id)){
            //Return nothing
            return null;
        }
        //Convert the array into a string
        $ids = implode(",", $id);
        //Create the sql query
        $sql = "SELECT * FROM Category WHERE Category_id IN($ids)";
        //Execute the query
        $res = mysqli_query($this->db, $sql);
        //Prepare the games array to hold the games data
        $categories = array();
        //Check if there is any data and pass it into a temp variable
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            //Append the games into the games array
            $categories[] = $row;
        }
        //Return the games found
        return $categories;
    }

    public function update()
    {
        $sql = "UPDATE Category SET Name = ?, Description = ? WHERE Category_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $this->name, $this->description, $this->category_id);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }
}
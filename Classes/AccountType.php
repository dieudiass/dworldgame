<?php
class AccountType
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $type_id;
    private $name;
    private $description;

    /**
     * AccountType constructor.
     * @param Mysqli $db
     */
    public function __construct(Mysqli $db)
    {
        $this->db = $db;
    }
    //Getters

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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
    public function getType()
    {
        return $this->type_id;
    }
    //Settters
    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type_id = $type;
    }
    //Functions
    public function create()
    {
        $sql = "INSERT INTO AccountType(Name, Description)";
        $sql .= " ";
        $sql .= "VALUES(?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the game data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "ss", $this->name, $this->description);
        //Add the game to the database
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }

    public function del()
    {
        $sql = "DELETE FROM AccountType WHERE Type_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->type_id);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }

    public function get()
    {
        $sql = "SELECT * FROM AccountType WHERE Type_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->type_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->type_id, $this->name, $this->description);
        if(mysqli_stmt_fetch($stmt) == true){
            return true;
        }else{
            return false;
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM AccountType";
        $res = mysqli_query($this->db, $sql);
        $types = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $types[] = $row;
        }
        return $types;
    }

    public function update()
    {
        $sql = "UPDATE AccountType SET Name = ?, Description = ? WHERE Type_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $this->name, $this->description, $this->type_id);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }
}
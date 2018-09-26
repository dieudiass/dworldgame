<?php
class Supplier
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $supplier_id;
    private $name;
    private $country;

    /**
     * Supplier constructor.
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
    public function getCountry()
    {
        return $this->country;
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
    public function getSupplier()
    {
        return $this->supplier_id;
    }
    //Settters
    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier_id = $supplier;
    }
    //Functions
    public function create()
    {
        $sql = "INSERT INTO Supplier(Name, Country)";
        $sql .= " ";
        $sql .= "VALUES(?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the game data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "ss", $this->name, $this->country);
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
        $sql = "DELETE FROM Supplier WHERE Supplier_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->supplier_id);
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

    public function games()
    {
        $sql = "SELECT Game_ref_no, Game.Name as Game FROM Game, Supplier WHERE Game.Supplier_id = Supplier.Supplier_id AND Supplier.Supplier_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->supplier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->supplier_id, $this->name, $this->country);
        if(mysqli_stmt_fetch($stmt) == true){
            mysqli_stmt_close($stmt);
            return true;
        }else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    public function get()
    {
        $sql = "SELECT * FROM Supplier WHERE Supplier_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->supplier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->supplier_id, $this->name, $this->country);
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
        $sql = "SELECT * FROM Supplier";
        $res = mysqli_query($this->db, $sql);
        $suppliers = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $suppliers[] = $row;
        }
        return $suppliers;
    }

    public function update()
    {
        $sql = "UPDATE Supplier SET Name = ?, Country = ? WHERE Supplier_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $this->name, $this->country, $this->supplier_id);
        //Check if the game was updated successfully
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
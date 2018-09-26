<?php
class Sale
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $sale_ref;
    private $date;
    private $time;
    private $total_amount;
    private $username;

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
    public function getSaleReference()
    {
        return $this->sale_ref;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
    //Setters
    /**
     * @param mixed $sale_ref
     */
    public function setSaleReference($sale_ref)
    {
        $this->sale_ref = $sale_ref;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    //Functions
    public function create()
    {
        $sql = "INSERT INTO Sale(Date, Time, Total_amount, Username)";
        $sql .= " ";
        $sql .= "VALUES(?, ?, ?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the sale data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "ssds", $this->date, $this->time, $this->total_amount, $this->username);
        //Add the sale to the database
        //Check if the sale was added successfully
        if (mysqli_stmt_execute($stmt) == true) {
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        } else {
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function del()
    {
        $sql = "DELETE FROM Sale WHERE Sale_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->sale_ref);
        //Check if the game was added successfully
        if (mysqli_stmt_execute($stmt) == true) {
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        } else {
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function fetch()
    {
        $sql = "SELECT * FROM Sale WHERE Sale_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->sale_ref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->sale_ref, $this->date, $this->time, $this->total_amount, $this->username);
        if (mysqli_stmt_fetch($stmt) == true) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    //Fetches all the sales made
    public function fetchAll()
    {
        $sql = "SELECT * FROM Sale";
        $res = mysqli_query($this->db, $sql);
        $suppliers = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $suppliers[] = $row;
        }
        return $suppliers;
    }

    //Fetches all sales made by a customer
    public function fetchAllUsingCustomer()
    {
        $sql = "SELECT Sale_ref_no, Date, Time, Total_amount FROM Sale WHERE Username = ? ORDER BY Sale_ref_no";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $sales = array();
        //Fetch all sales
        while($db_row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            $sales[] = $db_row;
        }
        //Return sales
        return $sales;
    }

    //Fetches the last sale made
    public function fetchLastSale()
    {
        $sql = "SELECT Sale_ref_no FROM Sale ORDER BY Sale_ref_no DESC LIMIT 1";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->sale_ref);
        if (mysqli_stmt_fetch($stmt) == true) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    //Fetches the last sale made by a user
    public function fetchLastSaleUsingUsername()
    {
        $sql = "SELECT Sale_ref_no FROM Sale WHERE Username = ? ORDER BY Sale_ref_no DESC LIMIT 1";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        //Fetch all sales
        while($db_row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
            $this->sale_ref = $db_row["Sale_ref_no"];
        }
    }

    public function update()
    {
        $sql = "UPDATE Sale SET Date = ?, Time = ?, Total_amount = ?, Username = ? WHERE Sale_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsi", $this->date, $this->time, $this->total_amount, $this->username, $this->sale_ref);
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
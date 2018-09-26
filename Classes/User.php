<?php
class User
{
    //Attributes
    //Database
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $username;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $gender;
    private $contact;
    private $account_type;
    /**
     * User constructor.
     * @param Mysqli $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @param mixed $account_type
     */
    public function setAccountType($account_type)
    {
        $this->account_type = $account_type;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    //Functions
    public function create()
    {
        $sql = "INSERT INTO User(Username, Email, Password, First_name, Last_name, Gender, Contact, Account_type)";
        $sql .= " ";
        $sql .= "VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssi", $this->username, $this->email, $this->password, $this->firstname, $this->lastname, $this->gender, $this->contact, $this->account_type);
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
        $sql = "DELETE FROM User WHERE Username = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->username);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }

    public function fetchLastID()
    {
        $sql = "SELECT Username FROM User ORDER BY Username DESC LIMIT 1";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->username);

        return mysqli_stmt_fetch($stmt);
    }

    public function get()
    {
        $sql = "SELECT * FROM User WHERE Username = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->username, $this->email, $this->password, $this->firstname, $this->lastname, $this->gender, $this->contact, $this->account_type);

        return mysqli_stmt_fetch($stmt);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM User";
        $res = mysqli_query($this->db, $sql);
        $users = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $users[] = $row;
        }
        return $users;
    }

    public function login()
    {
        $sql = "SELECT Username, Account_type FROM User WHERE Username = ? AND Password = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $this->username, $this->password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->username, $this->account_type);
        return mysqli_stmt_fetch($stmt);
    }

    public function update()
    {
        $sql = "UPDATE User SET Email = ?, First_name = ?, Last_name = ?, Gender = ?, Contact = ?, Account_type = ? WHERE Username = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "sssssis", $this->email, $this->firstname, $this->lastname, $this->gender, $this->contact, $this->account_type, $this->username);
        //Check if the game was updated successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }
}
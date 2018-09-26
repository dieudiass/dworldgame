<?php
class Game
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $game_ref_no;
    private $description;
    private $name;
    private $price;
    private $stock;
    private $category_id;
    private $supplier_id;
	private $image;
    //Constructor
    /**
     * Product constructor.
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
    public function getGameRefNo()
    {
        return $this->game_ref_no;
    }

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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier_id;
    }
	
	public function getImage()
	{
		return $this->image;
	}
    //Setters
    /**
     * @param mixed $game_ref_no
     */
    public function setGameRefNo($game_ref_no)
    {
        $this->game_ref_no = $game_ref_no;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

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
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @param mixed $supplier_id
     */
    public function setSupplier($supplier)
    {
        $this->supplier_id = $supplier;
    }
	
	public function setImage($image)
	{
		$this->image = $image;
	}
    //Functions
    //Fetches all the game keys that are still available
    public function availableGameKeys()
    {}

    //Fetches all the games that are available in store
    public function availableGames()
    {
        $sql = "SELECT * FROM Game WHERE STOCK >= 1";
        $res = mysqli_query($this->db, $sql);
        $games = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $games[] = $row;
        }
        return $games;
    }
    public function create()
    {
        $sql = "INSERT INTO Game(Name, Description, Price, Stock, Category_id, Supplier_id, Image)";
        $sql .= " ";
        $sql .= "VALUES(?, ?, ?, ?, ?, ?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the game data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "ssdiiis", $this->name, $this->description, $this->price, $this->stock, $this->category_id, $this->supplier_id, $this->image);
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
        $sql = "DELETE FROM Game WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->game_ref_no);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }

    public function gameKeys()
    {}

    public function get()
    {
        $sql = "SELECT * FROM Game WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->game_ref_no);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->game_ref_no, $this->name, $this->description, $this->price, $this->stock, $this->category_id, $this->supplier_id, $this->image);
        if(mysqli_stmt_fetch($stmt) == true){
            return true;
        }else{
            return false;
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Game";
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
        $sql = "SELECT * FROM Game WHERE Game_ref_no IN($ids)";
        //Execute the query
        $res = mysqli_query($this->db, $sql);
        //Prepare the games array to hold the games data
        $games = array();
        //Check if there is any data and pass it into a temp variable
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            //Append the games into the games array
            $games[] = $row;
        }
        //Return the games found
        return $games;
    }

    public function getAllUsingLimit($limit)
    {
        //Check if the limit is empty
        if(empty($limit)){
            //Return nothing
            return null;
        }
        //Create the sql query
        $sql = "SELECT Category.Name as CategoryName, Supplier.Name as SupplierName, Game_ref_no, Game.Name as Name, Game.Description as Description, Price, Stock FROM Game JOIN Category ON Category.Category_id = Game.Category_id JOIN Supplier ON Supplier.Supplier_id = Game.Supplier_id ORDER BY Game.Name LIMIT " . $limit . "";
        //Execute the query
        $res = mysqli_query($this->db, $sql);
        //Prepare the games array to hold the games data
        $games = array();
        //Check if there is any data and pass it into a temp variable
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            //Append the games into the games array
            $games[] = $row;
        }
        //Return the games found
        return $games;
    }

    public function getAllWithCategoryAndSupplier()
    {
        //Create the sql query
        $sql = "SELECT Category.Name as CategoryName, Supplier.Name as SupplierName, Game_ref_no, Game.Name as Name, Game.Description as Description, Price, Stock FROM Game JOIN Category ON Category.Category_id = Game.Category_id JOIN Supplier ON Supplier.Supplier_id = Game.Supplier_id ORDER BY Game.Name";
        //Execute the query
        $res = mysqli_query($this->db, $sql);
        //Prepare the games array to hold the games data
        $games = array();
        //Check if there is any data and pass it into a temp variable
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            //Append the games into the games array
            $games[] = $row;
        }
        //Return the games found
        return $games;
    }

    /**
     * Checks if there is enough stock for the requested quantity
     * @param int $qty
     * @return bool
     */
    public function hasStockFor($qty)
    {
        //Get the current stock quantity
        $this->stockQuantity();
        //Check if there current stock quantity is more ore equal to the amount of copies requested
        if($this->stock >= $qty){
            return true;
        }else{
            //We are out of supply
            return false;
        }
    }

    public function stockQuantity()
    {
        $sql = "SELECT Stock FROM Game WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->game_ref_no);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->stock);
        mysqli_stmt_fetch($stmt);
    }

    public function update()
    {
        $sql = "UPDATE Game SET Name = ?, Description = ?, Price = ?, Stock = ?, Category_id = ?, Supplier_id = ?, Image = ? WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssdiiisi", $this->name, $this->description, $this->price, $this->stock, $this->category_id,  $this->supplier_id, $this->image, $this->game_ref_no);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //The game was added successfully
            return true;
        }else{
            //There was an error adding the game
            return false;
        }
    }

    //Fetches all the game keys that are no longer available
    public function updateStockQuantity($qty)
    {
        $sql = "UPDATE Game SET Stock = (Stock - ?) WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $qty, $this->game_ref_no);
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
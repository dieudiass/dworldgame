<?php
include_once("Cart.php");
class CartTest extends PHPUnit_Framework_TestCase
{
    //Attributes
    /**
     * @var Cart
     */
    private $cart;
    /**
     * @var Mysqli
     */
    private $db;
    //End of Attributes
    //Methods
    public function setUp()
    {
        $this->db = $this->createMock("Mysqli");
        $this->cart = new Cart($this->db);
    }

    /**
     * @expectedException TypeError
     */
    public function testCart_Fails_WhenTheDatabaseIsNotPassed(){
        //Act
        $this->cart = new Cart();
    }
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testFind_Fails_WhenNoArgumentIsPassed(){
        //Act
        $this->cart->find();
    }

    public function testFind_ReturnsFalse_WhenTheKeyIsNotAString(){
        //Arrange
        $exp = false;
        //Act
        $this->assertEquals($exp, $this->cart->find(null));
        $this->assertEquals($exp, $this->cart->find(false));
        $this->assertEquals($exp, $this->cart->find([]));
        $this->assertEquals($exp, $this->cart->find(5));
    }
    //End of Methods
}

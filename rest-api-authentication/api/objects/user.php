<?php
class User{
 
    private $conn;
    private $table_name = "users";

    public $id;
    public $fname;
    public $email;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }
 
function create(){
 
    $query = "INSERT INTO " . $this->table_name . "
            SET
                fname = :fname,
                email = :email,
                password = :password";
 
    $stmt = $this->conn->prepare($query);
 
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
 
    $stmt->bindParam(':fname', $this->fname);
    $stmt->bindParam(':email', $this->email);
 
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
 
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
 // check if given email exist in the database
function emailExists(){
 
    // query to check if email exists
    $query = "SELECT id, fname, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind given email value
    $stmt->bindParam(1, $this->email);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->fname = $row['fname'];
        $this->password = $row['password'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 
// update() method will be here
}

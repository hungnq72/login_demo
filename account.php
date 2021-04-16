<?php

require_once("database.php");
class Post{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function insertAccount($name, $email, $avatar, $sitename){
        $this->db->query("INSERT INTO users(name, email, avatar, sitename) VALUES (:name, :email, :avatar, :sitename)");
        $this->db->bind(":name",$name);
        $this->db->bind(":email",$email);
        $this->db->bind(":avatar",$avatar);
        $this->db->bind(":sitename",$sitename);
        $this->db->execute();
    }

    public function search($email, $sitename){
        $this->db->query("SELECT * from users WHERE email = :email AND sitename = :sitename");
        $this->db->bind(":email",$email);
        $this->db->bind(":sitename",$sitename);
        $this->db->single();
        if($this->db->rowCount() == 0){
            return true;
        }else{
            return false;
        }
    }
    
}
$p = new Post();
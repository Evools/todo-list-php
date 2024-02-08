<?php

class Database
{

  private $host = 'localhost';
  private $user = 'root';
  private $pass = 'root';
  private $name = 'todo';
  private $conn;

  public function getConnect()
  {
    $this->conn = null;

    try {
      $conn = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    return $this->conn;
  }
}

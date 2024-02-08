<?php

class Todo
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function addTask($text)
  {
    $sql = "INSERT INTO `task` (`text`) VALUES (:text)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':text', $text);
    $stmt->execute();
  }

  public function getAllTask()
  {
    $query = "SELECT * FROM `task`";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $task;
  }

  public function deleteTask($id)
  {
    $query = "DELETE FROM `task` WHERE `id` = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }

  public function checkTask($id)
  {
    $query = "UPDATE `task` SET `done` = 1 WHERE `id` = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }

  public function updateTaskStatus($id, $status)
  {
    $query = "UPDATE `task` SET `done` = :status WHERE `id` = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
  }

  public function getCompletedTasks()
  {
    $query = "SELECT * FROM `task` WHERE `done` = 1";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

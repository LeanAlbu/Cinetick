<?php
class Model{
   protected $db_connection;
   public function __construct()
   {
      require_once BASE_PATH . '/app/core/Database.php';
      $this->db_connection = Database::getInstance();
   }
}
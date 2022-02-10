<?php

class Database
{

  private $db_host = "localhost";
  private $db_user = "root";
  private $db_password = "";
  private $db_name = "testing";

  private $obj_mysqli = '';
  private $result = [];
  private $connection = false;

  // Connect To Database Whenever an Object Of This Class Is Created.
  public function __construct()
  {
    // If Database is not connected, then...
    if (!$this->connection) {
      $this->connection = true;
      $this->obj_mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name,);

      if ($this->obj_mysqli->connect_error) {
        array_push($this->result, $this->obj_mysqli->connect_error);
        return false; // In case of connection error, this function will terminate here.
      }
    } else {
      // If Database is already connected, then...
      return true;
    }
  }


  // Function Insert Data Into Database.
  public function insert(string $table_name, $params = [])
  {
    if ($this->tableExists($table_name)) {
      // Seperate $params Array's KEYs and VALUEs and Convert them to a String Value.
      $table_columns = implode(", ", array_keys($params));
      $table_values = implode("', '", $params);

      $insertQuery = "INSERT INTO $table_name ($table_columns) VALUES ('$table_values')";

      if ($this->obj_mysqli->query($insertQuery)) {
        array_push($this->result, $this->obj_mysqli->insert_id);
        return true;
      } else {
        array_push($this->result, $this->obj_mysqli->error);
        return false;
      }
    }
  }


  // Function Update Row In Database.
  public function update(string $table_name, $params = [], string $where = null)
  {
    if ($this->tableExists($table_name)) {
      // For converting assoc array into string.
      // First we store each key=>value pair in an array as a string "Key = Value".
      // And then we'll implode that array to get final string like "Key = 'Value', Key = 'Value', ...".
      $args = [];
      foreach ($params as $key => $value) {
        $args[] = "$key = '$value'";
      }

      $column_values = implode(', ', $args);
      $updateQuery = "UPDATE $table_name SET " . $column_values;
      if ($where !== null) {
        $updateQuery .= " WHERE id = $where";
      }

      if ($this->obj_mysqli->query($updateQuery)) {
        array_push($this->result, $this->obj_mysqli->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->obj_mysqli->error);
        return false;
      }
    }
  }


  // Function Delete Table or Row(s) From Database.
  public function delete(string $table_name, string $where = null)
  {
    if ($this->tableExists($table_name)) {
      $deleteQuery = "DELETE FROM $table_name";
      if ($where !== null) {
        $deleteQuery .= " WHERE $where";
      }

      if ($this->obj_mysqli->query($deleteQuery)) {
        array_push($this->result, $this->obj_mysqli->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->obj_mysqli->error);
        return false;
      }
    }
  }


  // Function Select From Database.
  public function select(
    string $table_name,
    string $column_name = '*',
    string $join = null,
    string $where = null,
    string $order = null,
    int $limit = 0
  ) {
    if ($this->tableExists($table_name)) {
      $selectQuery = "SELECT $column_name FROM $table_name";
    }
    if ($join !== null) {
      $selectQuery .= " $join";
    }
    if ($where !== null) {
      $selectQuery .= " WHERE $where";
    }
    if ($order != null) {
      $selectQuery .= " ORDER BY $order";
    }
    if ($limit != null) {
      if (isset($_GET['page'])) {
        $pageNo = $_GET['page'];
      } else {
        $pageNo = 1;
      }

      $limitOffset = ($pageNo - 1) * $limit;
      $selectQuery .= " LIMIT $limitOffset, $limit";
    }

    echo "<h3 class='query'>$selectQuery</h3>";

    $queryResult = $this->obj_mysqli->query($selectQuery);

    if ($queryResult) {
      $this->result = $queryResult->fetch_all(MYSQLI_ASSOC);
      return true;
    } else {
      array_push($this->result, $this->obj_mysqli->error);
      return false;
    }
  }


  // Function To Show Pagination.
  public function pagination(
    string $table_name,
    string $join = null,
    string $where = null,
    int $limit = 0
  ) {
    if ($this->tableExists($table_name)) {
      if ($limit != 0) {
        // Query For Counting Total Number Of Records.
        $query = "SELECT count(*) FROM $table_name";
        if ($join != null) {
          $query .= " $join";
        }
        if ($where != null) {
          $query .= " WHERE $where";
        }

        $queryResult = $this->obj_mysqli->query($query);

        $totalRecords = $queryResult->fetch_array(); // Returns data in index array.
        $totalRecords = $totalRecords[0];

        $totalPages = ceil($totalRecords / $limit);

        $url = basename($_SERVER['PHP_SELF']);  // Returns the base file name.
        // Get the Page Number which is set in URL.
        if (isset($_GET['page'])) {
          $pageNo = $_GET['page'];
        } else {
          $pageNo = 1;
        }

        // Show Pagination
        $output = "<ul class='pagination'>";

        if ($pageNo > 1) {
          $output .= "<li><a href='$url?page=" . ($pageNo - 1) . "'>Prev</a></li>";
        }

        if ($totalRecords > $limit) {
          for ($p = 1; $p <= $totalPages; $p++) {
            if ($pageNo == $p) {
              $cssClass = "class='active'";
            } else {
              $cssClass = '';
            }
            $output .= "<li><a $cssClass href='$url?page=$p'>$p</a></li>";
          }
        }

        if ($pageNo < $totalPages) {
          $output .= "<li><a href='$url?page=" . ($pageNo + 1) . "'>Next</a></li>";
        }

        $output .= "</ul>";
        echo $output;
      } else {
        // If Limit == 0
        return false;
      }
    }
  }


  // Function SQL Statement.
  public function myQuery(string $sql)
  {
    $query = $this->obj_mysqli->query($sql);
    if ($query) {
      $this->result = $query->fetch_all(MYSQLI_ASSOC);
      return true;
    } else {
      array_push($this->result, $this->obj_mysqli->error);
      return false;
    }
  }


  // Close Connection.
  public function __destruct()
  {
    if ($this->connection) {
      if ($this->obj_mysqli->close()) { // If connection is closed successfully, then...
        $this->connection = false;
        return true;
      }
    } else {
      return false;
    }
  }


  // Function For Confirming Table Existance.
  private function tableExists(string $table_name)
  {
    $sql = "SHOW TABLES FROM $this->db_name LIKE '$table_name'";
    $tableInDb = $this->obj_mysqli->query($sql);

    if ($tableInDb) {
      if ($tableInDb->num_rows == 1) {
        return true;
      } else {
        array_push($this->result, "'" . $table_name . "' Table does not exist in this Database.");
        return false;
      }
    }
  }


  // Function For Printing Result Array.
  public function getResult()
  {
    $res = $this->result;
    $this->result = [];
    return $res;
  }
}

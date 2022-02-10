<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .query {
      background-color: #e0dcdc;
      padding: 10px 4px;
    }

    .pagination {
      display: flex;
    }

    ul {
      list-style: none;
    }

    li a {
      padding: 6px 10px;
      text-decoration: none;
      background-color: #5a5757;
      color: white;
      margin: auto 1px;
      font-weight: 700;
    }

    .active {
      background-color: #3f3d3d;
    }
  </style>
  <title>OOP CRUD</title>
</head>

<body>
  <?php
  include "database.php";

  $obj = new Database();
  // $obj->update("students", [
  //   'name' => 'ZeeBoy',
  //   'age' => '21',
  //   'city' => 'Lahore'
  // ], 4);

  // $obj->delete('students', "age = 19");

  // $obj->myQuery("SELECT * FROM students");
  $obj->select(
    'students',
    'students.id, students.name, students.age, cities.cname',
    'LEFT JOIN cities ON students.city = cities.cid',
    null,
    null,
    3
  );
  $obj->pagination(
    'students',
    'LEFT JOIN cities ON students.city = cities.cid',
    null,
    3
  );

  echo "<pre>";
  print_r($obj->getResult());
  echo "</pre>";

  ?>
</body>

</html>
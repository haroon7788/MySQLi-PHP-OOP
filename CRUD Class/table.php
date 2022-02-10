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

    ul.pagination {
      list-style: none;
    }

    ul.pagination li a {
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
  <title>PHP OOP CRUD</title>
</head>

<body>

  <?php

  include "database.php";

  $obj = new Database();
  $limit = 3;
  $obj->select('students', '*', null, null, null, $limit);

  $data = $obj->getResult();

  ?>

  <table width='400px' border=1 cellpadding='8px' cellspacing=0>

    <tr bgcolor='lightgrey'>
      <th>ID</th>
      <th>Name</th>
      <th>Age</th>
      <th>City</th>
    <tr>

      <?php
      foreach ($data as list(
        'id' => $id,
        'name' => $name,
        'age' => $age,
        'city' => $city
      )) {
      ?>

    <tr>
      <td><?php echo $id ?></td>
      <td><?php echo $name ?></td>
      <td><?php echo $age ?></td>
      <td><?php echo $city ?></td>
    </tr>

  <?php
      }
  ?>
  </table>

  <?php
  $obj->pagination('students', null, null, $limit);
  ?>

</body>

</html>
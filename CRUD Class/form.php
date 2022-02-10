<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    label {
      font-size: 18px;

    }

    select,
    input[type="text"],
    input[type="number"] {
      padding: 10px 4px;
      font-size: 16px;
      outline: 0;
      display: block;
      margin-bottom: 16px;
    }

    #save-btn {
      padding: 10px 28px;
      font-size: 16px;
      font-weight: 700;
    }
  </style>
  <title>PHP OOP CRUD</title>
</head>

<body>

  <?php

  include "database.php";
  $obj = new Database();

  ?>

  <form action="form-save-data.php" method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" id="">

    <label for="age">Age</label>
    <input type="number" name="age" min="1" id="">

    <label for="city">City</label>
    <select name="city" id="">
      <?php
      $obj->select('cities');
      $res = $obj->getResult();
      foreach ($res as list('cid' => $id, 'cname' => $city)) {
        echo "<option value='$id'>$city</option>";
      }
      ?>
    </select>
    <button type="submit" id="save-btn">Save</button>
  </form>

</body>

</html>
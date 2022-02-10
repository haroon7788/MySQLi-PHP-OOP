<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP OOP FORM</title>
</head>

<body>

  <?php

  include "database.php";
  $obj = new Database();

  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
  $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_FLOAT);
  $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);

  // To insert this data in database, we'll use insert() function from Database class.
  $value = [
    'name' => $name,
    'age' => $age,
    'city' => $city,
  ];

  // Now we'll pass this $value array to insert() function.
  if ($obj->insert('students', $value)) {
    echo "<h2>Data Has Been Added Successfully In Database.</h2>";
  }else {
    echo "<h2>Failed To Add Data In Database.</h2>";
  }

  ?>

</body>

</html>
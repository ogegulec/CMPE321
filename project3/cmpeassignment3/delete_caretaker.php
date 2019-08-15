<?php

/**
  * Delete a user
  */

require "config.php";
require "common.php";

if (isset($_GET['id'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);

    $id = $_GET['id'];

    $sql = "DELETE FROM caretaker WHERE caretaker_id = :caretaker_id";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':caretaker_id', $id);
    $statement->execute();

    $success = "User successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM caretaker";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Delete caretaker</h2>

<?php if ($success) echo $success; ?>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>first Name</th>
      <th>last name</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($result as $row) : ?>
    <tr>
      <td><?php echo escape($row["caretaker_id"]); ?></td>
      <td><?php echo escape($row["caretaker_firstname"]); ?></td>
      <td><?php echo escape($row["caretaker_lastname"]); ?></td>
      
      <td><a href="delete_caretaker.php?id=<?php echo escape($row["caretaker_id"]); ?>">Delete</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
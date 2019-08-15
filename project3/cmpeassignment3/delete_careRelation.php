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

    $sql = "DELETE FROM careRelation WHERE animal_id = :animal_id";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':animal_id', $id);
    $statement->execute();

    $success = "User successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM careRelation";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Delete a caretaker relation</h2>

<?php if ($success) echo $success; ?>

<table>
  <thead>
    <tr>
      <th>Animal id</th>
      <th>Caretaker id</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($result as $row) : ?>
    <tr>
      <td><?php echo escape($row["animal_id"]); ?></td>
      <td><?php echo escape($row["caretaker_id"]); ?></td>
      
      <td><a href="delete_careRelation.php?id=<?php echo escape($row["animal_id"]); ?>">Delete</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
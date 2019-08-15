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

    $sql = "DELETE FROM sponsor WHERE sponsor_id = :sponsor_id";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':sponsor_id', $id);
    $statement->execute();

    $success = "User successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM sponsor";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Delete sponsor</h2>

<?php if ($success) echo $success; ?>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>first Name</th>
      <th>last name</th>
      <th>phone </th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($result as $row) : ?>
    <tr>
      <td><?php echo escape($row["sponsor_id"]); ?></td>
      <td><?php echo escape($row["sponsor_firstname"]); ?></td>
      <td><?php echo escape($row["sponsor_lastname"]); ?></td>
      <td><?php echo escape($row["phone"]); ?></td>
      
      <td><a href="delete_sponsor.php?id=<?php echo escape($row["sponsor_id"]); ?>">Delete</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
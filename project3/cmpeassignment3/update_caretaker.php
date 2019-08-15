<?php

/**
  * List all users with a link to edit
  */

try {
  require "config.php";
  require "common.php";

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

<h2>Update caretakers</h2>

<table>
  <thead>
    <tr>
    <th>caretaker id</th>
  <th>caretaker first name</th>
  <th>caretaker last name</th>
    </tr>
  </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo escape($row["caretaker_id"]); ?></td>
        <td><?php echo escape($row["caretaker_firstname"]); ?></td>
        <td><?php echo escape($row["caretaker_lastname"]); ?></td>
        <td><a href="update-singlecaretaker.php?id=<?php echo escape($row["caretaker_id"]); ?>">Edit</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
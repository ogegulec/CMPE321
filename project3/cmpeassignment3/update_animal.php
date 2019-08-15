<?php

/**
  * List all users with a link to edit
  */

try {
  require "config.php";
  require "common.php";

  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM animal";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Update animals</h2>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Animal Name</th>
      <th>Age</th>
      <th>Species</th>
      <th>Edit</th>
    </tr>
  </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo escape($row["animal_id"]); ?></td>
        <td><?php echo escape($row["animalname"]); ?></td>
        <td><?php echo escape($row["age"]); ?></td>
        <td><?php echo escape($row["species"]); ?></td>
        <td><a href="update-single.php?id=<?php echo escape($row["animal_id"]); ?>">Edit</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
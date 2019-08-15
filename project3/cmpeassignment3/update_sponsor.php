<?php

/**
  * List all users with a link to edit
  */

try {
  require "config.php";
  require "common.php";

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

<h2>Update sponsors</h2>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>first Name</th>
      <th>last name</th>
      <th>phone</th>
      <th>Edit</th>
    </tr>
  </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo escape($row["sponsor_id"]); ?></td>
        <td><?php echo escape($row["sponsor_firstname"]); ?></td>
        <td><?php echo escape($row["sponsor_lastname"]); ?></td>
        <td><?php echo escape($row["phone"]); ?></td>
        <td><a href="update-singlesponsor.php?id=<?php echo escape($row["sponsor_id"]); ?>">Edit</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
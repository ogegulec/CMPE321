<?php
if (isset($_POST['submit'])) {
  try {
    require "config.php";
    require "common.php";
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "CALL rankCaretakers()";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php require "templates/header.php"; ?>

<?php
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>

    <table>
      <thead>
<tr>
  <th>number of animals</th>
  <th>caretaker_firstname</th>
  <th>caretaker_lastname</th>

 
</tr>
      </thead>
      <tbody>
  <?php foreach ($result as $row) { ?>
      <tr>
<td><?php echo escape($row["count(Y.caretaker_id)"]); ?></td>
<td><?php echo escape($row["caretaker_firstname"]); ?></td>
<td><?php echo escape($row["caretaker_lastname"]); ?></td>


      </tr>
    <?php } ?>
      </tbody>
  </table>
  <?php } else { ?>
    > No results found .
  <?php }
} ?>

<h2>Rank all caretakers</h2>

<form method="post">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
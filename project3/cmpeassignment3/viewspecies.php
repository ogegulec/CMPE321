<?php
if (isset($_POST['submit'])) {
  try {
    require "config.php";
    require "common.php";
	$connection = new PDO($dsn, $username, $password, $options);
    $species = $_POST['species'];
    $sql="CALL selectSpecies('$species')";
    
    
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
  <th>id</th>
  <th>animalname</th>
  <th>age</th>
  <th>species</th>
 
</tr>
      </thead>
      <tbody>
  <?php foreach ($result as $row) { ?>
      <tr>
<td><?php echo escape($row["animal_id"]); ?></td>
<td><?php echo escape($row["animalname"]); ?></td>
<td><?php echo escape($row["age"]); ?></td>
<td><?php echo escape($row["species"]); ?></td>

      </tr>
    <?php } ?>
      </tbody>
  </table>
  <?php } else { ?>
    > No results found .
  <?php }
} ?>

<h2>Show specific species animals</h2>

<form method="post">
<label for="species">Species</label>
<input type="text" name="species" id="species">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
<?php
if (isset($_POST['submit'])) {
  try {
    require "config.php";
    require "common.php";
	$connection = new PDO($dsn, $username, $password, $options);
    $sponsor_firstname = $_POST['sponsor_firstname'];
    $sponsor_lastname=$_POST['sponsor_lastname'];
    $sql="CALL viewAnimalsOfSponsor('$sponsor_firstname','$sponsor_lastname')";
    
    
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

<h2>Show animals of a sponsor</h2>

<form method="post">
<label for="sponsor_firstname">Name</label>
<input type="text" name="sponsor_firstname" id="sponsor_firtsname">
<label for="sponsor_lastname">lastname</label>
<input type="text" name="sponsor_lastname" id="sponsor_lastname">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
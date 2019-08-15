
<?php
/**
  * Use an HTML form to edit an entry in the
  * users table.
  *
  */
require "config.php";
require "common.php";
if (isset($_POST['submit'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $user =[
      "caretaker_id"        => $_POST['caretaker_id'],
      "caretaker_firstname" => $_POST['caretaker_firstname'],
      "caretaker_lastname"  => $_POST['caretaker_lastname']
      
      
    ];

    $sql = "UPDATE caretaker
            SET caretaker_id = :caretaker_id,
            caretaker_firstname = :caretaker_firstname,
            caretaker_lastname = :caretaker_lastname
              
              
            WHERE caretaker_id = :caretaker_id";

  $statement = $connection->prepare($sql);
  $statement->execute($user);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}

if (isset($_GET['id'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $id = $_GET['id'];
    $sql = "SELECT * FROM caretaker WHERE caretaker_id = :caretaker_id";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':caretaker_id', $id);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
} else {
    echo "Something went wrong!";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
  <?php echo escape($_POST['caretaker_firstname']); ?> successfully updated.
<?php endif; ?>

<h2>Edit a caretaker</h2>

<form method="post">
    <?php foreach ($user as $key => $value) : ?>
      <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
      <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key === 'caretaker_id' ? 'readonly' : null); ?>>
    <?php endforeach; ?>
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
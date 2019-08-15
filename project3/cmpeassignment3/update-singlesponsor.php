
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
      "sponsor_id"        => $_POST['sponsor_id'],
      "sponsor_firstname" => $_POST['sponsor_firstname'],
      "sponsor_lastname"  => $_POST['sponsor_lastname'],
      "phone"     => $_POST['phone']
      
      
    ];

    $sql = "UPDATE sponsor
            SET sponsor_id = :sponsor_id,
            sponsor_firstname = :sponsor_firstname,
            sponsor_lastname = :sponsor_lastname,
            phone = :phone
              
            WHERE sponsor_id = :sponsor_id";

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
    $sql = "SELECT * FROM sponsor WHERE sponsor_id = :sponsor_id";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':sponsor_id', $id);
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
  <?php echo escape($_POST['sponsor_firstname']); ?> successfully updated.
<?php endif; ?>

<h2>Edit a sponsor</h2>

<form method="post">
    <?php foreach ($user as $key => $value) : ?>
      <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
      <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key === 'sponsor_id' ? 'readonly' : null); ?>>
    <?php endforeach; ?>
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
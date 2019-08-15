<?php
/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */
if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";
    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_user = array(
            "caretaker_firstname" => $_POST['caretaker_firstname'],
            "caretaker_lastname"  => $_POST['caretaker_lastname'],
       
        );
        $sql ="INSERT INTO caretaker (caretaker_firstname, caretaker_lastname)
        VALUES (:caretaker_firstname, :caretaker_lastname)";
        $sql2 ="SELECT * FROM caretaker";
 
        $statement = $connection->prepare($sql);
        $statement->bindValue(':caretaker_firstname',$new_user["caretaker_firstname"]);
        $statement->bindValue(':caretaker_lastname',$new_user["caretaker_lastname"]);
        $statement->execute($new_user);


    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['caretaker_firstname']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a caretaker</h2>

<form method="post">
    <label for="caretaker_firstname">Name</label>
    <input type="text" name="caretaker_firstname" id="caretaker_firstname">
    <label for="caretaker_lastname">Lastname</label>
    <input type="text" name="caretaker_lastname" id="caretaker_lastname">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
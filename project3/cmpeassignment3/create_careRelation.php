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
            "animal_id" => $_POST['animal_id'],
            "caretaker_id"  => $_POST['caretaker_id'],
       
        );
        $sql ="INSERT INTO careRelation (animal_id, caretaker_id)
        VALUES (:animal_id, :caretaker_id)";
        $sql2 ="SELECT * FROM careRelation";
 
        $statement = $connection->prepare($sql);
        $statement->bindValue(':animal_id',$new_user["animal_id"]);
        $statement->bindValue(':caretaker_id',$new_user["caretaker_id"]);
        $statement->execute($new_user);


    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['animal_id']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a caretaker relationship</h2>

<form method="post">
    <label for="animal_id">Animal ID</label>
    <input type="text" name="animal_id" id="animal_id">
    <label for="caretaker_id">Caretaker ID</label>
    <input type="text" name="caretaker_id" id="caretaker_id">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
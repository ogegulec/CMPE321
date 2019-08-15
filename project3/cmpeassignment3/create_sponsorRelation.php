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
            "sponsor_id"  => $_POST['sponsor_id'],
       
        );
        $sql ="INSERT INTO sponsorRelation (animal_id, sponsor_id)
        VALUES (:animal_id, :sponsor_id)";
        $sql2 ="SELECT * FROM sponsorRelation";
 
        $statement = $connection->prepare($sql);
        $statement->bindValue(':animal_id',$new_user["animal_id"]);
        $statement->bindValue(':sponsor_id',$new_user["sponsor_id"]);
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

<h2>Add a sponsor relationship</h2>

<form method="post">
    <label for="animal_id">Animal ID</label>
    <input type="text" name="animal_id" id="animal_id">
    <label for="sponsor_id">Sponsor ID</label>
    <input type="text" name="sponsor_id" id="sponsor_id">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
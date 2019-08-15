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
            "sponsor_firstname" => $_POST['sponsor_firstname'],
            "sponsor_lastname"  => $_POST['sponsor_lastname'],
            "phone"  => $_POST['phone'],
        );
        $sql ="INSERT INTO sponsor (sponsor_firstname, sponsor_lastname,phone)
        VALUES (:sponsor_firstname, :sponsor_lastname,:phone)";
        $sql2 ="SELECT * FROM sponsor";
 
        $statement = $connection->prepare($sql);
        $statement->bindValue(':sponsor_firstname',$new_user["sponsor_firstname"]);
        $statement->bindValue(':sponsor_lastname',$new_user["sponsor_lastname"]);
        $statement->bindValue(':phone',$new_user["phone"]);
        $statement->execute($new_user);


    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['sponsor_firstname']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a sponsor</h2>

<form method="post">
    <label for="sponsor_firstname">Name</label>
    <input type="text" name="sponsor_firstname" id="sponsor_firstname">
    <label for="sponsor_lastname">Lastname</label>
    <input type="text" name="sponsor_lastname" id="sponsor_lastname">
    <label for="phone">phone number</label>
    <input type="text" name="phone" id="phone">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
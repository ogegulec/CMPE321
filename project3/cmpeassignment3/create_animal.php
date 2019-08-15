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
            "animalname" => $_POST['animalname'],
            "species"  => $_POST['species'],
            "age"       => $_POST['age'],
        );
        if(!empty($_POST['caretaker_id'])){

            $new_user2 = array(
                "animalname" => $_POST['animalname'],
                "species"  => $_POST['species'],
                "age"       => $_POST['age'],
                "caretaker_id" => $_POST['caretaker_id'],
            );
        }
       
    
        
        $sql ="INSERT INTO animal (animalname, age, species)
        VALUES (:animalname, :age, :species)";
        $sql2 ="SELECT * FROM animal";

 
        $statement = $connection->prepare($sql);
        $statement->bindValue(':animalname',$new_user["animalname"]);
        $statement->bindValue(':species',$new_user["species"]);
        $statement->bindValue(':age',$new_user["age"]);
        $statement->execute($new_user);
        
         

            if(!empty($_POST['caretaker_id'])){
                $sql3= "UPDATE careRelation set caretaker_id= :caretaker_id where animal_id= (select animal_id from animal where animalname = :animalname and 
                species=:species and age=:age)" ;
            $statement2 = $connection->prepare($sql3);
            $statement2->bindValue(':animalname',$new_user2["animalname"]);
            $statement2->bindValue(':species',$new_user2["species"]);
            $statement2->bindValue(':age',$new_user2["age"]);
            $statement2->bindValue(':caretaker_id',$new_user2["caretaker_id"]);
             $statement2 ->execute($new_user2);
            }
        
        


    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['animalname']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a user</h2>

<form method="post">
    <label for="animalname">Name</label>
    <input type="text" name="animalname" id="animalname">
    <label for="age">Age</label>
    <input type="text" name="age" id="age">
    <label for="species">Species</label>
    <input type="text" name="species" id="species">
    <label for="caretaker_id">add a caretaker with id(optional)</label>
    <input type="text" name="caretaker_id" id="caretaker_id">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="welcome.php">Back to home</a>

<?php require "templates/footer.php"; ?>
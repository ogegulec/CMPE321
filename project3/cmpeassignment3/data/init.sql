DROP DATABASE IF EXISTS test;
CREATE DATABASE IF NOT EXISTS test;

  use test;

  CREATE TABLE animal (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
    animalname VARCHAR(30) ,
    age INT(3),
    species VARCHAR(50) NOT NULL
  );
    CREATE TABLE sponsor (
    sponsor_id INT AUTO_INCREMENT PRIMARY KEY,
    sponsor_firstname VARCHAR(30) NOT NULL,
    sponsor_lastname VARCHAR(30) NOT NULL,
    phone INT(20)
  );
    CREATE TABLE caretaker (
    caretaker_id INT AUTO_INCREMENT PRIMARY KEY,
    caretaker_firstname VARCHAR(30) NOT NULL,
    caretaker_lastname VARCHAR(30) NOT NULL  
  );
   CREATE TABLE careRelation(
     animal_id INT PRIMARY KEY,
     caretaker_id INT NOT NULL,
     CONSTRAINT foreignKey_1 foreign key (animal_id) references animal(animal_id) ON DELETE CASCADE,
     CONSTRAINT foreignKey_2 foreign key (caretaker_id) references caretaker(caretaker_id) ON DELETE CASCADE
   );

   CREATE TABLE sponsorRelation(
     animal_id INT PRIMARY KEY,
     sponsor_id INT,
     CONSTRAINT foreignKey_1 foreign key (animal_id) references animal(animal_id) ON DELETE CASCADE,
     CONSTRAINT foreignKey_2 foreign key (sponsor_id) references sponsor(sponsor_id) ON DELETE CASCADE
   );

  CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    adminpassword VARCHAR(255) NOT NULL  
  );
DELIMITER $$
CREATE PROCEDURE selectSpecies (IN thatspecies VARCHAR(500))
BEGIN
SELECT * FROM animal
WHERE species like thatspecies;
END $$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE rankCaretakers()
BEGIN
select count(Y.caretaker_id), caretaker_firstname, caretaker_lastname
  from caretaker X left join careRelation Y on X.caretaker_id = Y.caretaker_id
  group by X.caretaker_id order by count(Y.caretaker_id) asc;
END $$
DELIMITER ;



DELIMITER $$
CREATE TRIGGER assignCaretaker AFTER INSERT ON animal
	FOR EACH ROW
  BEGIN
      SET @min_rank_caretaker = (select X.caretaker_id 
      from caretaker X left join careRelation Y on X.caretaker_id = Y.caretaker_id
      group by X.caretaker_id order by count(Y.caretaker_id) asc limit 1);
      INSERT INTO careRelation(animal_id, caretaker_id) VALUES (NEW.animal_id, @min_rank_caretaker);
	END;
$$
DELIMITER ;




DELIMITER $$
CREATE TRIGGER assignSponsor BEFORE DELETE ON animal
	FOR EACH ROW
	BEGIN
   IF ((select sponsor_id from sponsorRelation where animal_id = OLD.animal_id) is NOT NULL)
    THEN
		SET @older_sponsor = (select sponsor_id from sponsorRelation where animal_id = OLD.animal_id);
		SET @oldest_animal = (select animal_id from animal where animal_id not in(select animal_id from sponsorRelation) order by age desc limit 1);
		INSERT INTO sponsorRelation(animal_id, sponsor_id) values (@oldest_animal, @older_sponsor); 
	END IF;
  END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE viewAnimalsOfSponsor(IN s_firstname VARCHAR(255), IN s_lastname VARCHAR(255))
	BEGIN
		SET @id_of_sponsor = (select sponsor_id from sponsor where sponsor_firstname = s_firstname AND sponsor_lastname = s_lastname);
		select A.animal_id,A.animalname, A.age, A.species from animal A natural join (select animal_id from sponsorRelation where sponsor_id = @id_of_sponsor) as R;
	END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE viewAnimalsOfCaretaker(IN c_firstname VARCHAR(255), IN c_lastname VARCHAR(255))
	BEGIN
		SET @id_of_caretaker = (select caretaker_id from caretaker where caretaker_firstname = c_firstname AND caretaker_lastname = c_lastname);
		select A.animal_id,A.animalname, A.age, A.species from animal A natural join (select animal_id from careRelation where caretaker_id = @id_of_caretaker) as R;
	END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE viewAnimalNoSponsor()
BEGIN
  
  select animal_id, animalname,age,species from animal where animal_id not in(select animal_id from sponsorRelation);
	END$$
DELIMITER ;
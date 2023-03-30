CREATE DATABASE `mapping`;
USE `mapping`; 

CREATE TABLE `markers` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 60 ) NOT NULL ,
  `address` VARCHAR( 80 ) NOT NULL ,
  `lat` FLOAT( 10, 6 ) NOT NULL ,
  `lng` FLOAT( 10, 6 ) NOT NULL ,
  `type` VARCHAR( 30 ) NOT NULL
) ENGINE = INNODB;

CREATE USER `mapper`@`localhost` IDENTIFIED BY 'SillyPeopleDoNotChangeThisPassword';
GRANT SELECT, INSERT, UPDATE, DELETE ON mapping.* TO `mapper`@`localhost`;
FLUSH PRIVILEGES;


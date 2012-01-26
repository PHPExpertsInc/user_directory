-- CREATE DATABASE teds_db TEMPLATE=template0 ENCODING='utf8';
CREATE SCHEMA test_user_directory;
SET search_path TO test_user_directory;

begin;
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
  userID serial NOT NULL,
  username varchar(254) default NULL,
  password varchar(254) default NULL,
  PRIMARY KEY(userID)
);

CREATE UNIQUE INDEX users_uidx_username ON Users USING btree(username);

DROP TABLE IF EXISTS Profiles;
CREATE TABLE Profiles (
  profileID serial NOT NULL,
  userID int DEFAULT NULL,
  firstName varchar(254) NOT NULL,
  lastName varchar(254) NOT NULL,
  email varchar(254) NOT NULL,
  PRIMARY KEY(profileID)
);

CREATE UNIQUE INDEX profiles_uidx_userid ON Profiles USING btree(userID);
CREATE INDEX profiles_idx_lastname ON Profiles USING btree(lastName);
CREATE INDEX profiles_idx_email ON Profiles USING btree(email);

ALTER TABLE Profiles ADD FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO Users VALUES (1,'tsmith','*F7674CF5953FE36111DCF3152E8CC2C9E65A0009'),(2,'mjenssen','*812D768B08A7169CBD3B87BDDCE1CD1D59C02EAC'),(3,'djackson','*F8BB58E0F0C5958F19CB195F3BB56FE89A183A56'),(4,'vmaldoran','*6DFA279223B34735058F4AB170C59DE277655179'),(5,'joneill','*3B1CC4137B6A4B2A6CD47B89733FB9B830EDC874'),(6,'hlandry','*4AE33DBE6D2770E09F9D70D4D73448DF98CBE65E'),(7,'ghammond','*FEAA459370EABC8A414427AC431A214C58335C7F'),(8,'rmckay','*6AC949B5EEDA5B27442069A69C4E45800734CACC'),(9,'scarter','*A5FE0CCD4FC2CA2C40060FAAFC95DBA71A4F2BC7'),(10,'tealc','*15344AC32AA44AB94F4C54E0721159FE91237F8B'),(11,'ewier','*8E8E04E59F17473DF440F060C66C30443D886BAF'),(12,'jquinn','*605B6E9FFF16CDCA202FD024440450D503964FAE'),(13,'maortiz','*F7674CF5953FE36111DCF3152E8CC2C9E65A0009');

INSERT INTO Profiles VALUES (1,1,'Theodore','Smith','theodore@xmule.ws'),(2,2,'Mark','Jenssen','mark@givemehope.co.uk'),(3,3,'Daniel','Jackson','djackson@sgc.mil'),(4,4,'Vala','Mal Doran','vmaldoran@sgc.mil'),(5,5,'Jack','O''Neill','joneill@sgc.mil'),(6,6,'Harold','Landry','hlandry@sgc.mil'),(7,7,'George','Hammond','ghammond@sgc.mil'),(8,8,'Randolf','McKay','rmckay@sgc.mil'),(9,9,'Samantha','Carter','scarter@sgc.mil'),(10,10,'Teal''C','Jaffa','tealc@sgc.mil'),(11,11,'Elizabeth','Wier','ewier@sgc.mil'),(12,12,'Jonas','Quinn','jquinn@sgc.mil'),(13,13,'Maria','Ortiz','mmmmm@mmmmm.mmm');

DROP VIEW IF EXISTS vw_UserInfo;
CREATE VIEW vw_UserInfo AS 
select p.userID AS userID, Users.username AS username, p.firstName AS firstName, p.lastName AS lastName, p.email AS email 
from Profiles p 
join Users on p.userID = Users.userID;
commit;

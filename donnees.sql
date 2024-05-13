-- SQLBook: Code
-- Active: 1713532091000@@127.0.0.1@3306@donnees
-- SQLBook: Code

CREATE DATABASE IF NOT EXISTS donnees;
USE donnees;

CREATE TABLE IF NOT EXISTS clubs (
    id_club INT PRIMARY KEY AUTO_INCREMENT,
    nom_club VARCHAR(25),
    heure_ouverture time DEFAULT NULL,
    heure_fermeture time DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS disciplines(
    id_discipline INT PRIMARY KEY AUTO_INCREMENT,
    type_discipline VARCHAR(25)
);

CREATE TABLE IF NOT EXISTS installations (
    id_installation INT PRIMARY KEY AUTO_INCREMENT,
    nom_installation VARCHAR(60), 
    id_discipline INT,
    FOREIGN KEY (id_discipline) REFERENCES disciplines(id_discipline),
    piste BOOLEAN
);

CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    date_de_naissance DATE,
    email VARCHAR(50),
    mdp VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS inscription (
    id_inscription INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT, 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    id_club INT ,
    FOREIGN KEY (id_club) REFERENCES clubs(id_club),
    administrateur BOOLEAN,
    date_adhesion DATE DEFAULT NULL,
    est_adherent BOOLEAN DEFAULT 0 
);

CREATE TABLE IF NOT EXISTS exclusion (
    id_inscription INT, 
    FOREIGN KEY (id_inscription) REFERENCES inscription(id_inscription),
    date_exclusion DATE
);

CREATE TABLE IF NOT EXISTS reservation (
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    id_club INT,
    FOREIGN KEY (id_club) REFERENCES clubs(id_club),
    id_installation INT,
    FOREIGN KEY (id_installation) REFERENCES installations(id_installation),
    id_utilisateur INT, 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    date_debut_reservation DATE, 
    heure_debut_reservation TIME, 
    date_fin_reservation DATE, 
    heure_fin_reservation TIME,
    blocage BOOLEAN DEFAULT 0
);

CREATE TABLE IF NOT EXISTS presence (
    id_club INT ,
    FOREIGN KEY (id_club) REFERENCES clubs(id_club),
    id_installation INT,
    FOREIGN KEY (id_installation) REFERENCES installations(id_installation)
);

CREATE TABLE IF NOT EXISTS invite(
    id_reservation INT,
    FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation),
    id_utilisateur INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);
-- SQLBook: Code
-- Active: 1713532091000@@127.0.0.1@3306@donnees
INSERT INTO clubs(nom_club, heure_ouverture, heure_fermeture) VALUES ('Belfort Athletic Club','08:00:00','22:00:00'),('Sevenans Athlé','07:30:00','21:30:00'),('Montbéliard Athlétisme','09:00:00','22:00:00');
INSERT INTO disciplines(type_discipline) VALUES ('Course'),('Saut'),('Lancer');

INSERT INTO installations(nom_installation,id_discipline,piste) VALUES
('Sautoir de saut en hauteur',2,0),('Sautoir de saut en longueur',2,0),('Sautoir de triple saut',2,0),('Sautoir de saut à la perche',2,0),('Aire de lancer de disque ou marteau',3,0),('Aire de lancer de poids',3,0),('Aire de lancer de javelot',3,0),('Couloir 1',1,1),('Couloir 2',1,1),('Couloir 3',1,1),('Couloir 4',1,1),('Couloir 5 avec haies',1,1)


INSERT INTO presence(id_club,id_installation) VALUES (1,1),(1,2),(1,4),(1,6),(1,7),(1,8),(1,9),(1,10),(1,12),(2,1),(2,2),(2,3),(2,5),(2,7),(2,8),(2,10),(2,11),(3,1),(3,3),(3,4),(3,6),(3,7),(3,10),(3,11),(3,12);
-- SQLBook: Markup

INSERT INTO utilisateur(nom,prenom,date_de_naissance,email,mdp) VALUES ('LAVILLENIE','Renaud','1986-09-07','renaud.lavillenie@utbm.fr','renaudlavillenie86'),('LEMOSIN','Pascal','1984-07-26','pascal.lemosin@utbm.fr','pascallemosin84'),('GUESSAB','Mahiedine','1984-05-14','mahiedine.guessab@utbm.fr','mahiedineguessab84'),('LETAUPE','Alizée','1991-03-30','alizee.letaupe@utbm.fr','alizeeletaupe91'),('LAPIERRE','Fabrice','1983-11-17','fabrice.lapierre@utbm.fr','fabricelapierre83'),('DESENNE','Romain','1994-09-22','romain.desenne@utbm.fr','romaindesenne94'),('KRAMER','Melina','1998-07-01','melina.kramer@utbm.fr','melinakramer98'),('LIMA','David','1990-04-11','david.lima@utbm.fr','davidlima90'),('DEGRAVE','Sophie','1987-06-15','sophie.degrave@utbm.fr','sophiedegrave87'),('BROIS','Valentin','1993-02-18','valentin.brois@utbm.fr','valentinbrois93'),('BOLT','Usain','1986-08-21','usain.bolt@utbm.fr','usainbolt86'),('FARAH','Mo','1983-03-23','mo.farah@utbm.fr','mofarah83'),('JOHNSON','Michael','1967-09-13','michael.johnson@utbm.fr','michaeljohnson67'),('RADCLIFFE','Paula','1973-12-17','paula.radcliffe@utbm.fr','paularadcliffe73'),('RUDISHA','David','1988-12-17','david.rudisha@utbm.fr','davidrudisha88'),('THOMPSON','Elaine','1992-06-28','elaine.thompson@utbm.fr','elainethompson92'),('VAN NIEKERK','Wayde','1992-07-15','wayde.vanniekerk@utbm.fr','waydevanniekerk92'),('ENGLAND','Scott','1996-03-15','scott.england@utbm.fr','scottengland96'),('ASHFORD','Evelyn','1957-04-15','evelyn.ashford@utbm.fr','evelynashford57'),('LEWIS','Carl','1961-07-01','carl.lewis@utbm.fr','carllewis61'),('KIPCHOGE','Eliud','1984-11-05','eliud.kipchoge@utbm.fr','eliudkipchoge84'),('ELFEGO','Abel','1993-08-29','abel.elfego@utbm.fr','abelelfego93'),('HASSAN','Sifan','1993-01-01','sifan.hassan@utbm.fr','sifanhassan93'),('GATLIN','Justin','1982-02-10','justin.gatlin@utbm.fr','justingatlin82'),('VOLKO','Jan','1996-06-06','jan.volko@utbm.fr','janvolko96'),('KRAMER','Sven','1986-02-21','sven.kramer@utbm.fr','svenkramer86'),('FROOME','Chris','1985-05-20','chris.froome@utbm.fr','chrisfroome85'),('COBURN','Emma','1990-10-19','emma.coburn@utbm.fr','emmacoburn90'),('BARSHIM','Mutaz','1991-06-24','mutaz.barshim@utbm.fr','mutazbarshim91'),('DE GRASSE','Andre','1994-11-10','andre.degrasse@utbm.fr','andredegrasse94');

INSERT INTO inscription(id_utilisateur,id_club,administrateur,date_adhesion,est_adherent) VALUES (1, 1, 1, '2024-05-10', 1), (2, 1, 1, '2024-05-10', 1), (3, 1, 0, NULL, 0), (4, 1, 0, NULL, 0), (5, 1, 0, NULL, 0),
(6, 1, 0, NULL, 0), (7, 1, 0, NULL, 0), (8, 1, 1, '2024-05-10', 1), (9, 1, 0, NULL, 0), (10, 1, 0, NULL, 0),(11, 2, 1, '2024-05-10', 1), (12, 2, 1, '2024-05-10', 1), (13, 2, 0, NULL, 0), (14, 2, 0, NULL, 0), (15, 2, 0, NULL, 0),
(16, 2, 0, NULL, 0), (17, 2, 0, NULL, 0), (18, 2, 1, '2024-05-10', 1), (19, 2, 0, NULL, 0), (20, 2, 0, NULL, 0),(21, 3, 1, '2024-05-10', 1), (22, 3, 1, '2024-05-10', 1), (23, 3, 0, NULL, 0), (24, 3, 0, NULL, 0), (25, 3, 0, NULL, 0),
(26, 3, 0, NULL, 0), (27, 3, 0, NULL, 0), (28, 3, 1, '2024-05-10', 1), (29, 3, 0, NULL, 0), (30, 3, 0, NULL, 0);


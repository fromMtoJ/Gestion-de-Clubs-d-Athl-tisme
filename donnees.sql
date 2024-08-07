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


INSERT INTO presence(id_club,id_installation) VALUES (1,1),(1,2),(1,4),(1,6),(1,7),(1,8),(1,9),(1,10),(1,12);
-- SQLBook: Markup

INSERT INTO utilisateur(nom,prenom,date_de_naissance,email,mdp) VALUES ('BENED','Julie','2005-09-17','julie.bened@utbm.fr','juliebened17'),('BEAUCHAMP','Manon','2005-01-13','manon.beauchamp@utbm.fr','manonbeauchamp13')

INSERT INTO inscription(id_utilisateur,id_club,administrateur,date_adhesion,est_adherent) VALUES (1,1,0,'2024-05-09',1),(2,1,1,'2024-05-09',1)

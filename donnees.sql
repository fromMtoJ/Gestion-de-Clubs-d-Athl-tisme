-- SQLBook: Code
-- Active: 1713532091000@@127.0.0.1@3306@donnees
-- SQLBook: Code
CREATE DATABASE IF NOT EXISTS donnees;
USE donnees;

CREATE TABLE IF NOT EXISTS clubs (
    id_club INT PRIMARY KEY AUTO_INCREMENT,
    nom_club VARCHAR(25)
);
CREATE TABLE IF NOT EXISTS disciplines(
    id_discipline INT PRIMARY KEY AUTO_INCREMENT,
    type_discipline VARCHAR(25)
);

CREATE TABLE IF NOT EXISTS installations (
    id_installation INT PRIMARY KEY AUTO_INCREMENT,
    nom_installation VARCHAR(25), 
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
    id_installation INT,
    FOREIGN KEY (id_installation) REFERENCES installations(id_installation),
    id_utilisateur INT, 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    date_reservation DATE, 
    duree_reservation INT
);

CREATE TABLE IF NOT EXISTS presence (
    id_club INT ,
    FOREIGN KEY (id_club) REFERENCES clubs(id_club),
    id_installation INT,
    FOREIGN KEY (id_installation) REFERENCES installations(id_installation)
);
-- SQLBook: Code
-- Active: 1713532091000@@127.0.0.1@3306@donnees
INSERT INTO clubs(nom_club) VALUES ('Belfort Athletic Club'),('Sevenans Athlé'),('Montbéliard Athlétisme');

INSERT INTO disciplines(type_discipline) VALUES ('Course'),('Saut'),('Lancer'),('Épreuves combinées');

INSERT INTO installations(nom_installation,id_discipline,piste) VALUES
('Sautoir de saut en hauteur',2,0),('Sautoir de saut en longueur',2,0),('Sautoir de triple saut',2,0),('Sautoir de saut à la perche',2,0),('Aire de lancer de disque ou marteau',3,0),('Aire de lancer de poids',3,0),('Aire de lancer de javelot',3,0),('Couloir 1',1,1),('Couloir 2',1,1),('Couloir 3',1,1),('Couloir 4',1,1),('Couloir 5 avec haies',1,1)
-- SQLBook: Markup

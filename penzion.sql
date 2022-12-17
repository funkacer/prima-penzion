-- Active: 1670240057439@@127.0.0.1@3306@penzion

--vytvoříme databázi
CREATE DATABASE penzion
    DEFAULT CHARACTER SET = 'utf8mb4';

--použijeme databázi
USE penzion;

--vytvoříme tabulku
CREATE TABLE spravce (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    heslo VARCHAR(255) NOT NULL
    );

--vložíme uživatele
INSERT INTO spravce SET username = "admin", heslo = "papousek123";
INSERT INTO spravce SET username = "jirka", heslo = "veslo123";

--zkontrolujeme
SELECT * FROM spravce;

--převedeme stránky do databáze
CREATE TABLE stranka (
    id VARCHAR(255) PRIMARY KEY,
    titulek VARCHAR(255),
    menu VARCHAR(255),
    obrazek VARCHAR(255),
    obsah TEXT,
    poradi INT DEFAULT 0
);

INSERT INTO stranka SET id = "domu", titulek = "Prima Penzion", menu = "Domů", obrazek = "primapenzion-main.jpg",
obsah = "aaaaaaaaaaaaaaa", poradi = 0;

DELETE FROM stranka;

SELECT * FROM stranka;
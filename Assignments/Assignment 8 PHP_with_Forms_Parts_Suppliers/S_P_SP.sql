/*
    Jacob Kurbis
    CSCI 466 Section: 1
    Assignment 8: PHPw/Forms-Parts/Suppliers
    File: S_P_SP.sql -This file implements the required tables for database that the PHP/PDO code will use
    Due: 11/20/2023

    Link: https://students.cs.niu.edu/~z1945650/csci466/parts_and_suppliers.php
*/

/* Get rid of Tables if they already exist */
DROP TABLE IF EXISTS SP, P, S;

/* Create Table S */
CREATE TABLE S(
    S CHAR(2) NOT NULL,
    SNAME CHAR(12) NOT NULL,
    STATUS INT NOT NULL,
    CITY CHAR(12) NOT NULL,

    Primary Key(S)
);

/* Create Table P */
CREATE TABLE P(
    P CHAR(2) NOT NULL,
    PNAME CHAR(12) NOT NULL,
    COLOR CHAR(12) NOT NULL,
    WEIGHT INT NOT NULL,

    Primary Key(P)
);

/* Create Table SP */
CREATE TABLE SP(
    S CHAR(2),
    P CHAR(2),
    QTY INT NOT NULL,

    Primary Key(S, P),
    Foreign Key(S) References S(S),
    Foreign Key(P) References P(P)
);

/* Insert data into Table S*/
INSERT INTO S VALUES('S1', 'Smith', '20', 'London');
INSERT INTO S VALUES('S2', 'Jones', '10', 'Paris');
INSERT INTO S VALUES('S3', 'Blake', '30', 'Paris');
INSERT INTO S VALUES('S4', 'Clark', '20', 'London');
INSERT INTO S VALUES('S5', 'Adams', '30', 'Athens');

/* Insert data into Table P*/
INSERT INTO P VALUES('P1', 'Nut', 'Red', '12');
INSERT INTO P VALUES('P2', 'Bolt', 'Green', '17');
INSERT INTO P VALUES('P3', 'Screw', 'Blue', '17');
INSERT INTO P VALUES('P4', 'Screw', 'Red', '14');
INSERT INTO P VALUES('P5', 'Cam', 'Blue', '12');
INSERT INTO P VALUES('P6', 'Cog', 'Red', '19');

/* Insert data into Table SP*/
INSERT INTO SP VALUES('S1', 'P1', '300');
INSERT INTO SP VALUES('S1', 'P2', '200');
INSERT INTO SP VALUES('S1', 'P3', '400');
INSERT INTO SP VALUES('S1', 'P4', '200');
INSERT INTO SP VALUES('S1', 'P5', '100');
INSERT INTO SP VALUES('S1', 'P6', '100');
INSERT INTO SP VALUES('S2', 'P1', '300');
INSERT INTO SP VALUES('S2', 'P2', '400');
INSERT INTO SP VALUES('S3', 'P2', '200');
INSERT INTO SP VALUES('S4', 'P2', '200');
INSERT INTO SP VALUES('S4', 'P4', '300');
INSERT INTO SP VALUES('S4', 'P5', '400');
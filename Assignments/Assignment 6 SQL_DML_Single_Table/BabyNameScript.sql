
/*
    Jacob Kurbis
    CSCI 466 Section: 1
    Assignment 6: SQL Script
    Due: 11/03/2023

    Note: This script had multiple "LIMIT 50" statements that were put there in accordance
    with the guidelines from the PDF's instructions. They have been removed. The output
    reflects when the "LIMIT 50" statements were implemented.
*/

\T ASSIGN6.out

/* 1). Select the BabyName database */
USE BabyName;

/* 2). List all of the tables that are present in the database */
SHOW TABLES;

/* 3). Show all of the names from your birth year. Each name must be shown only once */
SELECT DISTINCT name 
    FROM BabyName 
    WHERE year = 1996;

/* 4). Show all of the columns and their types for each table in the database. */
SHOW CREATE TABLE BabyName;

/* 5). List how many unique names there are in each location. */
SELECT place, COUNT(DISTINCT name) AS uniqNames 
    FROM BabyName 
    GROUP BY place;

/* 6). Show all of the years (once only) where your first name appears. Some people’s names may not be present in the database. If your name is one of
   those, then use ‘Chad’ if you are male, or ‘Stacy’ if you are female. If you don’t feel you fit into one of those, feel free to use ‘Pat’. */
SELECT DISTINCT year 
    FROM BabyName 
    WHERE name = 'Jacob';

/* 7). Display the most popular male and female names from the year 1984. */
SELECT gender, name, COUNT(*) AS count 
    FROM BabyName 
    WHERE year = 1984 
    GROUP BY gender, name 
    ORDER BY count DESC;

/* 8). Show all the information available about names similar to your name (or the one you adopted from above), sorted in alphabetical order by name,
   then, within that, by count, and finally, by the year. */
SELECT * FROM BabyName 
    WHERE name LIKE 'Jac%' 
    ORDER BY name ASC, count DESC, year ASC;

/* 9). Show how many unique names there were in the year 1947. */
SELECT COUNT(DISTINCT name) AS uniqNames 
    FROM BabyName 
    WHERE year = 1947;

/* 10). Show the number of rows that exist in the table. */
SELECT COUNT(*) AS numRows 
    FROM BabyName;

/* 11). Show how many names are in the table for each sex from the year 1969. */
SELECT gender, COUNT(*) AS count 
    FROM BabyName 
    WHERE year = 1969 
    GROUP BY gender;

/* 12. Show a table with a column called “First Letter” that contains one row per letter, and another column named “Frequency” that contains how many
   unique names begin with each of those letters. */
SELECT SUBSTRING(name, 1, 1) AS First_Letter, COUNT(DISTINCT name) AS Frequency 
    FROM BabyName 
    GROUP BY First_Letter;
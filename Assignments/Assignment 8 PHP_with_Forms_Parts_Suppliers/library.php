<?php

/*
    Jacob Kurbis
    CSCI 466 Section: 1
    Assignment 8: PHPw/Forms-Parts/Suppliers
    File: library.php - This file contains all of the backend functions called upon from 
                        parts_and_suppliers.php. It includes functions for displaying parts
                        and suppliers, "processing purchases", adding new parts, and 
                        establishing supplier-part relationships.
    Due: 11/20/2023

    Link: https://students.cs.niu.edu/~z1945650/csci466/parts_and_suppliers.php
*/

//Function to display all suppliers in a table format
function displaySuppliers($pdo)
{
    $sql = 'SELECT * FROM S;';//SQL query to select all suppliers

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        $stmt->execute();//Executing the SQL statement
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);//Fetching the results
        print_table($rows);//Calling the function to print these rows as a table
    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Failed to retrieve suppliers: {$e->getMessage()}</p>\n");
    }
}

//Function to display all parts in a table format
function displayParts($pdo)
{
    $sql = 'SELECT * FROM P;';//SQL query to select all parts

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        $stmt->execute();//Executing the SQL statement
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);//Fetching the results
        print_table($rows);//Calling the function to print these rows as a table
    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Failed to retrieve parts: {$e->getMessage()}</p>\n");
    }
}

//Function to display suppliers for a specific part
function displaySuppliersForPart($pdo, $part)
{
    //SQL query to select suppliers for a specific part, including part details
    $sql = 'SELECT S.S, S.SNAME, S.STATUS, S.CITY, P.P, P.PNAME, P.COLOR, P.WEIGHT, SP.QTY
            FROM SP
            JOIN S ON SP.S = S.S
            JOIN P ON SP.P = P.P
            WHERE SP.P = :part;';

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        $stmt->execute(['part' => $part]);//Executing the SQL statement
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);//Fetching the results

        //Error checking if any suppliers are found and displaying them
        if (empty($rows))
        {
            echo "<p>No suppliers found for the selected part ({$part}).</p>";
        }
        else 
        {
            print_table($rows);//Printing the suppliers and part details as a table
        }
    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Error retrieving suppliers for part: {$e->getMessage()}</p>\n");
    }
}

//Function to display parts provided by a specific supplier
function displayPartsForSupplier($pdo, $supplier)
{
    //SQL query to select parts provided by a specific supplier, including supplier details
    $sql = 'SELECT P.P, P.PNAME, P.COLOR, P.WEIGHT, S.S, S.SNAME, S.STATUS, S.CITY, SP.QTY
            FROM SP
            JOIN P ON SP.P = P.P
            JOIN S ON SP.S = S.S
            WHERE SP.S = :supplier;';

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        $stmt->execute(['supplier' => $supplier]);//Executing the SQL statement
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);//Fetching the results

        //Error checking if any parts are found and displaying them
        if (empty($rows))
        {
            echo "<p>No parts found for the selected supplier ({$supplier}).</p>";
        }
        else 
        {
            print_table($rows);//Printing the parts and suppier details as a table
        }
    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Error retrieving parts for supplier: {$e->getMessage()}</p>\n");
    }
}

//Function to process the buying of parts from a supplier
function buyParts($pdo, $part, $supplier, $quantity)
{
    //SQL query to retrieve the current quantity of a part from a supplier
    $sql = 'SELECT QTY FROM SP WHERE S = :supplier AND P = :part;';

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        $stmt->execute(['supplier' => $supplier, 'part' => $part]);//Executing the SQL statement with supplier and part parameters
        $currentQTYrow = $stmt->fetch(PDO::FETCH_ASSOC);//Fetching the current quantity

        //Checking if the part is available from the supplier
        if ($currentQTYrow)
        {
            $currentQTY = $currentQTYrow['QTY'];

            //Check if the requested quantity is available
            if ($currentQTY >= $quantity)
            {
                //Update the quantity after "purchase"
                $newQTY = $currentQTY - $quantity;
                $updateSQL = 'UPDATE SP SET QTY = :newQTY WHERE S = :supplier AND P =:part;';
                $updateStmt = $pdo->prepare($updateSQL);//Preparing the SQL statement
                //Executing the SQL statement
                $updateStmt->execute(['newQTY' => $newQTY, 'supplier' => $supplier, 'part' => $part]);
                echo "<p>Successfully bought {$quantity} units of part {$part} from supplier {$supplier}.</p>";
            }
            else 
            {
                //Error message if insufficient quantity is available
                echo "<p>Insufficient quantity available for part {$part} from supplier {$supplier}.</p>";
            }
        }
        else 
        {
            //Error message if the part is not supplied by the supplier
            echo "<p>Part {$part} is not supplied by supplier {$supplier}.</p>"; 
        }
    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Error during buying process: {$e->getMessage()}</p>\n");
    }
}

//Function to add a new part to the database
function addNewPart($pdo, $partID, $partName, $color, $weight)
{
    //SQL query to insert a new part
    $sql = 'INSERT INTO P VALUES(:partID, :partName, :color, :weight);';

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        //Executing the SQL statement with part details
        $stmt->execute(['partID' => $partID, 'partName' => $partName, 'color' => $color, 'weight' => $weight]);
        //Success message to display to user
        echo "<p>New part added successfully.</p>";

    }
    catch (PDOException $e)//Error handling
    {
        die("<p>Error adding new part: {$e->getMessage()}</p>\n");
    }
}

//Function to add a new supplier-part relationship to the database
function addSupplierPartRelation($pdo, $supplier,$part, $quantity)
{
    //SQL query to insert a new supplier-part relationship
    $sql = 'INSERT INTO SP VALUES(:supplier, :part, :quantity);';

    try 
    {
        $stmt = $pdo->prepare($sql);//Preparing the SQL statement
        //Executing the SQL statement with supplier, part, and quantity
        $stmt->execute(['supplier' => $supplier, 'part' => $part, 'quantity' => $quantity]);
        echo "<p>Supplier-Part relationship added successfully.</p>";//Success message to display to user

    } catch (PDOException $e)//Error handling
    {
        die("<p>Error adding Supplier-Part relationship: {$e->getMessage()}</p>");
    }
}

//Function to print a table from an array of rows
function print_table(array $rows)
{
    //Check if there are rows to display
    if (empty($rows))
    {
        echo "<p>No data to display.</p>";//Message if no data is available
        return;
    }

    //Start the table and add headers
    echo "<table style='border-collapse: collapse: width: 100%;'>\n";
    echo "<thead style='background-color: #f2f2f2;'>";

    //Loop to print table headers
    foreach (array_keys($rows[0]) as $heading)
    {
        echo "<th style='border: 1px solid #ddd; padding: 8px;'>$heading</th>\n";
    }
    echo "</thead>\n";

    //Nested loops to print table rows and table data
    echo "<tbody>";
    foreach ($rows as $row)
    {
        echo "<tr style='border-bottom: 1px solid #ddd;'>\n";

        foreach ($row as $col)
        {
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>$col</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
}

?>
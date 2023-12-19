<!-- 
    Jacob Kurbis
    CSCI 466 Section: 1
    Assignment 8: PHPw/Forms-Parts/Suppliers
    File: parts_and_suppliers.php - This file serves as the main interface for 
                                    managing parts and suppliers. It includes forms for
                                    viewing parts and suppliers, adding new parts, buying
                                    parts, and establishing supplier-part relationships. 
    Due: 11/20/2023

    Link: https://students.cs.niu.edu/~z1945650/csci466/parts_and_suppliers.php
-->

<!DOCTYPE html>
<html>
    <head>
        <title>Jacob Kurbis Z1945650 Suppliers and Parts</title>
        <!-- CSS Styling -->
        <style>
            h1
            {
                text-align: center;/*Centers main heading*/
            }

            /*Styling for the grid layout containing tables and forms*/
            .grid-container
            {
                display: grid;
                grid-template-columns: auto auto;/*Two equal columns*/
                justify-content: center;/*Center the grid*/
                gap: 20px;/*Adds space between grid items*/
                margin-bottom: 20px;/*Adds space below the grid*/
            }

            /*Styling for the container holding tables and forms*/
            .table-form-container
            {
                text-align: center;/*Center content inside each container*/
                width: 100%;
                margin: auto;
            }

            /*Styling for the tables*/
            table
            {
                margin-left: auto;/*Align table to the center*/
                margin-right: auto;
                width: 30%;/*Sets width of tables*/
            }

            /*Container for displaying output from form submissions*/
            .output-container
            {
                text-align: center;
                width: 100%;
            }

            /*Styling for spacing between different form sections*/
            .form-section
            {
                margin-bottom: 20px;/*Adds space between forms*/
            }
        </style>

    </head>
    <body>
        <h1>CSCI466 Assignment 8</h1><!-- Heading for Assignment -->

<?php

//Includes the files for the login into the database and the library of functions to be called upon
include '/home/data/www/z1945650/public_html/csci466/login.php';
include '/home/data/www/z1945650/public_html/csci466/library.php';

//Database config
$dbname = 'z1945650';


//PDO configuration for database connection
$dsn = "mysql:host=$host;dbname=$dbname";

$options = 
[
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

//Establishing connection to the database with error handling
try 
{
    $pdo = new PDO($dsn, $username, $password, $options);
} 
catch (PDOException $e) 
{
   die("<p>Connection to database failed: {$e->getMessage()}</p>\n");
}


echo '<div class="grid-container">';//Beginning of grid container for tables

//Display Parts Table
echo '<div class="table-form-container">';
echo "<h2>Parts Details</h2>";
displayParts($pdo);

//Part Selection Form
echo '<form method="post">
        <label for="part">Select a Part:</label>
        <select name="part" id="part">;
        <option value = "">Select a Part</option>';//Blank option for validation 

//Query to fetch all parts
$sql = 'SELECT P FROM P;';

try 
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //loop to populate part selection dropdown
    foreach ($parts as $part)
    {
        echo "<option value=\"{$part['P']}\">{$part['P']}</option>";
    }
} catch (PDOException $e) 
{
    die("<p>Error retrieving parts: {$e->getMessage()}</p>\n");
}

echo '  </select>
        <input type="submit" name="showSuppliersForPart" value="Show Suppliers">
      </form>';
echo '</div>';//End of table-form container for parts

//Display Suppliers Table
echo '<div class="table-form-container">';
echo "<h2>Suppliers Details</h2>";
displaySuppliers($pdo);

//Supplier Selection Form
echo '<form method="post">
        <label for="supplier">Select a Supplier:</label>
        <select name="supplier" id="supplier">
        <option value = "">Select a Supplier</option>';//Blank option for validation 

//Query to fetch all suppliers
$sql = 'SELECT S, SNAME FROM S;';

try 
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //Loop to populate selection dropdown
    foreach ($suppliers as $supplier)
    {
        echo "<option value=\"{$supplier['S']}\">{$supplier['SNAME']}</option>";
    }
} catch (PDOException $e) 
{
    die("<p>Error retrieving suppliers: {$e->getMessage()}</p>\n");
}

echo '  </select>
        <input type="submit" name="showPartsForSupplier" value="Show Parts">
      </form>';

echo '</div>';//End of table-form container for suppliers
echo '</div>';//Ending of grid container


//Output Container for Form Submission results
echo '<div class="output-container">';

//Handling the Part form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['showSuppliersForPart']))
{
    $selectedPart = $_POST['part'];

    //Check if a part was selected
    if ($selectedPart === "")
    {   
        //Error message if no part is selected
        echo "<p>Please select a part.</p>";
    }
    else 
    {
        //Function to display suppliers for the selected part
        displaySuppliersForPart($pdo, $selectedPart);
    }
}

//Handling the Supplier form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['showPartsForSupplier']))
{
    $selectedSupplier = $_POST['supplier'];

    //Check if supplier was selected
    if ($selectedSupplier === "")
    {   
        //Error message if no supplier is selected
        echo "<p>Please select a supplier.</p>";
    }
    else 
    {
        //Function to display parts for the selected supplier
        displayPartsForSupplier($pdo, $selectedSupplier);
    }
}

echo '</div>';//Close the output-container

//Buy Parts Form
echo '<h2>Buy Parts</h2>';
echo '<form method="post">';

//Part selection dropdown
echo '<div>
        <label for="partToBuy">Select a Part to Buy:</label>
        <select name="partToBuy" id="partToBuy">
        <option value = "">Select a part</option>';//Blank option for validation

//Code to populate parts
foreach ($parts as $part)
{
    echo "<option value=\"{$part['P']}\">{$part['P']}</option>";
}

echo '</select>
      </div>';//End of part selection dropdown

//Supplier selection dropdown
echo '<div>
        <label for="supplierToBuyFrom">Select a Supplier:</label>
        <select name="supplierToBuyFrom" id="supplierToBuyFrom">
        <option value = "">Select a Supplier</option>';//Blank option for validation


//Code to populate suppliers
foreach ($suppliers as $supplier)
{
    echo "<option value=\"{$supplier['S']}\">{$supplier['SNAME']}</option>";
}

echo '</select>
      </div>';//End of supplier selection dropdown

//Quantity input field for specifying the number of parts to buy
echo '<label for="quantityToBuy">Quantity:</label>
      <input type="number" name="quantityToBuy" id="quantityToBuy" min="1">
      <div>
        <input type="submit" name="buyParts" value="Buy Parts">
      </div>
      </form>';

//Handling the Buy Parts form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buyParts']))
{
    $partToBuy = $_POST['partToBuy'];
    $supplierToBuyFrom = $_POST['supplierToBuyFrom'];
    $quantityToBuy = $_POST['quantityToBuy'];

    //Error check to ensure that all fields are filled out
    if ($partToBuy !== "" && $supplierToBuyFrom !== "" && $quantityToBuy > 0)
    {   
        //Function call to process the buying of parts
        buyParts($pdo, $partToBuy,$supplierToBuyFrom, $quantityToBuy);
    }
    else 
    {
        //Error message displayed if any field is left empty
        echo "<p>Please select a part, supplier, and enter a valid quantity.</p>";
    }
}

//Add New Part Form
echo '<div class="form-section">
        <form method="post">
            <h2>Add New Part</h2>
            <label for="newPartID">Part ID:</label>
            <input type="text" name="newPartID" id="newPartID" required><br>

            <label for="newPartName">Part Name:</label>
            <input type="text" name="newPartName" id="newPartName" required><br>

            <label for="newPartColor">Color:</label>
            <input type="text" name="newPartColor" id="newPartColor" required><br>

            <label for="newPartWeight">Weight:</label>
            <input type="number" name="newPartWeight" id="newPartWeight" min="1" required><br>

            <input type="submit" name="addNewPart" value="Add Part">
        </form>
     </div>';

//Handling Add New Part Form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addNewPart']))
{
    $newPartID = $_POST['newPartID'];
    $newPartName = $_POST['newPartName'];
    $newPartColor = $_POST['newPartColor'];
    $newPartWeight = $_POST['newPartWeight'];

    //Error checking to ensure the new part details are valid
    $checkSql = 'SELECT COUNT(*) FROM P WHERE P = :partID';
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute(['partID' => $newPartID]);
    $exists = $stmt->fetchColumn() > 0;

    //Multiple error checks for partID, partID length, name length, and weight
    if($exists)//If a part entered by the user already exists in the database(Integrity Constraint)
    {
        echo "<p>A part with ID '{$newPartID}' already exists. Please use a different ID.</p>";
    }
    elseif (strlen($newPartID) != 2)//If the part number is not exactly two characters long
    {
        echo "<p>Part ID is too long or too short, enter exactly a two character part ID.</p>";
    }
    elseif ($newPartName > 12 )//If the part name is more than 12 characters long
    {
        echo"<p>Part name is too long, please enter a part name equal to twelve characters or less. And not empty!.</p>";
    }
    elseif ($newPartWeight <= 0)//If the weight is less than 0 
    {
        echo"<p>Invalid weight enetered. The weight cannot be less than or equal to zero.</p>";
    }
    else 
    {
        //Call function to add a new part
        addNewPart($pdo, $newPartID, $newPartName, $newPartColor, $newPartWeight);
    }

}

//Form for Adding Supplier-Part Relationship
echo '<div class="form-section">
        <form method="post">
            <h2>Add Supplier-Part</h2>
            <label for="relSupplier">Select a Supplier:</label>
            <select name="relSupplier" id="relSupplier">
                <option value "">Select a Supplier</option>';
                //Code to populate Suppliers
                foreach ($suppliers as $supplier)
                {
                    echo "<option value=\"{$supplier['S']}\">{$supplier['SNAME']}</option>";
                }
            echo '</select><br>

            <label for="relpart">Select a Part:</label>
            <select name="relPart" id="relPart">
                <option value="">Select a Part</option>';
                //Code to poulate Parts
                foreach ($parts as $part)
                {
                    echo "<option value=\"{$part['P']}\">{$part['P']}</option>";
                }
            echo '</select><br>
            
            <label for="relQuantity">Quantity:</label>
            <input type="number" name="relQuantity" id="relQuantity" min="1" required><br>

            <input type="submit" name="addRel" value="Add Relationship">
        </form>
     </div>';

//Handling the Add Supplier-Part Relationship form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addRel']))
{
    $relSupplier = $_POST['relSupplier'];
    $relPart = $_POST['relPart'];
    $relQuantity = $_POST['relQuantity'];

    //Error check to ensure all fields are filled out
    if ($relSupplier !== "" && $relPart !== "" && $relQuantity > 0)
    {
        //Checking if the relationship already exists in the database
        $checkSql = 'SELECT COUNT(*) FROM SP WHERE S = :supplier AND P = :part';
        $stmt = $pdo->prepare($checkSql);
        $stmt->execute(['supplier' => $relSupplier, 'part' => $relPart]);
        $exists = $stmt->fetchColumn() > 0;
        
        //Check if the relationship already exists
        if ($exists)//If the relationship already exists
        {
            echo "<p>A relationship between supplier '{$relSupplier}' and part '{$relPart}' already exists.</p>";
        }
        else 
        {
            //Call function to add the new supplier-part relationship to the database
            addSupplierPartRelation($pdo, $relSupplier, $relPart, $relQuantity);
        }
    }
    else 
    {
        //Error message if any field is left empty
        echo "<p>Please select a supplier, part, and enter a valid quantity.</p>";
    }
    
}
?>

    </body>
</html>
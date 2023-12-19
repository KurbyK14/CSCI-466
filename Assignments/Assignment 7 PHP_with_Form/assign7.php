<?php

/*
    Jacob Kurbis
    CSCI 466 Section: 1
    Assignment 7: PHP with Forms
    Due: 11/13/2023

    Link to hosted php file: https://students.cs.niu.edu/~z1945650/assign7.php
*/

/*Generates dropdown options 0 through 9 for the number selection in the form,
  and a blank option as the first choice so it does not select 0 as a default.*/
function generateDropDownOptions()
{
    echo '<option value = "">Select a number</option>';//Blank option for validation 

    for ($i = 0; $i <= 9; $i++)
    {
        echo "<option value = \"$i\">$i</option>";//Loop to generate numbers 0 to 9 as options
    }
}

//Validates the form input to check if it's set and not empty/only whitespace
function validateInput($input)
{
    return isset($input) && trim($input) !== '';//Returns true if $input is set and not an empty string or only whitespace
}

//Variables to store the HTML output of the list and mutliplication table
$listDisplay = '';
$multiplicationDisplay = '';

//Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //Check and process input for the list (word, count, list type)
    if (validateInput($_POST['word']) && validateInput($_POST['count']) && validateInput($_POST['listtype']))
    {
        //Clean up and store form data
        $word = $_POST['word'];
        $count = intval($_POST['count']);//Integer typecast for count variable
        $listType = $_POST['listtype'];

        //Determine and start the type of list based on user input
        if ($listType === 'ordered')
        {
            $listDisplay = '<ol>'; //Opening ordered numbered list tag in HTML
        }
        else
        {
            $listDisplay = '<ul>'; //Opening unordered bullet list tag in HTML
        }

        //Append list items based on count and word
        for ($i = 0; $i < $count; $i++)
        {
            $listDisplay .= "<li> $word </li>"; //Using concatenation .= operator to append each word to the list
        }

        //Closing the list tag
        if ($listType === 'ordered')
        {
            $listDisplay .= '</ol>';//Closing ordered numbered list tag in HTML
        }
        else
        {
            $listDisplay .= '</ul>';//Closing unordered bullet list tag in HTML
        }
    }

    //Display error message if the list parameters are missing
    else
    {
        $listDisplay = '<p> List display parameters not received. </p>';
    }

    //Check and process input for the multiplication table
    if (validateInput($_POST['numstart']) && validateInput($_POST['numstep']) && validateInput($_POST['numnums']))
    {
        //Clean up and store form data
        $numStart = intval($_POST['numstart']);//Typecast conversion to an integer value for numStart
        $numStep = intval($_POST['numstep']);//Typecast conversion to an integer value for numStep
        $numNums = intval($_POST['numnums']);//Typecast conversion to an integer value for numNums

        //Build the multiplication table
        $multiplicationDisplay = '<table border = "1">';//Opening tag for multiplication table

        //Nested loops to create rows and columns of the table
        for ($i = 0; $i <= $numNums; $i++)
        {
            $multiplicationDisplay .= '<tr>';//Add opening tag for table row

            for ($j = 0; $j <= $numNums; $j++)
            {
                //Check for first row/column for table headers
                if($i == 0 || $j == 0)
                {
                    /*This calculation adds the starting number to the proudct of numSteps(provided by the user) and the max value 
                    of the two indexes to make a header that increments properly.*/
                    $cellValue = $numStart + ($numStep * max($i, $j));

                    //Make the table header from cellValue(calculation from before)
                    $multiplicationDisplay .= "<th> $cellValue </th>";
                }
                //Calculate and display cell values
                else
                {
                    /*This calculation adds the starting number(numStart) to the product of the current row index(i) and the
                    step size(numStep), and multiplies it with the sum of the starting number and column index(j) and step size.*/
                    $cellValue = ($numStart + ($i * $numStep)) * ($numStart + ($j * $numStep));

                    //Appends the cell value to the table data
                    $multiplicationDisplay .= "<td> $cellValue</td>";
                }
            }
            $multiplicationDisplay .= '</tr>';//Closing tag for table row
        }
        $multiplicationDisplay .= '</table>';//Closing tag for table
    }
    //Display error message if multiplication table parameters are missing
    else
    {
        $multiplicationDisplay = '<p> Multiplication parameters not received. </p>';
    }
}

?>
<!-- HTML Structure -->
<!DOCTYPE html>
<html>

<head>
    <title> Jacob Kurbis Z1945650  </title>
</head>

<body>
    <!--  Heading for page -->
    <h1> CSCI466 Assignment 7 </h1> 
    
    <!-- Form Structure -->
    <form method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <!-- Textboxes for word and count -->
    <label for = "word"> Word: </label>
    <input type = "text" id = "word" name ="word"><br><br>
    <label for = "count"> Count: </label>
    <input type = "text" id = "count" name = "count"> <br><br>

    <!-- Radio buttons for list type -->
    <input type = "radio" id = "ordered" name = "listtype" value = "ordered">
    <label for = "ordered"> Ordered </label>
    <input type = "radio" id = "unordered" name = "listtype" value = "unordered">
    <label for = "unordered"> Unordered </label> <br><br>

    <!-- Dropdown boxes for numstart, numstep, and numnums  -->
    <label for = "numstart"> Start Number: </label>
    <select name = "numstart" id = "numstart"> <?php generateDropDownOptions(); ?> </select> <br><br>
    <label for = "numstep">  Number of Steps: </label>
    <select name = "numstep" id = "numstep"> <?php generateDropDownOptions(); ?> </select> <br><br>
    <label for = "numnums"> Number of Numbers: </label>
    <select name = "numnums" id = "numnums"> <?php generateDropDownOptions(); ?> </select> <br><br>

    <!-- Submit Button -->
    <input type = "submit" value = "LET'S GOOOO">

    </form>

    <!-- PHP section to display results -->
    <?php

    echo $listDisplay;
    echo $multiplicationDisplay;

    ?>
</body>
</html>
<?php
session_start();
include ("db.php");
$pagename= "smart basket"; //Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>"; //display name of the page as window title
echo "<body>";

include ("headfile.html"); //include header layout file 
echo "<h4>".$pagename."</h4>"; //display name of the page on the web page

//if the posted ID of the new product is set i.e. if the user is adding a new product into the basket
if (isset($_POST['h_prodid']))
{
    //capture the ID of selected product using the POST method and the $_POST superglobal variable
    //and store it in a new local variable called $newprodid
    $newprodid=$_POST['h_prodid'];

    //capture the required quantity of selected product using the POST method and $_POST superglobal variable
    //and store it in a new local variable called $reququantity
    $reququantity=$_POST['p_quantity'];

    //Display id of selected product
    //echo "<p>Id of selected product: ".$newprodid;
    //Display quantity of selected product
    //echo "<br>Quantity of selected product: ".$reququantity;
    //create a new cell in the basket session array. Index this cell with the new product id.
    //Inside the cell store the required product quantity
    $_SESSION['basket'][$newprodid]=$reququantity;

    //Display "1 item added to the basket " message
    echo "<p>1 item added";
}
if(isset($_POST['del_prodid'])) {
        unset($_SESSION['basket'][$_POST['del_prodid']]);
    }
	
//else
//Display "Current basket unchanged " message
else
{
    echo "<p><b>Basket unchanged</b>";
}
//Create a variable $total and initialize it to zero
$total= 0; 

//Create HTML table with header to display the content of the basket: prod name, price, selected quantity and subtotal
echo "<p><table id='baskettable'>";
echo "<tr>";
echo "<th>Product Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Remove Product</th>";
echo "</tr>";

//if the session array $_SESSION['basket'] is set
if (isset($_SESSION['basket']))
	
{
    //loop through the basket session array for each data item inside the session using a foreach loop
    //to split the session array between the index and the content of the cell
    //for each iteration of the loop
    //store the id in a local variable $index & store the required quantity into a local variable $value
    foreach($_SESSION['basket'] as $index => $value)
{
    //SQL query to retrieve from Product table details of selected product for which id matches $index
    //execute query and create array of records $arrayp
    $SQL="select prodId, prodName, prodPrice from Product where prodId=".$index;
    $exeSQL=mysqli_query($conn, $SQL) or die (mysql_error());
    $arrayp=mysqli_fetch_array($exeSQL);
    echo "<tr>";

    //display product name & product price using array of records $arrayp
    echo "<td>".$arrayp['prodName']."</td>";
    echo "<td>&pound".number_format($arrayp['prodPrice'],2)."</td>";

    // display selected quantity of product retrieved from the cell of session array and now in $value
    echo "<td style='text-align:center;'>".$value."</td>";

    //calculate subtotal, store it in a local variable $subtotal and display it
    $subtotal=$arrayp['prodPrice'] * $value;
    echo "<td>&pound".number_format($subtotal,2)."</td>";
    echo "<td>";
    echo "<form method=post action=basket.php>";
    echo "<input type=hidden name=del_prodid value=".$index.">";
    echo "<input type=submit name='submitbtn' value='Remove' id='submitbtn'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
    //increase total by adding the subtotal to the current total
    $total=$total+$subtotal;
}
}
//else display empty basket message
else
{
    echo "<p>Empty basket";
}

// Display total
echo "<tr>";
echo "<td colspan=4><b>TOTAL</td></b>";
echo "<td>&pound".number_format($total,2)."</td>";
echo "</tr>";
echo "</table>";

echo "<br><p><a href='clearbasket.php'>CLEAR BASKET</a></p>";
echo "<br><p>New homteq customers: <a href='signup.php'>Sign up</a></p>";
echo "<p>Returning homteq customers: <a href='login.php'>Login</a></p>";

include ("footfile.html");
echo "</body>";


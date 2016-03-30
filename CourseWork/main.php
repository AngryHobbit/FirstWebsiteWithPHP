<?php

function connect(){
    $host = "localhost";
    $user = "Balloons";
    $pass = "pa$$";
    $db = "balloons";
    
    $conn = mysqli_connect($host, $user, $pass, $db);
    
    return $conn;
}


function subscribe($email){
    $conn = connect();
    $query = "INSERT INTO bb_newsletter VALUES ('$email')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: index.html");
 
}

function register($email, $fname, $sname, $pcode, $pass){
    $conn = connect();
    $query = "INSERT INTO bb_customer VALUES('$email', '$fname', '$sname', '$pcode', '$pass')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: index.html");
}

function login($email, $pass){
    $conn = connect();
    $query = "SELECT * FROM bb_customer WHERE email = '$email' AND pass = '$pass'";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    if (mysqli_num_rows($result)  == 1){
        session_start();
        $_SESSION['user'] = $email;
        $msg = "You have logged in";
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'index.html#register';
            </script>
            ";
    }else{
        $msg = "Your username/password was not recognized - try again!";
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'index.html#register';
            </script>";
    }
}

function logout()
{
    session_start();
    session_destroy();
    $msg = "You have now logout";
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'index.html#register';
            </script>";
    exit;   
}


function display_products(){
    $conn = connect();
    $query = "SELECT * FROM bb_product";
    $results = mysqli_query($conn, $query);
    echo "<table><tr>
        <th>Product Name</th>
        <th>Description</th>
        <th>Image</th>
        <th>Price</th>
        <th>Order</th>
        </tr>
        ";
    while($row = mysqli_fetch_array($results) ){
        echo "<tr>
            <td>$row[name]</td>
            <td>$row[description]</td>
   <td><img src='$row[imagepath]' width='150' height='150' /></td>
            <td>$row[price]</td>
            <td>
            
            <form action='basket.php' method='post'>

<input type='submit' value='Add to basket' name='$row[pid]' />

            </form>
            
            </td>
            </tr>";
    }
    echo "</table>";
    
}


function add_to_basket($pid){
    session_start();
    if (  isset($_SESSION['basket'])  ) {
        if ( isset($_SESSION['basket'][$pid]) ){
            $_SESSION['basket'][$pid]++;
        }else {
            $_SESSION['basket'][$pid]=1;
        }
        
    }else {
        $_SESSION['basket'] = array($pid => 1);
    }
    header("Location: basket.html");
}

function display_basket(){
    if ( !isset($_SESSION['basket']) ){
        echo "<p>Your basket is empty. To order items please go to the products page.</p>";
        return;
    }
    echo "<table><tr>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
        <th>Remove</th>
        </tr>";
    $conn = connect();
    $total = 0;
    foreach ($_SESSION['basket'] as $key=>$value) {
        $query = "SELECT name, price FROM bb_product WHERE pid=$key";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        echo "<tr>
            <td>$row[name]</td>
            <td>$value</td>
            <td>$row[price]</td>
            <td>". number_format($value*$row['price'], 2, '.', '')."</td>
            <td><form action='remove.php' method='post'><input type='submit' value='Remove' /></form></td>
            </tr>";
        $total = $total + $value*$row['price'];
    }
    echo "</table>";
    mysqli_close($conn);
    echo "<table><tr>
        <th>Total</th>
        <th>Order</th>
        </tr>
        <tr>
        <td>". number_format($total, 2, '.', '') ."</td>
        <td><form action='order.php' method='post'><input type='submit' value='Order' /></form></td>
        </tr>
        </table>";
}

function order(){
    session_start();
    if ( !isset($_SESSION['user']) ){
        $msg = "You must be registered and logged in to order items";
        echo "<script type='text/javascript'>
            alert('$msg');
            window.location = 'index.html#register';
            </script>
            ";
    }
    $conn = connect();
    $query = "INSERT INTO bb_order VALUES(NULL, '$_SESSION[user]')";
    mysqli_query($conn, $query);
    $oid = mysqli_insert_id($conn);
    
    foreach ($_SESSION['basket'] as $key=>$value) {
        $query = "INSERT INTO bb_orderitems VALUES($oid, $key, $value)";
        mysqli_query($conn, $query);
    }
    unset($_SESSION['basket']);
    mysqli_close($conn);
    $msg = "Your order has been received.";
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location = 'index.html#register';
        </script>
        ";
}

function remove()
{
    
    $msg = "You have removed a item.";
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location = 'basket.html';
        </script>
        ";
}
?>
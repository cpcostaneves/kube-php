<!--
//----------------------------------------------------------------------
// File: index.php
// Author: Cristiano Pedrassani Costa Neves
// Date: 24 apr 2019
// Brief: Web Server index.
//----------------------------------------------------------------------
-->

<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
}

th, td {
  padding: 5px;
}

</style>
</head>
<body>

<?php


// Connect to Redis
$redis = new Redis();
$redis->connect('redis', 6379 );

// Get data from Redis
$custsrc = "Memory/Cache";
$customers = $redis->exists('customers') ? unserialize($redis->get('customers')): array();

if (empty($customers))
{
  // Customer list is empty. Get it from database
  $dbpdo = new PDO('pgsql:host=postgres;dbname=service', 'service', 'mysecretpassword');
  $dbres = $dbpdo->query("SELECT name FROM customers");
  // Copy to array
  while ($row = $dbres->fetch())
  {
    $customers[] = $row['name'];
  }
  // Save to Redis
  $redis->set('customers', serialize($customers));    
  $custsrc = "Database";
}
?>

<h1>Customers</h1>

<p> Data source: <?php echo $custsrc; ?> </p>

<table>
  <tr>
    <th>Name</th>
  </tr>

<?php
foreach($customers as $customer) 
    echo ' <tr><td>' . $customer . '</td></tr>';
?>

</table>


</body>
</html>

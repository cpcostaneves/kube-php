<!--
//----------------------------------------------------------------------
// File: init.php
// Author: Cristiano Pedrassani Costa Neves
// Date: 24 apr 2019
// Brief: Web Server init.
//----------------------------------------------------------------------
-->

<?php

  // Populate DB
  // Connect
  $dbpdo = new PDO('pgsql:host=postgres;dbname=service', 'service', 'mysecretpassword');
  // Create table
  $createres = $dbpdo->query("CREATE TABLE customers (id bigserial primary key, name varchar(50) NOT NULL, dateadded timestamp default NULL);");
  // Insert sample data
  $insertres = $dbpdo->query("INSERT INTO customers (name) VALUES ('John Doe'), ('Jane Doe'), ('Another Name');");

?>

<h1>Initialized!!</h1>




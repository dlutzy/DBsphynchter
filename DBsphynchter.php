This thing does a count(*) of all the tables in a source and destination database. It is useful for spotting tables that might not be in sync. It is bad in so many ways it's not worth counting them. It will do bad things to your database and has no concept of security.  Dedicated to a DBA I used to work with who had a way with words. Especially 'sphincter'.  He would not approve.  

<form action="DBsphynchter.php" method="GET">
 <table border="0" cellpadding="0" cellspacing="2" width=500>
    <tr>
    <td><strong>Source Database server:</strong></font>
      <input type=text name="source_host" size=55 required="Yes" >
    <td><strong>Source Database name:</strong></font>
      <input type=text name="source_db" size=55 required="Yes" >
    <td><strong>Source Database username:</strong></font>
      <input type=text name="source_user" size=55 required="Yes" >
    <td><strong>Source Database password:</strong></font>
      <input type=text name="source_pass" size=55  >
  </tr><tr>
    <td><strong>Destination Database server:</strong></font>
      <input type=text name="dest_host" size=55 required="Yes" >
    <td><strong>Destination Database name:</strong></font>
      <input type=text name="dest_db" size=55 required="Yes" >
    <td><strong>Destination Database username:</strong></font>
      <input type=text name="dest_user" size=55 required="Yes" >
    <td><strong>Destination Database password:</strong></font>
      <input type=text name="dest_pass" size=55  >
   </tr><tr>
    <td><strong>I know what I'm doing.</strong>
      <input type=checkbox>
      <input type=submit value="Really! I really know what I'm doing" >
   </td></tr>
</table>
</form>
<?php
ini_set('display_errors', '0');

$source_host=$_GET["source_host"];
$source_db=$_GET["source_db"];
$source_user=$_GET["source_user"];
$source_pass=$_GET["source_pass"];
$source_db=$_GET["source_db"];
$dest_host=$_GET["dest_host"];
$dest_db=$_GET["dest_db"];
$dest_user=$_GET["dest_user"];
$dest_pass=$_GET["dest_pass"];


$con1=mysqli_connect($source_host,$source_user,$source_pass,$source_db);

if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$con2=mysqli_connect($dest_host,$dest_user,$dest_pass,$dest_db);

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$result = mysqli_query($con1,"show tables");

  echo "<table border=1><tr><th>Table Name</th><th>Source Rows</th><th>Dest Rows</th><tr>";

while($row = mysqli_fetch_array($result))
{
  echo "<tr><td>";
  echo $row[0];
  echo "</td>";

  $srows = mysqli_query($con1,"select count(*) from $row[0]");
  $srow_count = mysqli_fetch_row($srows);
  $drows = mysqli_query($con2,"select count(*) from $row[0]");
  $drow_count = mysqli_fetch_row($drows);
  echo "<td>";
  echo $srow_count[0];
  echo "</td>";
  if ($srow_count[0] == $drow_count[0])
  {  
    echo "<td>";
  }
  else
  {  
    echo "<td bgcolor=red>";
  }
  echo $drow_count[0];
  echo "</td>";
  echo "</tr>";
}

echo "</table>";
mysqli_close($con1);
mysqli_close($con2);



?>

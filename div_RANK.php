<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

  echo "Ranking<br>";
  $userid = $_SESSION['FBid'];

  
  $sql = "SELECT userid, SUM(caixa + margem) AS Ativos FROM Clientes GROUP BY userid ORDER BY Ativos desc";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
     $formname = 'Rank'; 
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     echo "<input type='text' size='20' name='UserID' value='" . $row[0] . "'></input>";
     echo "<input type='text' size='20' name='Ativos' value='" . $row[1] . "'></input>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';
?>

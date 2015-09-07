<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

  echo "Ranking<br>";
  $userid = $_SESSION['FBid'];

  
  $sql = "SELECT userid, Nome, SUM(caixa + margem) AS Ativos FROM Clientes GROUP BY userid ORDER BY Ativos desc";
  $retorno = (new minhaclasse())->usaDB("$sql");
  $formname = 'Rank'; 
  echo "<form name='" . $formname . "'>";
  $i = 1;
  foreach($retorno as $row) {
     echo "<div>"; 
     echo "<input type='text' size='20' name='Ranking' value='" . i . "'></input>";
     $i++;
     echo "<img src='https://graph.facebook.com/".$row[0]."/picture?type=small'/>";
     echo "<input type='text' size='20' name='UserID' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='20' name='Ativos' value='" . $row[2] . "'></input>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';
?>

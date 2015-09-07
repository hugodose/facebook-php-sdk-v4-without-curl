<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

  echo "Ranking<br>";
  $userid = $_SESSION['FBid'];

  
  $sql = "SELECT userid, Nome, SUM(caixa + margem) AS Ativos, Pnl/Risco *100 FROM Clientes GROUP BY userid ORDER BY Ativos desc";
  $retorno = (new minhaclasse())->usaDB("$sql");
  $formname = 'Rank'; 
  $i = 1;
  echo "<form name='" . $formname . "'>";
  echo "<div>"; 
  echo "<input type='text' size='4' name='Foto' value='Foto'></input>";
  echo "<input type='text' size='4' name='Ranking' value='Ranking'></input>";
  echo "<input type='text' size='20' name='Nome' value='Nome'></input>";
  echo "<input type='text' size='6' name='Ativos' value='Ativos'></input>";
  echo "<input type='text' size='6' name='Lucro/Risco' value='Lucro/Risco'></input>";
  echo "<br>";
  foreach($retorno as $row) {
     echo "<img src='https://graph.facebook.com/".$row[0]."/picture?type=small'/>";
     echo "<input type='text' size='4' name='Ranking' value='" . $i . "'></input>";
     $i++;
     echo "<input type='text' size='20' name='Nome' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='6' name='Ativos' value='" . $row[2] . "'></input>";
     echo "<input type='text' size='6' name='Lucro/Risco' value='" . round($row[3]) . "%'></input>";
     echo "<br>";
  }
  echo "</div>"; 
  echo "</form>";
  echo '<br>';
?>

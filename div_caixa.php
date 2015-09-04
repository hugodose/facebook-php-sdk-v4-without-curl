<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
  //$FBid = '12001';
  //$FBid = $_POST['FBid'];
  echo "CAIXA<br>";
  $FBid = $_SESSION['FBid'];
  $sql = "SELECT caixa, margem, pnl, risco from Clientes WHERE userid = '$FBid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
     $formname = 'Caixa' . $row[0]; 
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     echo "<input type='text' size='20' name='Ativos' value='" . $row[1] + $row[2] . "'></input>";
     echo "<input type='text' size='20' name='Caixa' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='20' name='Margem' value='" . $row[2] . "'></input>";
     echo "<input type='text' size='18' name='Retorno' value='" . $row[3] . "'></input>";
     echo "<input type='text' size='18' name='Risco' value='" . $row[4] . "'></input>";
     echo "<input type='text' size='20' name='Retorno/Risco' value='" . $row[3]/$row[4]*100 . "%'></input>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';
?>

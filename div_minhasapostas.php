<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
  //$FBid = '12001';
  //$FBid = $_POST['FBid'];
  $FBid = $_SESSION['FBid'];
  $sql = "SELECT id, data, campeonato, time1, time2, datajogo, notional, escolha, odds, resultado, pnl from Apostas WHERE userid = '$FBid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
     $formname = 'Aposta' . $row[0]; 
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     echo "<input type='text' size='20' name='Data' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='20' name='Campeonato' value='" . $row[2] . "'></input>";
     echo "<input type='text' size='18' name='Time1' value='" . $row[3] . "'></input>";
     echo "<input type='text' size='18' name='Time2' value='" . $row[4] . "'></input>";
     echo "<input type='text' size='20' name='DataJogo' value='" . $row[5] . "'></input>";
     echo "<input type='text' size='4' name='Notional' value='" . $row[6] . "'></input>";
     echo "<input type='text' size='6' name='Escolha' value='" . $row[7] . "'></input>";
     echo "<input type='text' size='4' name='Odds' value='" . $row[8] . "'></input>";
     echo "<input type='text' size='6' name='Resultado' value='" . $row[9] . "'></input>";
     echo "<input type='text' size='6' name='Pnl' value='" . $row[10] . "'></input>";
     //echo "<textarea name='Data' rows='1' cols='20'>" . $row[1] . "</textarea>";
     //echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[2] . "</textarea>";
     //echo "<textarea name='Time1' rows='1' cols='20'>" . $row[3] . "</textarea>";
     //echo "<textarea name='Time2' rows='1' cols='20'>" . $row[4] . "</textarea>";
     //echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[5] . "</textarea>";
     //echo "<textarea name='Notional' rows='1' cols='20'>" . $row[6] . "</textarea>";
     //echo "<textarea name='Escolha' rows='1' cols='20'>" . $row[7] . "</textarea>";
     //echo "<textarea name='Odds' rows='1' cols='20'>" . $row[8] . "</textarea>";
     //echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[9] . "</textarea>";
     //echo "<textarea name='Pnl' rows='1' cols='20'>" . $row[10] . "</textarea>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';

?>

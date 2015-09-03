<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
  //$FBid = '12001';
  $FBid = $_POST['FBid'];
  $sql = "SELECT id, data, campeonato, time1, time2, datajogo, notional, escolha, odds, resultado, pnl from Apostas WHERE userid = '$FBid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
     $formname = 'Aposta' . $row[0]; 
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     echo "<textarea name='Data' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[3] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[4] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[5] . "</textarea>";
     echo "<textarea name='Notional' rows='1' cols='20'>" . $row[6] . "</textarea>";
     echo "<textarea name='Escolha' rows='1' cols='20'>" . $row[7] . "</textarea>";
     echo "<textarea name='Odds' rows='1' cols='20'>" . $row[8] . "</textarea>";
     echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[9] . "</textarea>";
     echo "<textarea name='Pnl' rows='1' cols='20'>" . $row[10] . "</textarea>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';

?>

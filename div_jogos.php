<?php
 require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');


   $sql = "SELECT Campeonato, Time1, Time2, DataJogo, Casa, Empate, Fora, Resultado from Jogos";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     echo "<form action='div_enviaapostas.php' method='POST'>";
     echo "<div>"; 
     echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[0] . "</textarea>";
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[3] . "</textarea>";
     echo "<textarea name='Casa' rows='1' cols='20'>" . $row[4] . "</textarea>";
     echo "<textarea name='Empate' rows='1' cols='20'>" . $row[5] . "</textarea>";
     echo "<textarea name='Fora' rows='1' cols='20'>" . $row[6] . "</textarea>";
     echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[7] . "</textarea>";
     echo "<textarea name='Notional' rows='1' cols='20'> ..notional.. </textarea>";
     echo "<textarea name='Escolha' rows='1' cols='20'> ..escolha.. </textarea>";
     echo "<input value='Go' type='button' onclick='JavaScript:xmlhttpPost('div_enviaapostas.php')'>";
     echo "</div>"; 
     echo "</form>";
   }
 
  
 ?>

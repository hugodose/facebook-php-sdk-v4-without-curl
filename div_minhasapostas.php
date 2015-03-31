<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

if (isset($_POST['Load'])) {
  //$sql = "SELECT Time1, Time2, DataJogo from Jogos WHERE Resultado = ''";
  $sql = "SELECT Time1, Time2, DataJogo, Resultado from Jogos";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
    echo "<form action='' method='POST'>";
    echo "<div>";
    echo "<textarea name='Time1' rows='1' cols='20'>" . $row[0] . "</textarea>";
    echo "<textarea name='Time2' rows='1' cols='20'>" . $row[1] . "</textarea>";
    echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[2] . "</textarea>";
    echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[3] . "</textarea>";
    echo "<input type='submit' name='Input' value='Envia'>";
    echo "</div>";
    echo "</form>";
  }
echo '<br>';
}
?>

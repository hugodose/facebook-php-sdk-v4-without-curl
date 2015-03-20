<html>
 <body>
  <h2>Resultados</h2>
  <form action="atualiza.php" method="get">
    <input type="button" name="B1" value="B1">
    <input type="button" name="B2" value="B2">
    <input type="button" name="insert" value="insert">
    <input type="button" name="select" value="select">
    <input type="Submit" name="Submit1"/>

    <!-- <a href="http://www.google.com?act=google">Google</a>
    <a href="http://www.yahoo.com?act=yahoo">yahoo</a>
     -->
  </form>

  
 <?php
 require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

if(isset($_GET["Submit1"])){   
        echo "Submit1";
}
if(isset($_GET["B1"])){
        echo "B1";
} 
if(isset($_GET["B2"])){
        echo "B2"; 
}





 if($_GET){
    if(isset($_GET['insert'])){
        insert();
    }elseif(isset($_GET['select'])){
        select();
    }
 }

    function select()
    {
       echo "The select function is called.";
    }
    function insert()
    {
       echo "The insert function is called.";
    }

 
 
 
 

 $sql = "SELECT Time1, Time2, DataJogo from Jogos WHERE Resultado = ''";
 $retorno = (new minhaclasse())->usaDB("$sql");
 foreach($retorno as $row) {
 echo "<form action='' method='POST'>";
 echo "<div>"; 
 echo "<textarea name='Time1' rows='1' cols='20'>" . $row[0] . "</textarea>";
 echo "<textarea name='Time2' rows='1' cols='20'>" . $row[1] . "</textarea>";
 echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[2] . "</textarea>";
 echo "<input type='submit' value='submit2'>";
 echo "</div>"; 
 echo "</form>";
 }
 echo '<br>';
 

 if (isset($_POST['submit2'])) {
 
 echo $_POST['Time1'];
 echo $_POST['Time2'];
 echo $_POST['Data'];
  
 }
  
  
 ?>

  </body>
</html>

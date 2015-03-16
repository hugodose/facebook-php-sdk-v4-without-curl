<html>
 <body>
  <h2>Entries</h2>
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
  $appObj = new minhaclasse();
  $appArray = $appObj->selectDB();
  
  $appObj2 = new minhaclasse()->selectDB();
  
  
  ?>

  <h2>Input</h2>
  <form action="/sign" method="post">
    <div><textarea name="name" rows="3" cols="60"></textarea></div>
    <div><textarea name="content" rows="3" cols="60"></textarea></div>
    <div><input type="submit" value="Input"></div>
  </form>
  </body>
</html>

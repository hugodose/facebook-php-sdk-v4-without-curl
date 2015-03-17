<?php
  date_default_timezone_set('Europe/London');
  $date = date('d-m-Y H:i:s');
  echo $date;
  
  $my_datetime='2013-10-23 15:47:10';
  echo date('d-m-Y H:i:s BRT',strtotime("$my_datetime BRT"));

?>

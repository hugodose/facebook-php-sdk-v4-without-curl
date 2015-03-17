<?php
  date_default_timezone_set('Europe/London');
  $date = date('d-m-Y H:i:s');
  echo $date .' - Londres<br>';
  
  $my_datetime='2013-10-23 15:47:10';
  echo date('d-m-Y H:i:s',strtotime("$my_datetime")) .' - BRT to Londres<br>';;
  
  date_default_timezone_set('Brazil/East');
  echo date('d-m-Y H:i:s',strtotime("$date")) .' - Londres to BRT<br>';;

?>

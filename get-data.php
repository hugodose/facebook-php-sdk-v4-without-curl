<?php
$minhamatriz = array('oi1', 'oi2');
echo json_encode($minhamatriz);

echo "<table border='1'>
<tr>
<th>Heading1</th>
<th>Heading2</th>
</tr>";

  echo "<tr>";
  echo "<td>" . $minhamatriz[0] . "</td>";
  echo "<td>" . $minhamatriz[1] . "</td>";
  echo "</tr>";
echo "</table>";



?>

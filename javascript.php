<input type="button" id="meubotao" value="Clique aqui" onclick="Consulta();" />
<input type="text" id="meutexto" value="..." />
<div id="tabelaphp"></div>

echo "<form action='zzzzz.php' method='POST'>";
echo "<div>";
echo "<textarea name='Time1' rows='1' cols='20'>" . $row[0] . "</textarea>";
echo "<textarea name='Time2' rows='1' cols='20'>" . $row[1] . "</textarea>";
echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[2] . "</textarea>";
echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[3] . "</textarea>";
echo "<input type='submit' name='Input' value='Envia'>";
echo "</div>";
echo "</form>";


<script>
    //function reqListener () {
    //  console.log(this.responseText);
    //}
function Consulta () {
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function() {
        //This is where you handle what to do with the response.
        //The actual data is found on this.responseText
        document.getElementById('meutexto').value = this.responseText[0];
        document.getElementById('tabelaphp').innerHTML=xmlhttp.responseText;
        //alert(this.responseText); 
    };
    oReq.open("get", "get-data.php", true);
    //                               ^ Don't block the rest of the execution.
    //                                 Don't wait until the request finishes to 
    //                                 continue.
    oReq.send();
}
</script>

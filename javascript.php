<input type="button" id="meubotao" value="Clique aqui" onclick="Consulta();" />
<input type="text" id="meutexto" value="..." />
<script>
    //function reqListener () {
    //  console.log(this.responseText);
    //}
function Consulta () {
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function() {
        //This is where you handle what to do with the response.
        //The actual data is found on this.responseText
        document.getElementById('meutexto').value = this.responseText;
        //alert(this.responseText); 
    };
    oReq.open("get", "get-data.php", true);
    //                               ^ Don't block the rest of the execution.
    //                                 Don't wait until the request finishes to 
    //                                 continue.
    oReq.send();
}
</script>

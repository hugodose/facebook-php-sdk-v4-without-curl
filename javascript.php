<input type="button" id="meubotao" value="Clique aqui" onclick="Consulta();" />
<input type="text" id="meutexto" value="..." />
<div id="tabelaphp1"></div>
<div id="tabelaphp2"></div>
<div id="tabelaphp3"></div>
<div id="tabelaphp4"></div>



<script>
    //function reqListener () {
    //  console.log(this.responseText);
    //}
function Consulta () {
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function() {
        //This is where you handle what to do with the response.
        //The actual data is found on this.responseText
        document.getElementById('meutexto').value = this.responseText;  //retorna variavel
        document.getElementById('tabelaphp1').innerHTML=this.responseText; //retorna html
        document.getElementById('tabelaphp2').innerHTML=oReq.responseText; //retorna html
        //alert(this.responseText); 
    };
    //oReq.onreadystatechange=function(){
    //    if (oReq.readyState==4 && oReq.status==200) //ready
    //    {
    //        document.getElementById('tabelaphp3').innerHTML=this.responseText;
    //        document.getElementById('tabelaphp4').innerHTML=oReq.responseText;
    //    }
    //}
    oReq.open("get", "get-data.php", true);
    //                               ^ Don't block the rest of the execution.
    //                                 Don't wait until the request finishes to 
    //                                 continue.
    oReq.send();
}
</script>


<form id="my_form">
    Start date: <br/> <input name="idate" id="firstdate" type="text" /><br />
    End date: <br /> <input name="fdate" id="seconddate" type="text" /><br />
    <input id="submit_form" type="submit" value="Submit">
</form>
<div id="update_div"></div>

<script>
var submit_button = $('#submit_button');

submit_button.click(function() {

    var start_date = $('firstdate').val();
    var end_date = $('seconddate').val();

    var data = 'start_date=' + start_date + '&end_date=' + end_date;

    var update_div = $('#update_div');

    $.ajax({
        type: 'GET',
        url: 'proccess_form.php',
        data: data,   
        success:function(html){
           update_div.html(html);
        }
    });
});
</script>

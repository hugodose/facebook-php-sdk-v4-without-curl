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





            <title>Ajax Test</title>
            <script language="Javascript">
            function xmlhttpPost(strURL) {
                var xmlHttpReq = false;
                var self = this;
                // Mozilla/Safari
                if (window.XMLHttpRequest) {
                    self.xmlHttpReq = new XMLHttpRequest();
                }
                // IE
                else if (window.ActiveXObject) {
                    self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
                }
                self.xmlHttpReq.open('POST', strURL, true);
                self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                self.xmlHttpReq.onreadystatechange = function() {
                    if (self.xmlHttpReq.readyState == 4) {
                        updatepage(self.xmlHttpReq.responseText);
                    }
                }
                self.xmlHttpReq.send(getstring());
            }

            function getstring() {
                var form     = document.forms['Test'];
                var firstname = form.firstname.value;
                var secondname = form.secondname.value;
                squery = 'firstname=' + escape(firstname) + '&secondname=' + escape(secondname);  // NOTE: no '?' before querystring
                return squery;
            }

            function updatepage(str){
                document.getElementById("sentback").innerHTML = str;
            }
            </script>
            </head>
            <body>
            <form name="Test">

              This pages does a post without a page refresh. the update div will be filled in below the text entry area in DIV=sentback
                <p>First Name: <input name="firstname" type="text"> 
                <p>Second Name: <input name="secondname" type="text"> 
              <input value="Go" type="button" onclick='JavaScript:xmlhttpPost("proccess_form.php")'></p>
              <div id="sentback"></div>
            </form>


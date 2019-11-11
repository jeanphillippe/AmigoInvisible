<?

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

date_default_timezone_set('America/Argentina/Buenos_Aires');
$people = file_get_contents('people.json');
$peoplejson = stripslashes($people);
#echo $peoplejson;
$people = json_decode($people);
#print_r($people);
$results = file_get_contents('results.json');
$resultsjson = $results;
#echo $resultsjson;
$results = json_decode($results);
#print_r($results);
if ($_GET['audit']) {
	file_put_contents("auditoria.htm",$_SERVER['REMOTE_ADDR']." - ".$_GET['audit']."\r\n",FILE_APPEND);
}
if ($_GET['taked']) {
	#echo htmlentities($_GET['drawed']);
	// Search
	$pos = array_search(htmlentities($_GET['taked']), $people);
	// Remove from array
	#echo $results[$pos];
	unset($people[$pos]);
	$jsonData = json_encode(array_values($people));
	file_put_contents('people.json', $jsonData);
	die(htmlentities("  / ".$_GET['drawed'])." quitado de people.json");
}

if ($_GET['drawed']) {
	#echo htmlentities($_GET['drawed']);
	// Search
	$pos = array_search(htmlentities($_GET['drawed']), $results);
	// Remove from array
	#echo $results[$pos];
	unset($results[$pos]);
	$jsonData = json_encode(array_values($results));
	file_put_contents('results.json', $jsonData);
	die(htmlentities("  / ".$_GET['drawed'])." quitado de results.json");
}
?>
<!DOCTYPE html>
<html lang="es" >

<head>
  <meta charset="UTF-8">
  <title>Amigo Invisible</title>
  <meta name="viewport" content="width=device-width">
  
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>

  <div class="wrapper" style="margin-top: 20vh;">
<h1>Amigo Invisible</h1>
	<div class="select-wrap" id="peopleWrap">
		<select id="people"></select>
		<span class="arrow"></span>
	</div>
	<div><button id="choose">¿Quién me tocó?</button></div>
	<div id="result"></div>
	<div class="close"><span id="close"></span></div>
</div>
  
  





    <script>window.onload = function()
{
	drawList();
};

var nosorteados = <? echo $resultsjson;?> ;
var give = <? echo $peoplejson;?>;
var receive = nosorteados.concat();
var peopleWrap = document.getElementById('peopleWrap');
var people = document.getElementById('people');
var choose = document.getElementById('choose');
var result = document.getElementById('result');
var close = document.getElementById('close');

function drawList()
{
	people.innerHTML = '<option value="">Quién sos vos?</option>';
	for (var i = give.length - 1; i >= 0; i--) {
		var option = document.createElement('option');
		option.value = i;
		option.innerHTML = give[i];
		people.appendChild(option);
	}
}

function selectPerson(person) 
{
	var name = give[person];
	var nameIndex = receive.indexOf(name);
	
	if(nameIndex >= 0) 
	{
		receive.splice(nameIndex, 1);
	}
	var recipient = Math.floor((Math.random() * receive.length));
	var recipientName = receive[recipient];
	
	receive.splice(recipient, 1);
	give.splice(person, 1);

	if(nameIndex >= 0)
	{
		receive.push(name);

	}		
			const Http2 = new XMLHttpRequest();
           const url2='http://www.jeanphillippe.com/navidad/index.php?taked='+ name;
           Http2.open("GET", url2);
           Http2.send();

           const Http3 = new XMLHttpRequest();
           const url3='http://www.jeanphillippe.com/navidad/index.php?audit=<? echo date("d-m-Y H:i a");?> - ' + name + ", te toco  " + recipientName + "!<br/>";
           Http3.open("GET", url3);
           Http3.send();

		   const Http = new XMLHttpRequest();
           const url='http://www.jeanphillippe.com/navidad/index.php?drawed='+ recipientName;
           Http.open("GET", url);
           Http.send();

	result.innerHTML = "<h2>" + name + ", te tocó  " + recipientName + "!</h2>";
	close.innerHTML = "Este resultado fue guardado.";
	if(give.length > 0)
	{
		drawList();
	}
}

choose.onclick = function()
{
	if(people.value)
	{
		selectPerson(people.value);
	}
};

close.onclick = function()
{
	result.innerHTML = "";
	close.innerHTML = "";
  if(give.length == 0){
 peopleWrap.parentNode.removeChild(peopleWrap);
		choose.parentNode.removeChild(choose);
		result.innerHTML = "<h2>Eso es todo!</h2>";
		close.innerHTML = "";
	}
};</script>
</body>

</html>

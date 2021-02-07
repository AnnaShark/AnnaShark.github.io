<?php
// Inicialización del estado de sesion
session_start();
$err = "";
// Existen datos del usuario en el estado de sesion? 
if ( !isset($_SESSION['user'])) {
	header('Location: '.'login.html');
} 

?>

<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<link rel='icon' href='favicon.ico' type='image/x-icon'/ >
	<title>Anna Shark</title>
	
	<script src="external/jquery/jquery.js"></script>
	<script src="jquery-ui.js"></script>
	

	<style>

	body{
		font-family: "Trebuchet MS", sans-serif;
		text-align: center;
	}

  .field{
    width: 200px;
  }

	.ui-button{
		width: 300px;
		font-size: 90%;
	}


	.wrapper1{
    display: flex;
    justify-content: center;

	}

	table {
		border-collapse: collapse;
		margin: 25px 0;
		font-size: 0.9em;
		min-width: 400px;
		margin-left: auto;
  		margin-right: auto;	
    }
	
	thead tr {
		background-color: #222222;
		
		color: #eeeeee;;
		text-align: center;
	}

	th, td {
    	padding: 12px 15px;
	}

	tbody tr {
		border-bottom: 1px solid #dddddd;
		text-align: left;	
	}

	tbody tr:nth-of-type(even) {
    	background-color: #f3f3f3;
	}	
	a {
		color: grey;
	}

	footer{
		text-align: center;
		color: grey;
		font-size: 75%;
    margin: 100px;
	}

	</style>
<link href="jquery-ui.css" rel="stylesheet">	

<script>
$(function () {
  $.datepicker.setDefaults($.datepicker.regional["es"]);
  $("#datepicker").datepicker({
   dateFormat: 'yy-mm-dd'
  });
  });
</script>
  
<script>
$().ready( function() {
	$.getJSON('modeldiary.php', refrescar);
	$("#Form").on( "submit", function( event ) { 
		event.preventDefault();
		// Serializacion de datos
		var datos = $( this ).serialize();
		
		// Envio de datos serializados
		$.post('modeldiary.php', {'datos': datos}, refrescar, 'json'); 
		//console.log("here1")
		// Limpieza de campos
		$("input[name='date']").val('');
		$("input[name='tsh']").val('');
		$("input[name='dosis']").val('');
		$("input[name='weight']").val('');
		$("input[name='note']").val('');
		$("input[name='link']").val('');
		$('textarea').val('');
		
	}); 
});

$(window).on('load', function() {
	//console.log("here2")
	$(document).on('submit','#remForm',function(){
		console.log("here1")
		event.preventDefault();
		// Serializacion de datos
		var datos = $( this ).serialize();
		// Envio de datos serializados
		$.post('modeldiary.php', {'datos': datos}, refrescar, 'json'); 	
	});
	$.getJSON('modeldiary.php', refrescar);
});

function refrescar( entries ){
	console.log(entries)
	// Se ha devuelto una matriz?
	/*if ( !$.isArray(cuentas)) {
		alert(cuentas.error);
		return; 
	}*/
	// Eliminar filas existentes
	$('#tbl_entr tbody tr').remove(); 
	// Generacion de filas por cada usuario 
	$(entries).each(function( index, entry ) {
	$("#tbl_entr").find('tbody') 
		.append($('<tr>')
			.append($('<td>')
      			  .html(entry.DATE))
			.append($('<td>')
       			 .html(entry.TSH))
			.append($('<td>')
       			 .html(entry.DOSIS))
			.append($('<td>')
				.html(entry.WEIGHT))
     		.append($('<td style= "width:300px">')
				.html(entry.NOTE))
      		.append($('<td>')
        		.html("<a href="+entry.LINK+">See the document</a>"))
      		.append($('<td>')
        		.html("<form id=remForm method='post'> <input type='hidden' name='entry_id' value="+entry.ID+"><input class= 'ui-button ui-widget ui-corner-all' style= 'width:100px;font-size: 0.9em' type='submit' value='Remove'>"))
		); 
	});
}
</script>

</head>

<body>
<header>

<button onclick="document.location='index.html'" class="ui-button">Home</button>
<button onclick="document.location='prof.html'" class="ui-button">Professional info</button>
<button onclick="document.location='diary.php'" class="ui-button">Hypo tracker</button>
<button onclick="document.location='contact.html'" class="ui-button">Contacts</button>

<h1>Add analysis to the tracker</h1>
or <a href="logout.php">Logout</a>

</header>



<br />
<form id = Form method="post">
<label>Date of the analysis:</label><br />
<input class = "field" type="text" id="datepicker" name="date" required/><br /><br />
<label>The level of Thyroid stimulating hormone (TSH):</label><br />
<input class = "field" type="number" name="tsh"  step=".01" required ><br /><br />
<label>Weekly dosis of levothyroxine (eg. euthyrox) changed to:</label><br />
<input class = "field" type="number" name="dosis" required><br /><br />
<label>Current body weight (optional):</label><br />
<input class = "field" type="number" name="weight"> <br /><br />
<label>Observations on the energy, mood, appetite etc (optional):</label><br />
<textarea id="note" cols="70" rows ="10" name="note"></textarea><br /><br />
<label>Paste a link to a cloud storage with your analysis document (optional):</label><br />
<input class = "field" style= 'width:500px' type="text" name="link" ><br /><br />
<br />
<input class="ui-button ui-widget ui-corner-all" type="submit" value="Add your entry">
</form> 

<br />
<br />


<table class="table" id='tbl_entr'>
	<thead>
		<tr>
			<th>Date</th>
			<th>TSH</th>
      <th>Dosis</th>
      <th>Weight</th>
      <th>Observaitons</th>
	  <th>Link to analysis</th>
	  <th></th>
		</tr>
	</thead>
	<tbody>

	</tbody>
	</table>








</body>


<footer>
    <p>Created by Anna Shark 2020</p>
</footer>
</html>

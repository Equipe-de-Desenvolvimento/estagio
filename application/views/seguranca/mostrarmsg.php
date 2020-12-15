<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		
	</style>
</head>
<body>

<?php 
if (count($lista)>0) {
foreach ($lista as $value) {

    
if ( $value->empresa == "t") {
	$mensage_minha = "minha";
}else{
     
	$mensage_minha = "oposto";
}
	echo "<div class='bloco_msg' id=".$mensage_minha."  ><b class='windownew'>". wordwrap($value->mensagem,30, '<br />',true)."</b><b style='font-weight:normal;font-size:12px;float:right;'>".@date("H:i",strtotime($value->horario_mensagem))."</b></div>";	    
}

}

 ?>

</body>
</html>
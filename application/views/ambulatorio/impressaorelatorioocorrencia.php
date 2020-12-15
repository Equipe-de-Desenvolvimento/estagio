<?php 
//
//echo "<pre>";
//print_r($relatorio);
?>

<html>
    <head>
        <title>Relatório</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            h4,td{
                font-family: arial;
                
            }
        </style>
    </head>
    <body>
        <h4>Relatório Ocorrência</h4>
        <h4>Período: <?= $_POST['txtdata_inicio']; ?> até <?= $_POST['txtdata_fim'];?>  </h4>
        <h4>Status:
            <?php 
            if ($_POST['status'] == "1") {
              echo "Finalizada";  
            }elseif($_POST['status'] == "2"){
                echo "Não finalizada"; 
            }else{
                echo "Todas";
            }
            ?>
        </h4>
        <h4>Operador: 
            <?php if (count($operador) > 0) {
                echo $operador[0]->operador;
            }else{
                echo "Todos";
            }
?>
        </h4>
        
        
        
        <hr>
        
<?php if (count($relatorio) > 0) {?>
        <table border=1 cellspacing=0 cellpadding=2 >
            <tr>
                <td>Nome do Operador</td>
                 <td>Total de ocorrência</td>
            </tr>
<? 
    $total = 0;
    foreach($relatorio as $item){
        $total = $item->qtd + $total;
    ?>
            <tr>
                <td><?= $item->operador; ?></td>
                <td><?= $item->qtd; ?></td>
            </tr>
        
         
        
        
    <? }?>
            <tr>
                <td>Total</td>
                <td><?= $total; ?></td>
            </tr>
        </table>
        
<?php }else{
    
    echo "<h4>Nenhum registro encontrado</ħ4>";
}
?>
    </body>
</html>

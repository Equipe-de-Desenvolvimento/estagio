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
        <h4>Relatório Alteração Médico</h4>
        <h4>Período: <?= $_POST['txtdata_inicio']; ?> até <?= $_POST['txtdata_fim'];?>  </h4> 
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
            <thead>
                <tr>
                     <th>Nome do Operador</th>
                     <th>Data da Alteração</th>
                     <th>Paciente</th>
                     <th>Data Agenda</th>
                     <th>Procedimento</th>
                     <th>Convênio</th>
                </tr>
            </thead>
            <tbody>
<? 
    $total = 0;
    foreach($relatorio as $item){ 
    ?>
            <tr>
                <td><?= $item->nome; ?></td>
                <td><?= date('d/m/Y H:i:s',strtotime($item->data_cadastro)); ?></td>
                   <td><?= $item->paciente; ?></td>
                <td><?= date('d/m/Y',strtotime($item->data)); ?></td>
                <td><?= $item->procedimento; ?></td>
                <td><?= $item->convenio;  ?></td>
            </tr>
         
    <? }?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Total: <?= count($relatorio); ?></th>
                 
                </tr>
            </tfoot>
        </table>
        
<?php }else{
    
    echo "<h4>Nenhum registro encontrado</ħ4>";
}
?>
    </body>
</html>

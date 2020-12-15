 
<html>
    <head>
        <title>Relatório</title>
        <meta charset="utf-8">
         
        <style>
            td,th{
                font-family: arial;
            } 
             .zebra:nth-child(2n+2) {
                background: #ccc;
            }
            td{
                font-size: 12px;
            }
            
            .borda{
                border:1px solid black;
                 font-size: 12.2px;  
            }
            
        </style>
    </head>
    <body>
<?php 
  if (count($relatorio) > 0) {
?>
        <h4>Período: <?= $_POST['txtdata_inicio']; ?> até <?= $_POST['txtdata_fim'];?>  </h4>
        <h4>Empresa: <? if ($_POST['empresa'] != "0") {
echo @$empresa[0]->nome_empresa;
}ELSE{
    echo "TODAS";
}
?> 
        </h4>
<table border="0" width="100%">
    <thead>
        <tr>
            <th class="borda">Tipo</th>
            <th class="borda">Classe</th>
            <th class="borda">Data</th>
            <th class="borda">Valor</th>
            <th class="borda">Conta</th>
            <th class="borda">D. Exclusão</th>
            <th class="borda">Usuário Exclusão</th>
            <th class="borda">Observação</th>
        </tr>
    </thead>
    <tbody> 
<?php 
$aux = "";
foreach($relatorio as $item){
    if ($item->tipo == "MANTER_ENTRADA") {
         $lista = $this->caixa->dadosentrada($item->chave_primaria);   
    }elseif($item->tipo == "MANTER_SAIDA"){
         $lista = $this->caixa->dadossaida($item->chave_primaria); 
    }elseif($item->tipo == "MANTER_CONTAS_PAGAR"){
         $lista = $this->contaspagar->dadoscontaspagar($item->chave_primaria); 
    }elseif($item->tipo == "MANTER_CONTAS_RECEBER"){ 
         $lista = $this->contasreceber->dadoscontasreceber($item->chave_primaria); 
    } 
 ?>
        
  <?if ($item->tipo != $aux) {?>
      <tr style="text-align: left;" >
        <th colspan="6" > <?
           if ($item->tipo == "MANTER_ENTRADA") {
              echo "&nbsp; Manter Entrada";
           }elseif($item->tipo == "MANTER_SAIDA"){
               echo "&nbsp; Manter Saída";
           }elseif($item->tipo == "MANTER_CONTAS_PAGAR"){
               echo "&nbsp; Manter Contas a Pagar";
           }elseif($item->tipo == "MANTER_CONTAS_RECEBER"){ 
                  echo "&nbsp; Manter Contas a Receber"; 
           }
         ?> </th>
    </tr>
    <?
 } 
 $aux = $item->tipo;
    ?> 
    <tr class="zebra">
        <td><?= $lista[0]->tipo; ?></td>
        <td><?= $lista[0]->classe; ?></td>
        <td><?= date("d/m/Y",strtotime($lista[0]->data)); ?></td>
        <td>R$ <?= number_format($lista[0]->valor, 2, ',', '.') ?></td>
        <td><?= $lista[0]->conta; ?></td>
        <td><?= date("d/m/Y",strtotime($item->data_cadastro)); ?></td>
        <td><?= $item->op_exclusao; ?></td>      
        <td><?= $lista[0]->observacao; ?></td>
    </tr>  
<?
}
}else{
    echo "<h3>Nenhum resultado encontrado</h3>";
}
?>

    
    </tbody>
    
    
  </table>
    </body>
</html>

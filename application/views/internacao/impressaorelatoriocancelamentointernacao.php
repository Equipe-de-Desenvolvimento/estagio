 

<html>
    <head>
        <title>Relatório</title>
        <meta charset="utf-8">
        <style>
            tr:nth-child(2n+2) {
                background: #ccc;
                font-family: arial;
            }
            h4,th,td{
                font-family: arial;
            }
            
        </style>
    </head>
    <body>
          
        <?php  
        
        echo "<h4>Relatório Cancelamento Internação</h4>";
       echo "<h4>Período: ".$_POST['txtdata_inicio']." à ".$_POST['txtdata_fim']. "</h4>";
         
        if ($_POST['empresa'] != "TODAS") {
            echo "<h4>Empresa: ".$nome_empresa[0]->nome."</h4>";
        }else{
            echo "<h4>Empresa: TODAS<h4>";
        }
         
        if ($_POST['operador'] != "TODAS") {
            echo "<h4>Operador: ".$operador[0]->nome."</h4>";
        }else{
            echo "<h4>Operador: TODOS<h4>";
        } 
        
        if ($_POST['convenio'] != "TODAS") {
            echo "<h4>Convênio: ".$convenio[0]->nome."</h4>";
        }else{
            echo "<h4>Convênio: TODOS<h4>";
        }
 
         
        
        ?>
        
        
        <hr>
        <?php   if (count($relatorio) > 0) {?>
        <table   border=1 cellspacing=0 cellpadding=2 width="100%">
            <thead>
                <tr>
                      <th>Paciente</th>
                      <th>Unidade</th>
                      <th>Enfermaria</th>
                      <th>Leito</th>
                      <th>Data Internação</th>
                      <th>Data Exclusão</th>
                      <th>Operador Exclusão</th>
                </tr>
            </thead>
            <?php 
       
                        
                    
            foreach($relatorio as $item){
                ?> 
            <tr>
                <td><?= $item->paciente; ?></td>
                <td><?= $item->unidade; ?></td>
                <td><?= $item->enfermaria; ?></td>
                <td><?= $item->leito; ?></td>
                <td><?= date("d/m/Y", strtotime($item->data_internacao)); ?></td>
                <td><?= date("d/m/Y", strtotime($item->data_atualizacao)); ?></td>
                <td><?= $item->operador_exclusao; ?></td>
            </tr>
            <?
            }
           
            ?>
        </table>
            <?php  }else{
                echo "<h3>Não há resultados para esta consulta.</h3>";
            }?>
    </body>
</html>

<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        
        <style>
            #Txtenviar {
                display: none;
            }td{
                font-family: arial;
            }
         tr:nth-child(odd) {
            background: #ccc;
          }
           
         </style>
    </head>
    <body>
         <?php 
         $i=0;
        foreach($lista as $value){
            foreach(json_decode($value->arquivo_duplo) as $item){
               $resultado =  $this->exame->listarprocedimentoimportadoduplo($item);
               if (count($resultado) == 0) {
                   continue;
               }
                  $i++;            
             }
            }        
        ?> 
        
        <form  action="<?= base_url()?>ambulatorio/exame/adicionarprocedimentoimportadoduplo" method="post" >            
        <table border=1 cellspacing=0 cellpadding=2  width='100%'>
            <?php if ($i > 0) {                        
                    ?>
            <tr>
                <th>Médico</th>
                <th>Convênio</th>
                <th>Procedimento</th>
                <th>Duplo</th>
                <th><center>Ações</center></th>                
            </tr>  
        <?php
        foreach($lista as $value){
            foreach(json_decode($value->arquivo_duplo) as $item){
               $resultado =  $this->exame->listarprocedimentoimportadoduplo($item);
               if (count($resultado) == 0) {
                   continue;
               }
                 ?>
            <tr>
                <td><?= $resultado[0]->medico; ?></td>
                <td><?= $resultado[0]->convenio; ?></td>
                <td><?= $resultado[0]->procedimento; ?></td>
                <td>DUPLO</td>
                <td><center><input type="checkbox" id="procedimento" name="procedimento[]" value="<?= $resultado[0]->procedimento_importacao_producao_id_duplo; ?>"></center></td>
            </tr>
            <?                
             }
            }
        }else{
            echo "<h4>Nenhum procedimento encontrado</h4>";
        }
        ?>  </table>
            <input type="hidden" name="procedimento_importacao_arquivo_id" id="procedimento_importacao_arquivo_id" value="<?= $procedimento_importacao_arquivo_id; ?>">
            <input type="hidden" name="arquivo_id" value="<?=$lista[0]->procedimento_importacao_arquivo_id?>" />
            <button type="submit" id="Txtenviar" name="Txtenviar">ADICIONAR A PRODUÇÃO</button>
            </form>
    </body>
</html>

<script type="text/javascript">
    
    
checks = document.querySelectorAll('input[type="checkbox"]');

//adicionando evento de click em todos os checkboxs
checks.forEach( function(ck){
    ck.addEventListener("click", function(){    
        //pegando a quantidade de elementos checados
        let checked = document.querySelectorAll
        ('input[type="checkbox"]:checked').length;
        
        let botao = document.getElementById('Txtenviar');
        
        // se a quantidade de elementos checados for igual a 0, então esconde o botão
        if(checked == 0){
            botao.style.display = "none";
        } else {
            botao.style.display = "block";
        } 
    });
});



</script>
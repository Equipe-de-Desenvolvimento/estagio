<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Historico</title>
        <style>
            body{
                background-color: silver;
                font-family: arial;
            }
            #detalhes{
                font-size: 13px;
            }
        </style>
    </head>
    <body> 
       <table border=1 cellspacing=0 cellpadding=2  width="100%">
           <tr>
               <td>Operador</td>
               <td>Data / Hora</td>
                <td>Tipo de alteração</td>
           </tr>
            <?php   
            foreach($historico as $item){ 
             @$json = json_decode($item->texto_alteracao_json); 
            
            ?>
           <tr> 
                <td><?= $item->operador; ?></td>
                <td><?= date('d/m/Y H:i:s',strtotime($item->data_cadastro)); ?></td>
                <td id="detalhes"><? 
               if($item->tipo == "ATUALIZACAO"){
                     echo "Atualização";
                }elseif($item->tipo == "CADASTRO"){
                    echo "Aparelho cadastrado";
                }elseif($item->tipo == "TROCA"){
                     $aparelho = $this->exame->listaraparelho($json->aparelho); 
                     echo "Aparelho trocado para: <br>".$aparelho[0]->aparelho;
                }elseif($item->tipo == "DESCRICAO"){
                    echo "Descrião alterada para: <br> ".$json->descricao;
                } 
                ?></td>
           </tr>  
        <?php }?> 
        </table>
    </body>
</html>

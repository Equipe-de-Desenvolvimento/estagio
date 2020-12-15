<html>
    <head>
        <title>Cadastro</title>
        
    </head>
    <body>
        <? if (count($operadores_add) > 0) {?>
        <h3>Operadores cadastrados</h3>
          <table border=1 cellspacing=0 cellpadding=2 width="100%">
              <tr>
                  <td>Nome</td>
              </tr>
        
        <? foreach($operadores_add as $item){  ?>
            <tr>
                <td><?= $item->nome ?></td>
            </tr>
        <? } ?>  
         </table>
      <? }else{ ?>  
            <h2>Nenhum registro encontrado.</h2>
      <? } ?>     
    </body>
</html>

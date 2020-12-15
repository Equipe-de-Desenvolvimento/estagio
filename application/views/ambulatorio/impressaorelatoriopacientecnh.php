<html>
    <head>
        <title>Relatório </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            td,h2,h3{
                font-family: arial;
            }
        </style>
    </head>
    <body>
        <h2>Relatório Paciente CNH</h2> 
        <h3>Período: <?= date('d/m/Y',strtotime($txtdata_inicio)); ?> à <?= date('d/m/Y',strtotime($txtdata_fim)); ?></h3>
        <h3>Empresa: 
            <?php 
            if(count($empresa) > 0){
                echo  $empresa[0]->nome;
            }else{
                echo "TODOS";
            }
            ?>
        </h3>
        <table border="1"  cellspacing=0 cellpadding=2>
            <thead>
                <tr>
                    <th>Data De Nascimento</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone / Telefone 2 / Whatsapp</th>
                    <th>Número da CNH</th>
                    <th>Vencimento CNH </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                  $email = '';
                foreach($relatorio as $item){
                      if (isset($item->cns) && $item->cns != '') {
                                $email = $email . $item->cns . ', ';
                       }
                    ?>
                    <tr>
                        <td><?= date('d/m/Y',strtotime($item->nascimento)); ?></td>
                        <td><?= $item->nome; ?></td>
                        <td><?= $item->cns; ?></td>
                        <td><?= $item->telefone." / ".$item->celular." / ".$item->whatsapp; ?></td>
                        <td><?= $item->cnh ; ?></td>
                        <td><?= date('d/m/Y',strtotime($item->vencimento_cnh)); ?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
   <br>
        <br>
    <?
    if ($mala_direta == true) {
        ?>

        <h3 style="display: inline">Mala Direta</h3> <span><button class="btn">Copiar Texto</button></span>
        <div id="mala_direta">
            <?
            echo "<p>" . $email . "</p>";
            ?>
        </div>
    <? } ?>
    </body>
</html>

<style>
    #mala_direta{
        width: 600pt;
        border: 1px solid black;
        background-color: #ecf0f1;
        word-wrap: break-word;
        max-height: 200pt;
        overflow-y: auto;
    }
</style>
    <script type="text/javascript" src="<?= base_url() ?>js/clipboard/dist/clipboard.js" ></script>
    <script>
    var clipboard = new Clipboard('.btn', {
        text: function() {
            return document.getElementById('mala_direta').textContent;
        }
    });

    clipboard.on('success', function(e) {
        alert('Copiado Com Sucesso!');
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
    </script>


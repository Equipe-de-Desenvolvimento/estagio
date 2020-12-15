<meta charset='UTF-8'>
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Log Guia <br> <?=$guia[0]->guia_id?></h3>
        <div>
        <?
        foreach($guia as $item){
        ?>
            <table border='1'>

            <tr>
            <th>Operador Cadastro</th>
            <td><?=$item->operador_cadastro?></td>
            </tr>

            <tr>
            <th>Data Cadastro</th>
            <td><?=substr($item->data_cadastro, 8, 2) . '/' . substr($item->data_cadastro, 5, 2) . '/' . substr($item->data_cadastro, 0, 4) . ' ' . substr($item->data_cadastro, 10, 9); ?></td>
            </tr>

            <tr>
            <th>Operador Alteração</th>
            <td><?=$item->operador_atualizacao?></td>
            </tr>

            <tr>
            <th>Data alteração</th>
            <td><?=substr($item->data_atualizacao, 8, 2) . '/' . substr($item->data_atualizacao, 5, 2) . '/' . substr($item->data_atualizacao, 0, 4) . ' ' . substr($item->data_atualizacao, 10, 9); ?></td>
            </tr>

            <? if($item->proc_antigo == ''){

                }
                else {?>

            <tr>
            <th>Procedimento Antigo</th>
            <td><?=$item->proc_antigo?></td>
            </tr>

            <tr>
            <th>Preço Antigo</th>
            <td><?=$item->preco_antigo?></td>
            </tr>

            <?
                }
            ?>



            </table>

            <br><br>

        <?
         }
        ?>
        </div>
    </div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
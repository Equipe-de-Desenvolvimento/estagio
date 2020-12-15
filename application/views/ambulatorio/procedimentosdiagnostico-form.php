<meta charset='UTF-8'>
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Procedimentos</h3>
        <div>
        <?

        ?>  
            <form name="form_faturar" id="form_faturar" action="<?= base_url() ?>ambulatorio/exame/gravarfaturadoconveniocirurgia/<?=@$cirurgico[0]->solicitacao_cirurgia_id?>" method="post">
                    <table border='1'>
                    <tr>
                    <th>Nº</th>
                    <th>Nome</th>
                    <th>Ação</th>
                    </tr>
                    <?
                    $contador = 0;
                    foreach($procedimentos as $item){
                    $contador++;
                    ?>
                    <tr>
                    <td align='center'><?=$contador?></td>
                    <td><?=$item->nome?></td>
                    <td align='center'><a href='<?= base_url() ?>ambulatorio/exame/excluiprocedimentodiagnostico/<?=$item->procedimento_tuss_id?>/<?=$diagnostico_id?>'><img src="<?= base_url() ?>img/form-ic-error.png"></a></td>
                    </tr>
                    <?
                    }
                    ?>
                    </table>
        </form>

        <fieldset>
                <legend>Adicionar Procedimentos</legend>
                    <form name="procedimentos_form" id="procedimentos_form" action="<?= base_url() ?>ambulatorio/exame/adicionarprocedimentodiagnostico/<?=$diagnostico_id?>" method="POST">
                <label>Procedimentos</label>
                <select name="procedimentos[]" id="procedimentos" class="chosen-select size3" multiple data-placeholder="Selecione" required="">
                            <!-- <option value="0">TODOS</option> -->
                            <? foreach ($listar_procedimentos as $value) : ?>
                                <option value="<?= $value->procedimento_tuss_id; ?>" 
                        ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    <input type="submit" value="Adicionar">
                    </form>
        </fieldset>
        </div>

    </div> <!-- Final da DIV content -->
</body>
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script> 

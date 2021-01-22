<div class="panel-body"> <!-- Inicio da DIV content -->

    <div class="alert alert-primary"><b>Cadastro Forma de Pagamento</b></div>
    <form name="form_formapagamento_parcela" id="form_formapagamento_parcela" action="<?= base_url() ?>cadastros/formapagamento/gravarparcelas" method="post">
        
        <fieldset>

            <div class="row">
                <div class="col-lg-3">
                    <label>Taxa: </label>
                    <input type="text" alt="decimal" name="taxa" class="form-control" id="taxa">
                </div>

                <div class="col-lg-1">
                    <label>Inicio:</label>
                    <select name="parcela_inicio" class="form-control" id="parcela_inicio" required>
                        <? 
                        $parcelaInicialDisponivel = $ultima_parcela+1;
                        if ($ultima_parcela < $maximo) { 
                            
                            ?>
                            <option value="<?= $parcelaInicialDisponivel ?>"> <?= $parcelaInicialDisponivel ?> </option>
                            <?
                            
                            ?>
                        <? } else { ?>
                                <option value=""></option>
                        <? } ?>
                    </select>
                </div>


                <div class="col-lg-1">
                    <label>Fim:</label>
                    <select name="parcela_fim" class="form-control" id="parcela_fim" required>
                                <?
                                if ($ultima_parcela < $maximo) {
                                    for ($i = $parcelaInicialDisponivel; $i <= $formapagamento[0]->parcelas; $i++) { ?>
                                        <option value="<?= $i ?>"> <?= $i ?></option>
                                    <? } 
                                }?>
                    </select>
                </div>
            </div>
            <br>
            <input type="hidden" name="formapagamento_id" value="<?= $formapagamento_id ?>">

            <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>

        </fieldset>
    </form>

    <div style="display: block; width: 100%;">
    <br>
        <? if (count($faixas_parcelas) > 0) { ?>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="tabela_header">Taxa</th>
                        <th class="tabela_header">Inicio</th>
                        <th class="tabela_header">Fim</th>
                        <th class="tabela_header"><center>Deletar</center></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $estilo_linha = "tabela_content01";
                    foreach ($faixas_parcelas as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        ?>

                        <tr>
                            <td class="<?php echo $estilo_linha; ?>">Taxa: <?= $item->taxa_juros ?></td>
                            <td class="<?php echo $estilo_linha; ?>">Parcela inicio: <?= $item->parcelas_inicio ?></td>
                            <td class="<?php echo $estilo_linha; ?>">Parcela fim: <?= $item->parcelas_fim ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><center><a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/formapagamento/excluirparcela/<?= $item->formapagamento_pacela_juros_id ?>/<?= $item->forma_pagamento_id ?>">Excluir</a></center></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        <? } ?> 
    </div>

</div> <!-- Final da DIV content -->
<style>
    .taxas{
        width: 100px;
    }
    .taxas .esquerda{
        width: 130px;
    }
    .taxas-feitas{
        width: 80%;
    }
</style>

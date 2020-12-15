<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <h3 class="h3_title">Outras Despesas</h3>
    <form name="form_prescricao" id="form_prescricao" action="<?= base_url() ?>internacao/internacao/gravaroutrasdespesas/<?= $internacao_id ?>" method="post">
        <fieldset>
            <legend>Taxas / Material</legend>
            <div>
                <label>Procedimentos</label>
                <select name="procedimentoID" id="procedimentoID" class="size2" data-placeholder="Selecione" tabindex="1">
                                        <option value="">Selecione</option>
                                        <? foreach($procedimentos as $procedimento){
                                           echo "<option data-valor='".$procedimento->valortotal."' data-grupo='".$procedimento->grupo."' value='".$procedimento->procedimento_convenio_id."'>".$procedimento->nome."</option>"; 
                                        }?>
                </select>
                <input type="hidden" name="grupo" id="grupo">
            </div>

            <div>
            <label>Qtde</label>
            <input type='text' name="qtde" value='1' class="texto00" required="">
            </div>

            <div>
            <label>Valor U.</label>
            <input type='text' name="valorunitario" id="valorunitario" class="texto02" readonly="">
            </div>

            <div>
            <label>Unidade Medida (Xml)</label>
            <input type='text' name="unidade_medida" class="texto02" value='1'>
            </div>

            <div style="display: block; width: 100%; margin-top: 5pt;">
            <br>
                <button type="submit" name="btnEnviar">Adicionar Taxa</button>
                <button type="reset" name="btnLimpar">Limpar</button>
            </div>

        </fieldset>

        <table>
        <thead>
            <tr>
                <th class="tabela_header">Taxas</th>
                <th class="tabela_header">Valor U.</th>
                <th class="tabela_header">Qtde</th>
                <th class="tabela_header">Valor Total</th>
                <th class="tabela_header">Unidade Medica</th>
                <th class="tabela_header" style="text-align: left;" colspan="1">Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach($despesas as $taxas){ ?>
            <tr>
            <td style="color:blue" class="tabela_content02"><?=$taxas->taxa?></td>
                <td class="tabela_content02"><?=$taxas->valor_u?></td>
                <td class="tabela_content02"><?=$taxas->quantidade?></td>
                <td class="tabela_content02"><?=$taxas->valor_total?></td>
                <td class="tabela_content02"><?=$taxas->unidade_medida?></td>

                <td class="tabela_content02" colspan="1">
                <div class="bt_link">
                                <a href="<?= base_url() ?>internacao/internacao/cancelaroutradespesa/<?= $taxas->internacao_outras_despesas_id ?>/<?=$internacao_id?>">
                                    <b>Cancelar</b>
                                </a>
                            </div> 
                </td>
            </tr>
        <? } ?>
        </tbody>
        </table>

    </form>


    <style>
        .bold{
            font-weight: bolder;
        }
        .grey{
            background-color: grey;
        }
        tr{
            /* vertical-align: top; */
        }
    </style>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>

<script>
   jQuery(document).ready(function () {
      jQuery('#procedimentoID').change(function() {
          jQuery('#valorunitario').val((jQuery(this).find(':selected').data('valor')));
          jQuery('#grupo').val((jQuery(this).find(':selected').data('grupo')));
      });
  });
</script>

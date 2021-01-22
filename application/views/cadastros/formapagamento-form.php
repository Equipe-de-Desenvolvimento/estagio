<?
$procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
?>
<div class="panel-body"> <!-- Inicio da DIV content -->

        <div class="alert alert-primary"><b>Cadastro Forma de Pagamento</b></div>
            
            <form name="form_formapagamento" id="form_formapagamento" action="<?= base_url() ?>cadastros/formapagamento/gravar" method="post">
                
                <div class="row">
                    <div class="col-lg-3">
                        <label>Nome</label>
                        <input type="hidden" name="txtcadastrosformapagamentoid" class="texto10" value="<?= @$obj->_forma_pagamento_id; ?>" />
                        <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_nome; ?>" required/>
                    </div>


                    <? if ($procedimento_multiempresa == 'f') { ?>
                    <div class="col-lg-2">
                        <label>Ajuste</label>
                        <input type="text" name="ajuste" class="form-control" id="ajuste" value="<?= @$obj->_ajuste; ?>" />%
                    </div>
                    <? } ?>

                    <div class="col-lg-2">
                        <label>Dia de Recebimento</label>
                        <input type="text" name="diareceber" class="form-control" id="diareceber" value="<?= @$obj->_dia_receber; ?>"/>
                    </div>

                    <div class="col-lg-2">
                        <label>Tempo de Recebimento</label>
                        <input title="Aqui é digitado o tempo que leva desde o momento do pagamento até o recebimento do dinheiro em si. (Esse campo anula o Dia de recebimento)" type="text" name="temporeceber" class="form-control" id="temporeceber" value= "<?= @$obj->_tempo_receber; ?>" />
                        <input type="checkbox"  name="arrendondamento" id="arrendondamento" <? if (@$obj->_fixar == 't') { ?>checked <? } ?>  />Fixar
                    </div>

                    <div class="col-lg-2">
                        <label>N° Maximo de Parcelas</label>
                        <input type="text" name="parcelas" class="form-control" id="parcelas" value= "<?= @$obj->_parcelas; ?>"/>
                    </div>

                    <div class="col-lg-2">
                        <label>Valor Mínimo da Parcela</label>
                        <input type="text" name="parcela_minima" class="form-control" alt="decimal" id="parcela_minima" value= "<?= @$obj->_parcela_minima; ?>" />
                    </div>

                    <div class="col-lg-2">
                        <label>Credor/Devedor</label>
                        <select name="credor_devedor" id="credor_devedor" class="form-control">
                            <option value="">SELECIONE</option>
                            <? foreach ($credor_devedor as $value) { ?>
                                <option value="<?= $value->financeiro_credor_devedor_id ?>" <?
                                if (@$obj->_credor_devedor == $value->financeiro_credor_devedor_id):echo 'selected';
                                endif;
                                ?>><?= $value->razao_social ?></option>
                                    <? } ?>                            
                        </select>
                    </div>


                    <div class="col-lg-3">
                        <label>Forma de Pagamento Cartão</label>
                        <input type="checkbox" name="cartao" id="cartao" <? if (@$obj->_cartao == 't') { ?>checked <? } ?>  />
                    </div>
                    
                    <div class="col-lg-3">
                        <label>Forma de Pagamento TCD</label>
                        <input type="checkbox" name="tcd" id="tcd" <? if (@$obj->_tcd == 't') { ?>checked <? } ?>  />
                    </div>
                </div>

                <hr/>
                <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
</div> <!-- Final da DIV content -->

<script type="text/javascript">
    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function () {
        $("#accordion").accordion();
    });

    if ($('#cartao').is(":checked")) {

        $("#credor_devedor").prop('required', true);
        $("#parcelas").prop('required', true);

    } else {

        $("#credor_devedor").prop('required', false);
        $("#parcelas").prop('required', false);
    }

    $('#cartao').change(function () {
        if ($(this).is(":checked")) {

            $("#credor_devedor").prop('required', true);
            $("#parcelas").prop('required', true);

        } else {

            $("#credor_devedor").prop('required', false);
            $("#parcelas").prop('required', false);
        }
    });


    $(document).ready(function () {
        jQuery('#form_formapagamento').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                },
                conta: {
                    required: true

                },
                credor_devedor: {
                    required: true
                },
                parcelas: {
                    required: true
                }

            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                },
                conta: {
                    required: "*"

                },
                credor_devedor: {
                    required: "*"
                },
                parcelas: {
                    required: "*"
                }
            }
        });
    });

</script>

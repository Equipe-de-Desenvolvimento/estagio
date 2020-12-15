<div class="content"> <!-- Inicio da DIV content --> 
    <div id="accordion">
        <h3 class="singular"><a href="#">Exclusão cirurgia</a></h3>
        <div>
            <form name="form_exameespera" id="form_exameespera" action="<?= base_url() ?>centrocirurgico/centrocirurgico/excluirsolicitacaocirurgia/<?= $solicitacao_id; ?>" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Motivo</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="solicitacao_id" value="<?=  $solicitacao_id; ?>" /> 
                   
                        <select name="txtmotivo" id="txtmotivo" class="size4" required="true">
                            <option value="">SELECIONE</option>
                            <? foreach ($motivos as $item) : ?>
                                <option value="<?= $item->ambulatorio_cancelamento_id; ?>"><?= $item->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <dt>
                        <label>Observacao</label>
                    </dt>
                    <dd>
                        <textarea id="observacaocancelamento" name="observacaocancelamento" cols="88" rows="3" required="true"></textarea>
                    </dd>
                </dl> 
                <br>
                <br>
                <br>
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $("#txtprocedimentolabel").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtpacientelabel").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtpacientelabel").val(ui.item.value);
                $("#txtpacienteid").val(ui.item.id);
                return false;
            }
        });
    });

    $(document).ready(function () {
        jQuery('#form_exameespera').validate({
            rules: {
                txtsalas: {
                    required: true
                },
                txttecnico: {
                    required: true
                }
            },
            messages: {
                txtsalas: {
                    required: "*"
                },
                txttecnico: {
                    required: "*"
                }
            }
        });
    });

</script>
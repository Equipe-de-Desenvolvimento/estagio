<body bgcolor="#C0C0C0">
<meta charset= utf-8>
<div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Vizualizar Observacao</h3>
        <div>
            <form name="form_horariostipo" id="form_horariostipo" action="<?= base_url() ?>ambulatorio/exame/observacaogravar/<?= $agenda_exame_id; ?>" method="post">
                <fieldset>
                    
                <dl class="dl_desconto_lista">
                <dt>
                        <label>NOME</label>
                        </dt>
                        <dd>
                            <input type="text" name="txtentregue" value="<?= $observacao[0]->entregue; ?>" style="width: 400px;" />
                        </dd>
                        <dt>
                        <label>TELEFONE</label>
                        </dt>
                        <dd>
                            <input name="telefone" id="telefone" size="20" maxlength="14" value="<?= $observacao[0]->entregue_telefone; ?>" onkeypress="mascara(this)" type="text"> 
                        </dd>
                        <dt>
                        <label>Observacao</label>
                        </dt>
                        <dd>
                            <textarea id="observacaocancelamento" name="observacaocancelamento" cols="50" rows="3" ><?= $observacao[0]->entregue_observacao; ?></textarea>
                        </dd>

                     
                </dl>    

            </form>
            </fieldset>
            <br><br>

            <table border='1'>
            <tr>
            <th colspan="2">Operador Entrega</th>
            </tr>
            <tr>
            <th>Operador</th>
            <th>Data / Hora</th>
            </tr>
            <tr>
            <?
            $data = date("d/m/Y H:m:s", strtotime(str_replace('-', '/', $observacao[0]->data_entregue)));
            ?>
            <td><?=$observacao[0]->operador;?></td>
            <td><?=$data?></td>
            </tr>
            </table>
        </div>
</div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(document).ready(function(){
        jQuery('#form_horariostipo').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                },
                txtTipo: {
                    required: true
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                },
                txtTipo: {
                    required: "*"
                }
            }
        });
    });

</script>
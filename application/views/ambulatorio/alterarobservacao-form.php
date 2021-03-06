<body bgcolor="#C0C0C0">
<meta charset="UTF-8">
<div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Alterar Observacao</h3>
        <div>
            <form name="form_horariostipo" id="form_horariostipo" action="<?= base_url() ?>ambulatorio/exame/observacaogravar/<?= $agenda_exame_id; ?>" method="post">
                <fieldset>
                    
                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Operador Observação</label>
                    </dt>
                        <input type="text" name="txtoperador"  class="texto07" value="<?= @$observacao[0]->operador; ?>" readonly>

                     
                </dl> 
                    
                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Observacao</label>
                    </dt>
                        <textarea type="text" name="txtobservacao" cols="55" class="texto12"><?= @$observacao[0]->observacoes; ?></textarea>

                     
                </dl>    

                <hr/>
                <button type="submit" name="btnEnviar">OK</button>

                <?if(@$observacao[0]->faltou_manual == 't'){?>
                    <button type="submit" disabled name="btnFaltou">Faltou</button>
                <?}elseif(@$observacao[0]->paciente_id == null){?>
                    
                <?}else{?>
                    <button type="submit" name="btnFaltou">Faltou</button>
                <?}?>
            </form>
            </fieldset>
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
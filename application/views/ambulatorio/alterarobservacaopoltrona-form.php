 <body bgcolor="#C0C0C0">
<meta charset="UTF-8">
<div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Alterar Observacao</h3>
        <div>
            <form name="form_horariostipo" id="form_horariostipo" action="<?= base_url() ?>ambulatorio/guia/observacaogravar/<?= $hora_agendamento_id; ?>" method="post">
                <fieldset>
         <dl class="dl_desconto_lista">
                    <dt>
                    <label>Observacao</label>
                    </dt>
                        <textarea type="text" name="txtobservacao" cols="55" class="texto12"><?= @$observacao[0]->observacao; ?></textarea>
                     
                </dl>    

                <hr/>
                <button type="submit" name="btnEnviar">OK</button>  
                </fieldset>
            </form>
         
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
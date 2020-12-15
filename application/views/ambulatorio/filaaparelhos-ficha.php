<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de aparelho</a></h3>
        <div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>ambulatorio/exame/gravaraparelho" method="post">
                  
                <dl class="dl_desconto_lista">                     
                    <dt>
                      <label>Nome do aparelho</label>
                    </dt>
                    <dd> 
                        <input type="text" name="txtAparelho" id="txtAparelho" class="texto10" value="<?= @$obj[0]->aparelho; ?>" required/>
                        <input type="hidden" name="fila_aparelhos_id" id="fila_aparelhos_id" class="texto10" value="<?= @$obj[0]->fila_aparelhos_id; ?>"  />
                    </dd>  
                    <dt>
                     <label>Número de série</label>
                    </dt>
                    <dd> 
                       <input type="text" name="txtNumserie" id="txtNumserie" class="texto10" value="<?= @$obj[0]->num_serie; ?>"  />
                    </dd>
                </dl>    
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button> 
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

 
 
<script type="text/javascript">
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ambulatorio/exame/listarfilaaparelho');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

 
 $(function () {
    $("#txtNome").autocomplete({
        source: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
        minLength: 3,
        focus: function (event, ui) {
            $("#txtNome").val(ui.item.label);
            return false;
        },
        select: function (event, ui) {
            $("#txtNome").val(ui.item.value);
            $("#paciente_id").val(ui.item.id);
            return false;
        }
    });
});
</script>


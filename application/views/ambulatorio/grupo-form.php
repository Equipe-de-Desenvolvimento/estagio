<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Grupos</a></h3>
        <div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>ambulatorio/grupos/gravar" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="txtgrupoid" class="texto10" value="<?= @$obj->_ambulatorio_grupo_id; ?>" />
                        <input type="text" name="txtNome" class="texto10r" value="<?= @$obj->_nome; ?>" />
                    </dd>
                </dl>
                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Tipo</label>
                    </dt>
                    <dd>
                        <select name="txttipo" id="txttipo" class="size4">
                            <option value="CONSULTA" <? if (@$obj->_tipo == "CONSULTA"):echo 'selected'; endif;?>>CONSULTA</option>
                            <option value="EXAME" <? if (@$obj->_tipo == "EXAME"):echo 'selected'; endif;?>>EXAME</option>
                            <option value="ESPECIALIDADE" <? if (@$obj->_tipo == "ESPECIALIDADE"):echo 'selected'; endif;?>>ESPECIALIDADE</option>
                            <option value="MATERIAL" <? if (@$obj->_tipo == "MATERIAL"):echo 'selected'; endif;?>>MATERIAL</option>
                            <option value="MEDICAMENTO" <? if (@$obj->_tipo == "MEDICAMENTO"):echo 'selected'; endif;?>>MEDICAMENTO</option>
                            <option value="CIRURGICO" <? if (@$obj->_tipo == "CIRURGICO"):echo 'selected'; endif;?>>CIRURGICO</option>
                        </select>
                    </dd>
                </dl>     
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
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ambulatorio/grupos');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });


    $(document).ready(function(){
        jQuery('#form_sala').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "2 Caracteres no Mínimo"
                }
            }
        });
    });

</script>
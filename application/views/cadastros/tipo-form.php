<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Tipo Entrada/saida</a></h3>
        <div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>cadastros/tipo/gravar" method="post">
                <?
                
                    $empresa_id = $this->session->userdata('empresa_id');
                    $this->db->select('ep.financ_4n');
                    $this->db->from('tb_empresa_permissoes ep');        
                    $this->db->where('ep.empresa_id', $empresa_id);
                    $retorno = $this->db->get()->result();
                    
                ?>

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="txtcadastrostipoid" class="texto10" value="<?= @$obj->_tipo_entradas_saida_id; ?>" />
                        <input type="text" name="txtNome" class="texto10" value="<?= @$obj->_descricao; ?>" />
                    </dd>
                    <?if($retorno[0]->financ_4n == 't'){?>
<!--                    <dt>
                    <label>Nível 1</label>
                    </dt>
                    <dd>                        
                        <select name="txtnivel1_id" id="txtnivel1_id" class="size4" required>
                            <? foreach ($nivel1 as $value) : ?>
                                <option value="<?= $value->nivel1_id; ?>"<? if (@$obj->_nivel1_id == $value->nivel1_id):echo 'selected';
                    endif;
                        ?>><?= $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>-->
                    <dt>
                    <label>Nível 2</label>
                    </dt>
                    <dd>                        
                        <select name="txtnivel2_id" id="txtnivel2_id" class="size4" required>
                            <? foreach ($nivel2 as $value) : ?>
                                <option value="<?= $value->nivel2_id; ?>"<? if (@$obj->_nivel2_id == $value->nivel2_id):echo 'selected';
                    endif;
                        ?>><?= $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <? } ?>
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
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });


    $(document).ready(function(){
        jQuery('#form_sala').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>
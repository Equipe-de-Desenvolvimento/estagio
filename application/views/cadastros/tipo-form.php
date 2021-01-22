<div class="panel-body"> <!-- Inicio da DIV content -->

        <div class="alert alert-primary"><b>Cadastro de Tipo Entrada/saida</b></div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>cadastros/tipo/gravar" method="post">
                <?
                
                    $empresa_id = $this->session->userdata('empresa_id');
                    $this->db->select('ep.financ_4n');
                    $this->db->from('tb_empresa_permissoes ep');        
                    $this->db->where('ep.empresa_id', $empresa_id);
                    $retorno = $this->db->get()->result();
                    
                ?>

                <dl class="dl_desconto_lista">
                    <div class="row">
                        <div class="col-lg-4">
                            <label>Nome</label>
                            <input type="hidden" name="txtcadastrostipoid" class="form-control" value="<?= @$obj->_tipo_entradas_saida_id; ?>" />
                            <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_descricao; ?>" />
                        </div>
                    </div>
                

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
                <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
                <button class="btn btn-outline-default btn-round btn-sm" type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
               
            </form>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>cadastros/tipo');
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
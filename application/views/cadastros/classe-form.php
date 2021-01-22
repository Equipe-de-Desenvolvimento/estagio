<div class="panel-body"> <!-- Inicio da DIV content -->
      <div class="alert alert-primary"><b>Cadastro de Classe</b></div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>cadastros/classe/gravar" method="post">
                
                <?
                
                    $empresa_id = $this->session->userdata('empresa_id');
                    $this->db->select('ep.financ_4n');
                    $this->db->from('tb_empresa_permissoes ep');        
                    $this->db->where('ep.empresa_id', $empresa_id);
                    $retorno = $this->db->get()->result();
                    
                ?>

                <div class="row">
                    <div class="col-lg-4">
                        <label>Nome</label>
                        <input type="hidden" name="txtfinanceiroclasseid" class="texto10" value="<?= @$obj->_financeiro_classe_id; ?>" />
                        <input type="text" name="txtNome" class="form-control" value="<?= @$obj->_descricao; ?>" required/>
                    </div>

                    <div class="col-lg-4">
                        <label>Tipo</label>
                        <select name="txttipo_id" id="txttipo_id" class="form-control" required>
                            <? foreach ($tipo as $value) : ?>
                                <option value="<?= $value->tipo_id; ?>"<? if (@$obj->_tipo_id == $value->tipo_id):echo 'selected';
                        endif;
                        ?>><?= $value->descricao; ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </div>
                    

                    
  
                <hr/>
                <button class="btn btn-outline-default btn-round btn-sm"  type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-default btn-round btn-sm"  type="reset" name="btnLimpar">Limpar</button>
                <button class="btn btn-outline-default btn-round btn-sm"  type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>cadastros/classe');
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
                    minlength: "!"
                }
            }
        });
    });

</script>


<div id="page-wrapper"> <!-- Inicio da DIV content -->
<!--    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ponto/horariostipo">
            Voltar
        </a>
    </div>-->
    <div class="panel panel-default">
        <div class="alert alert-info">Cadastro de Horario</div>
        <div class="panel-body">
             <form name="form_horariostipo" id="form_horariostipo" action="<?= base_url() ?>ambulatorio/agenda/gravar" method="post">
            <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label>Nome</label>
                        
                        
                            <input type="hidden" name="txthorariostipoID" value="<?= @$obj->_agenda_id; ?>" />
                            <input type="text" name="txtNome" class="form-control bestupper" value="<?= @$obj->_nome; ?>" />
                        </div>


                    </div>    

                    
            </div>
                 <button class="btn btn-outline btn-success btn-sm" type="submit" name="btnEnviar"><i class="fa fa-floppy-o fa-fw" area-hiden="trues"></i> Enviar</button>
                    <button  class="btn btn-outline btn-danger btn-sm" type="reset" name="btnLimpar">Limpar</button>
                    <!--<button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>-->
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

    $(document).ready(function () {
        jQuery('#form_horariostipo').validate({
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
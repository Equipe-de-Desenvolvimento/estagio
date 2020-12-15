<style>
.statusbardeira{
    /* background-color:#000; */
    /* color:#fff; */
    display:inline-block;
    padding: 5px 10px;
    padding-left:15px;
    padding-right:15px;
    text-align:center;
    border-radius:2px;
    }

/* .w3-badge{border-radius:2px} */

</style>
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
    <h3 class="singular"><a href="#">Manter Bardeiras de Status</a></h3>
        <div>
            <form name="form_procedimentohonorario" id="form_procedimentohonorario" action="<?= base_url() ?>ambulatorio/exame/gravarbardeirastatusconvenio" method="post">

                <dl class="dl_desconto_lista">

                        <dt>
                            <label>Bardeira Status</label>
                        </dt>
                        <dd>

                            <input type="text" name="texto_bardeira" value="<?=@$bardeira[0]->nome?>" readonly="">
                            <span class="statusbardeira" style="background-color:<?=$bardeira[0]->cor?>; color:<?=$bardeira[0]->cor?>;">------</span>
                            <input type="hidden" name="bardeira_id" id="medico" value="<?=@$bardeira_id?>">
                        </dd>       
                        
                    <?  
                    if(@$convenio_id != '') { ?>
                        <dt>
                            <label>Convênio</label>
                        </dt>
                        <dd>
                            <? 
                            foreach ($convenio as $value) {
                                if ($value->convenio_id == @$convenio_id) {
                                    $convenioNome = $value->nome;
                                }
                            }
                            ?>
                            <input type="text" name="texto_convenio" value="<?=@$convenioNome?>" readonly="">
                            <input type="hidden" name="covenio" id="covenio" value="<?=@$convenio_id?>">

                        </dd>       
                    <? } else { ?>
                        <dt>
                            <label>Convênio</label>
                        </dt>
                        <dd>
                            <select name="covenio" id="covenio" class="size4 chosen-select" required>
                                <option value="">SELECIONE</option>
                                <? foreach ($convenio as $value) : ?>
                                    <option  value="<?= $value->convenio_id; ?>">
                                        <?php echo $value->nome; ?>
                                    </option>                            
                                <? endforeach; ?>                                                                                             
                            </select>               

                        </dd>   
                    <? } ?>                                                        
                    <dt>                         
                        <label>Grupo</label>
                    </dt>                    
                    <dd>                       
                        <select name="grupo" id="grupo" class="size4 chosen-select" required>
                            <option value="">SELECIONE</option>
                            <option>TODOS</option>                           
                            <? foreach ($grupo as $value) : ?>
                                <option value="<?= $value->nome; ?>"><?php echo $value->nome; ?></option>
                            <? endforeach; /* $value->ambulatorio_grupo_id; */ ?>

                        </select>
                    </dd>
                    <dt>
                        <label>Procedimento</label>
                    </dt>
                    <dd>
<!--                        <select  name="procedimento" id="procedimento" class="size4" >
                            <option value="">SELECIONE</option>
                        </select>-->
                        
                        <select name="procedimento" id="procedimento" class="size4 chosen-select" data-placeholder="Selecione" tabindex="1">
                            <option value="">Selecione</option>
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
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

<script type="text/javascript">
//    $('#btnVoltar').click(function () {
//        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
//    });

    $(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $('#covenio').change(function () {
            if ($(this).val()) {
                if ( $('#grupo').val() == "TODOS") {
                    $('.carregando').show();
                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoporconveniobardeira', {covenio: $(this).val(), ajax: true}, function (j) {
                        options = '<option value="">TODOS</option>';
                        for (var c = 0; c < j.length; c++) {
                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                        }
//                        $('#procedimento').html(options).show();
                        $('#procedimento option').remove();
                        $('#procedimento').append(options);
                        $("#procedimento").trigger("chosen:updated");
                        $('.carregando').hide();
                    });
                }
                else{
                    if ( $('#grupo').val() != "") {
                        $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniogrupobardeira', {grupo1: $('#grupo').val(), convenio1: $(this).val()}, function (j) {
                            options = '<option value="">TODOS</option>';
                            for (var c = 0; c < j.length; c++) {
                                options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                            }
//                            $('#procedimento').html(options).show();
                            $('#procedimento option').remove();
                            $('#procedimento').append(options);
                            $("#procedimento").trigger("chosen:updated");
                            $('.carregando').hide();
                        });
                    }
                }
            } else {
//                $('#procedimento').html();

                $('#procedimento option').remove();
                $('#procedimento').append('<option value="">SELECIONE</option>');
                $("#procedimento").trigger("chosen:updated");
            }
        });
    });
    
    
    
    $(function () {
        $('#grupo').change(function () {
            if ($('#grupo').val() == 'RM') {
                $('#revisordiv').show();
            } else {
                $('#revisordiv').hide();
            }


        });
    });

    if ($('#grupo').val() == 'RM') {
        $('#revisordiv').show();
    } else {
//        $('#revisordiv').hide();
        $(document).ready(function () {
            $('#revisordiv').hide();
        });
    }

            
    
    $(function () {
        $('#grupo').change(function () {
            if ($('#covenio').val() != 'SELECIONE' && $('#grupo').val() != 'TODOS') {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniogrupobardeira', {grupo1: $(this).val(), convenio1: $('#covenio').val()}, function (j) {
                    options = '<option value="">TODOS</option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                    }
//                    $('#procedimento').html(options).show();

                    $('#procedimento option').remove();
                    $('#procedimento').append(options);
                    $("#procedimento").trigger("chosen:updated");
                    $('.carregando').hide();
                });
            }
            
            else {
                
                if ( $('#grupo').val() == 'TODOS' ) {
                    $('.carregando').show();
                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoporconveniobardeira', {covenio: $('#covenio').val(), ajax: true}, function (j) {
                        options = '<option value="">TODOS</option>';
                        for (var c = 0; c < j.length; c++) {
                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                        }
//                        $('#procedimento').html(options).show();
                        $('#procedimento option').remove();
                        $('#procedimento').append(options);
                        $("#procedimento").trigger("chosen:updated");
                        $('.carregando').hide();
                    });
                }
                
            }
        });
    });


</script>
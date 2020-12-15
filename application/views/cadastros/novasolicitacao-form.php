          
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Solicitacação de Agendamento</a></h3>
            <form name="form_novasolitacao" id="form_novasolitacao" action="<?= base_url() ?>ambulatorio/guia/gravarsolicitacaoagendamento/" method="post">
          <dl>
                    
                    <dt>
                        <label>Nome:</label>     
                    </dt>
                    <dd>
                        <input type ="hidden" id="txtPacienteId" name ="paciente_id"  value ="<?= @$obj[0]->paciente_id; ?>" id ="txtPacienteId">
                        <input type ="hidden" id="paciente_solicitar_agendamento_id" name ="paciente_solicitar_agendamento_id"  value ="<?= $solicitacao_id; ?>" id ="txtPacienteId">
                        <input type="text" id="txtNome" name="nome" class="texto10"  value="<?= @$obj[0]->paciente; ?>" required="true" />
                        <input type="hidden" name="agendado" value="<?= @$agendado; ?>">
                    </dd>

                    <dt>
                        <label>Telefone:</label>     
                    </dt>
                    <dd>
                        <input type="text" id="txtTelefone" name="telefone" class="texto2"  value="<?= @$obj[0]->telefone; ?>" required="true" />
                    </dd>
                
               
               
                    <dt>
                        <label>Data:</label>
                        </dt>
                        <dd>
                        <input type="text" name="inicio" id="inicio" class="texto04" alt="date" value="<?= substr(@$obj[0]->data, 8, 2) . '/' . substr(@$obj[0]->data, 5, 2) . '/' . substr(@$obj[0]->data, 0, 4);  ?>" required=""/>
                        </dd>
               
                
                    <dt >
                        <label>Turno:</label>
                    </dt>
                        <dd>
                        <select name="turno" id="turnos">
                            <option <?= @$obj[0]->turno == 'manha' ? 'selected' : ''?> value="manha">Manhã</option>
                            <option <?= @$obj[0]->turno == 'tarde' ? 'selected' : ''?> value="tarde">Tarde</option>
                            <option <?= @$obj[0]->turno == 'noite' ? 'selected' : ''?> value="noite">Noite</option>
                        </select>
                        </dd>
               
                     <dt>
                         <label>Convenio:</label>
                     </dt>
                        <dd>
                        <select name="convenio1" id="convenio1" class="size1" required="">
                            <option value="">Selecione</option>
                                        <?
                                        foreach ($convenio as $item) :
                                            ?>
                                            <option value="<?= $item->convenio_id; ?>" <?= @$obj[0]->convenio_id == $item->convenio_id ? 'selected' : ''?>>
                                                <?= $item->nome; ?>
                                            </option>
                                        <? endforeach; ?>
                        </select>
                        </dd>
                
                    <dt>
                        <label>Procedimento:</label>
                    </dt>
                    <dd> 
                        <select name="procedimento1" id="procedimento1" class="size2" data-placeholder="Selecione" tabindex="1" required>
                            <option value="">Selecione</option>
                        </select>
                    </dd>

                    <dt>
                        <label>Medico:</label>
                    </dt>
                    <dd> 
                                <select  name="medicoagenda" id="exame" class="size1"  required="">
                                        <option value="">Selecione</option>
                                        <? foreach ($medicos as $item) : ?>
                                            <option value="<?= $item->operador_id; ?>" <?= @$obj[0]->medico_id == $item->operador_id ? 'selected' : ''?>><?= $item->nome; ?></option>
                                                <? endforeach; ?>
                                </select>
                    </dd>


                    <dt>
                        <label>Observaçao:</label>
                    </dt>
                    <dd> 
                        <textarea type="text" name="txtobservacao"class="texto10"><?=@$obj[0]->observacao?></textarea>
                    </dd>
                    
                    <br> <hr>
                    <dt>
                    <dd>
                      <button type="submit" name="btnEnviar">Enviar</button>
                    </dd>
                    </dt>
                    
          </dl>


            </form>

</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript">


    $(function () {
        $("#accordion").accordion();
    });
    
    $(function () {
        $("#inicio").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    jQuery("#txtTelefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });


     $(function () {
                                            $('#convenio1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();                                                  
                                                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoconvenioatendimentonovo', {convenio1: $('#convenio1').val()}, function (j) {
                                                        options = '<option value=""></option>';

                                                        for (var c = 0; c < j.length; c++) {
                                                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                                                        }
                                                        $('#procedimento1 option').remove();
                                                        $('#procedimento1').append(options);
                                                        $("#procedimento1").trigger("chosen:updated");
                                                        $('.carregando').hide();
                                                    });

                                                } else {
                                                    $('#procedimento1').html('<option value="">Selecione</option>');
                                                }
                                            });
                                        });

    <?if(isset($obj[0]->convenio_id)){?>                                    
    var convenio_id = <?=@$obj[0]->convenio_id?>;
    selecionarprocedimento(convenio_id);

    function selecionarprocedimento(convenio){
        if (convenio) {
            $('.carregando').show();                                                  
            $.getJSON('<?= base_url() ?>autocomplete/procedimentoconvenioatendimentonovo', {convenio1: $('#convenio1').val()}, function (j) {
                options = '<option value=""></option>';

                for (var c = 0; c < j.length; c++) {
                    if(j[c].procedimento_convenio_id == <?=@$obj[0]->procedimento_convenio_id?>){
                        options += '<option selected value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                    }else{
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                    }
                }
                $('#procedimento1 option').remove();
                $('#procedimento1').append(options);
                $("#procedimento1").trigger("chosen:updated");
                $('.carregando').hide();
            });

        } else {
            $('#procedimento1').html('<option value="">Selecione</option>');
        }
    }

    <?}?>
                                        
                                        <? if (@$obj->_paciente_id == null) { ?>
                                                    $(function () {
                                                        $("#txtNome").autocomplete({
                                                            source: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
                                                            minLength: 5,
                                                            focus: function (event, ui) {
                                                                $("#txtNome").val(ui.item.label);
                                                                return false;
                                                            },
                                                            select: function (event, ui) {
                                                                $("#txtNome").val(ui.item.value);
                                                                $("#txtPacienteId").val(ui.item.id);
                                                                $("#txtTelefone").val(ui.item.itens);
                                                                $("#txtCelular").val(ui.item.celular);
                                                                $("#nascimento").val(ui.item.valor);
                                                                $("#cpf").val(ui.item.cpf);
                                                                return false;
                                                            }
                                                        });
                                                    });



                                                    $(function () {
                                            $('#procedimento1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();

                                                    var procedimento = $(this).val();
       
                                                    var medico_antigo = $('#exame').val();
                                                   
                                                    $.getJSON('<?= base_url() ?>autocomplete/listarmedicoprocedimentoconvenio', {procedimento: $(this).val(), ajax: true}, function (m) {
                                                 
                                                        options = '<option value=""></option>';
                                                        //                            console.log(j);
                                                        for (var y = 0; y < m.length; y++) {                                                              
                                                            if(m[y].operador_id ==  medico_antigo){
                                                              options += '<option selected value="' + m[y].operador_id + '">' + m[y].nome + '</option>';  
                                                            }else{
                                                               options += '<option value="' + m[y].operador_id + '">' + m[y].nome + '</option>';
                                                            }
                                                        }
                                                        $('#exame').html(options).show();

                                                        $('.carregando').hide();

                                                    });

                                                }

                                                   
                                                     $.getJSON('<?= base_url() ?>autocomplete/listarmedicoprocedimentoconvenio', {ajax: true}, function (m) {
//                                                      console.log(m);
                                                        options = '<option value=""></option>';
                                                        //                            console.log(j);
                                                        for (var y = 0; y < m.length; y++) {                                                              
                                                            if(m[y].operador_id ==  medico_antigo){
                                                              options += '<option selected value="' + m[y].operador_id + '">' + m[y].nome + '</option>';  
                                                            }else{
                                                               options += '<option value="' + m[y].operador_id + '">' + m[y].nome + '</option>';
                                                            }
                                                        }
                                                        $('#exame').html(options).show();

                                                        $('.carregando').hide();

                                                    });
                                                
                                            });
                                        });

<?
} ?>
                                        
                                        

</script>

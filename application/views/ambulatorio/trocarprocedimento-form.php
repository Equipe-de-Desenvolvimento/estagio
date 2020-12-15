<head>
    <meta charset="UTF-8"/>
</head>
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Trocar Procedimento</h3>
        <div>
            <form name="form_faturar" id="form_faturar" action="<?= base_url() ?>ambulatorio/exame/gravartrocarprocedimento" method="post">
                <fieldset> 
                    <input type="hidden" name="agenda_exames_id" id="agenda_exames_id" value="<?= $agenda_exames_id ?>"/>
                    <input type="hidden" name="empresa_id" id="empresa_id" value="<?= $consulta[0]->empresa_id; ?>"/>
                    <input type="hidden" name="medicoid" id="medicoid" value="<?= $consulta[0]->medico_agenda; ?>"/>
                    <dt>
                        <label>Procedimento atual</label>
                    </dt>
                    <dd>
                        <input type="text" value="<?= $consulta[0]->procedimento ?>" readonly/>
                    <dd>
                    <dt>
                        <label>Trocar por:</label>
                    </dt>
                    <dd>
                       
                        
                        <table>
                            <tr>
                                <td>Convênio:</td>
                                <td>
                                     <select name="convenio1" id="convenio1" class="size4" required>
                                        <option  value="">Selecione</option>
                                        <? foreach ($convenios as $value) : ?>
                                            <option value="<?= $value->convenio_id; ?>"><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </td>
                            </tr> 
                              <tr>
                                <td>Grupo:</td>
                                <td> 
                                    <select name="grupo1" id="grupo1" class="size4">
                                        <option  value="">Selecione</option>
                                        <? foreach ($grupos as $value) : ?>
                                            <option value="<?= $value->nome; ?>"><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                   </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Procedimento:</td>
                                <td> 
                                 <select  name="procedimento1" id="procedimento1" class="size1" style="width: 200px" required>
                                    <option value="">Selecione</option> 
                                 </select>
                                </td>
                            </tr> 
                        </table>
                    </dd>
                   
                    <hr>
                    <dd>                    
                        <button type="submit" id="enviar" >Enviar</button>
                    </dd>

           
            </fieldset>
           </form>
        </div>
    </div> <!-- Final da DIV content -->
</body>


<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css"> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>

<script type="text/javascript">
    $(function () {
        $('#convenio1').change(function () {
//            alert('teste');
            if ($(this).val()) {
                $('.carregando').show();  
                $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniomedico', {convenio1: $(this).val(), teste: $("#medicoid").val(), empresa_id:$('#empresa_id').val(), grupo1: $("#grupo1").val()}, function (j) {
                    options = '<option value=""></option>';
                     console.log(j);
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                    }
                    $('#procedimento1').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#procedimento1').html('<option value="">Selecione</option>');
            }
        });
        
        $('#grupo1').change(function () {
//            alert('teste');
            if ($(this).val()) {
                $('.carregando').show(); 
                
                $.getJSON('<?= base_url() ?>autocomplete/procedimentoconveniomedico', {convenio1: $('#convenio1').val(), teste: $("#medicoid").val(), empresa_id:$('#empresa_id').val(), grupo1: $("#grupo1").val()}, function (j) {
                    options = '<option value=""></option>';
                     console.log(j);
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                    }
                    $('#procedimento1').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#procedimento1').html('<option value="">Selecione</option>');
            }
        });
    });
    
 </script>
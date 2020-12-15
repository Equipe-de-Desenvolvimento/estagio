<div id="page-wrapper"> <!-- Inicio da DIV content -->
    <div class="row">
        <div class="col-lg-12">
            <!--<div class="panel panel-default">-->
            <div class="alert alert-success">
                Marcar Atendimento
            </div>

            <!--</div>-->
        </div>
    </div>
    <form name="form_exametemp" id="form_exametemp" action="<?= base_url() ?>ambulatorio/exametemp/gravarpacienteconsultamedicotemp/<?= $agenda_exames_id ?>" method="post">
        <div class="panel panel-default ">
            <div class="alert alert-info">
                Dados do Atendimento
            </div>
            <div class="panel-body">
                <div class="row">
                    <!--                    <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Prontuário*</label>
                                                <input type="text" id="txtNomeid" class="form-control" name="txtNomeid" readonly="true" />
                                            </div>
                    
                    
                                        </div>-->
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label>Paciente*</label>
                            <input type="hidden" id="txtNomeid" class="form-control texto02" name="txtNomeid" readonly="true" />
                            <input type="text" id="txtNome" required name="txtNome" class="form-control eac-square" onblur="calculoIdade(document.getElementById('nascimento').value)"  />
                        </div>


                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Data de Nasc</label>
                            <!--<input type="text" id="txtNomeid" class="form-control texto02" name="txtNomeid" readonly="true" />-->
                            <input type="text" name="nascimento" id="nascimento" class="form-control data" alt="date"  maxlength="10"  onkeypress="mascara3(this)" onblur="calculoIdade(this.value)"/>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" id="txtTelefone" class="form-control" name="txtTelefone"/>
                        </div>


                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Celular</label>
                            <input type="text" id="txtCelular" class="form-control" name="txtCelular"/>
                        </div>


                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Convenio *</label>
                            <select name="convenio" id="convenio" class="form-control" required>
                                <option  value="0">Selecione</option>
                                <? foreach ($convenio as $value) : ?>
                                    <option value="<?= $value->convenio_id; ?>"><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>


                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Procedimento</label>
                            <select  name="procedimento" id="procedimento" class="form-control" required >
                                <option value="">Selecione</option>
                            </select>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-8">
                        <div class="form-group">
                            <label>Observacoes</label>
                            <textarea id="observacoes" class="form-control" name="observacoes"></textarea>



                        <!--<input type="text" id="observacoes" class="form-control" name="observacoes" />-->
                        </div>


                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-1">
                        <button class="btn btn-outline btn-success btn-sm" type="submit" name="btnEnviar"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Enviar</button>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-outline btn-danger btn-sm" type="reset" name="btnLimpar">Limpar</button>
                    </div>
                </div>



            </div>

        </div>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">


                <table class="table table-bordered table-striped table-hover">
                    <thead>

                        <tr>
                            <th class="tabela_header">Data</th>
                            <th class="tabela_header">Hora</th>
                            <th class="tabela_header">Exame</th>
                            <th class="tabela_header">Observa&ccedil;&otilde;es</th>
                        </tr>
                    </thead>
                    <?
                    $estilo_linha = "tabela_content01";
                    foreach ($consultas as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        ?>

                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data, 8, 2) . '/' . substr($item->data, 5, 2) . '/' . substr($item->data, 0, 4); ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->inicio; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->observacoes; ?></td>
                        </tr>


                        <?
                    }
                    ?>
<!--            <tfoot>
<tr>
    <th class="tabela_footer" colspan="4">
    </th>
</tr>
</tfoot>-->
                </table>
            </div>

        </div>

    </div>


</div> <!-- Final da DIV content -->

<!--<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>-->
<script>
                                function mascaraTelefone(campo) {

                                    function trata(valor, isOnBlur) {

                                        valor = valor.replace(/\D/g, "");
                                        valor = valor.replace(/^(\d{2})(\d)/g, "($1)$2");

                                        if (isOnBlur) {

                                            valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
                                        } else {

                                            valor = valor.replace(/(\d)(\d{3})$/, "$1-$2");
                                        }
                                        return valor;
                                    }

                                    campo.onkeypress = function (evt) {

                                        var code = (window.event) ? window.event.keyCode : evt.which;
                                        var valor = this.value

                                        if (code > 57 || (code < 48 && code != 8)) {
                                            return false;
                                        } else {
                                            this.value = trata(valor, false);
                                        }
                                    }

                                    campo.onblur = function () {

                                        var valor = this.value;
                                        if (valor.length < 13) {
                                            this.value = ""
                                        } else {
                                            this.value = trata(this.value, true);
                                        }
                                    }

                                    campo.maxLength = 14;
                                }


</script>
<script type="text/javascript">
    mascaraTelefone(form_exametemp.txtTelefone);
    mascaraTelefone(form_exametemp.txtCelular);

    $(function () {
        $("#data_ficha").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function () {
        $('#exame').change(function () {
            if ($(this).val()) {
                $('#horarios').hide();
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/horariosambulatorio', {exame: $(this).val(), teste: $("#data_ficha").val()}, function (j) {
                    var options = '<option value=""></option>';
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].agenda_exames_id + '">' + j[i].inicio + '-' + j[i].nome + '-' + j[i].medico_agenda + '</option>';
                    }
                    $('#horarios').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#horarios').html('<option value="">-- Escolha um exame --</option>');
            }
        });
    });

    $(function () {
        $('#convenio').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/procedimentoconvenioconsulta', {convenio1: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                    }
                    $('#procedimento').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#procedimento').html('<option value="">Selecione</option>');
            }
        });
    });

//    $(function () {
//        $("#txtNome").autocomplete({
//            source: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
//            minLength: 5,
//            focus: function (event, ui) {
//                $("#txtNome").val(ui.item.label);
//                return false;
//            },
//            select: function (event, ui) {
//                $("#txtNome").val(ui.item.value);
//                $("#txtNomeid").val(ui.item.id);
//                $("#txtTelefone").val(ui.item.itens);
//                $("#txtCelular").val(ui.item.celular);
//                $("#nascimento").val(ui.item.valor);
//                return false;
//            }
//        });
//    });

    // NOVOS AUTOCOMPLETES.
    // A primeira coisa a definir é o nome da variável pra jogar no Jquery mais tarde
    // Url é a função que vai trazer o JSON.
    // getValue é onde se define o nome do campo que você quer que apareça na lista
    // Exemplo do getValue. Na função abaixo do CBOprofissionais, o Hamilton definiu value como o valor do CBO dos profissionais
    // Daí dentro da função list você define que match está enable, pra que ele possa verificar pelo texto que o cara digita
    // OnSelectItem event é a função responsável por jogar o ID que você quer no campo Hidden
    // getSelectedItemData(). Depois do ponto você coloca o campo que vai ser jogado no Hidden
    // Daí embaixo tem o Jquery padrão pra jogar o ID no campo Hidden

    var paciente = {
//        url: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
        url: function (phrase) {
            if (phrase.length > 2) {
                return "<?= base_url() ?>autocomplete/paciente?term=" + phrase;
            } else {
                //duckduckgo doesn't support empty strings
//                return "http://api.duckduckgo.com/?q=empty&format=json";
            }
        },
        getValue: "value",
        list: {
            onSelectItemEvent: function () {
                var value = $("#txtNome").getSelectedItemData().id;
                var telefone = $("#txtNome").getSelectedItemData().itens;
                var celular = $("#txtNome").getSelectedItemData().celular;
                var nascimento = $("#txtNome").getSelectedItemData().valor;

                $("#txtNomeid").val(value).trigger("change");
                $("#txtTelefone").val(telefone).trigger("change");
                $("#txtCelular").val(celular).trigger("change");
                $("#nascimento").val(nascimento).trigger("change");

            },
            match: {
                enabled: true
            },
            showAnimation: {
                type: "fade", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 200,
                callback: function () {}
            },
            maxNumberOfElements: 10
        },
        requestDelay: 200,
        theme: "bootstrap"
    };

    $("#txtNome").easyAutocomplete(paciente);
    // FINAL DO AUTOCOMPLETE NOVO. DEFINE AQUI O ID DO CAMPO ATRIBUIDO E A VARIVEL





    $(function () {
        $("#accordion").accordion();
    });


    $(document).ready(function () {
        jQuery('#form_exametemp').validate({
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

    function calculoIdade() {
        var data = document.getElementById("nascimento").value;
        var ano = data.substring(6, 12);
        var idade = new Date().getFullYear() - ano;
        document.getElementById("idade2").value = idade;
    }

    jQuery("#nascimento").mask("99/99/9999");

</script>
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ambulatorio/procedimentoplano">
            Voltar
        </a>

    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro De Valor Base</a></h3>
        <!--<div class="ajusteAccordion">--> 
            <form name="form_procedimentoplano" id="form_procedimentoplano" action="<?= base_url() ?>ambulatorio/procedimentoplano/gravarvalorbase/<?=@$procedimentoescolhido[0]->procedimento_convenio_id?>" method="post">

                <table class="dl_desconto_lista">
                    <input type="hidden" name="procedimento_convenio_id" value="<?= @$procedimentoescolhido[0]->procedimento_convenio_id; ?>" />
                    <tr>
                        <td>
                            <label>Convenio *</label>
                        </td>
                        <td>
                            <input readonly type="text" name="convenio" id="convenio" class="texto05" value="<?=@$procedimentoescolhido[0]->convenio?>" />
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Procedimento *</label>
                        </td>
                        <td>
                            <input readonly type="text" name="procedimento" id="procedimento" class="texto08" value="<?=@$procedimentoescolhido[0]->procedimento?>" />
                            
                        </td>
                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Qtde CH</label>
                        </td>
                        <td>
                            <input type="text" name="qtdech" id="qtdech" class="texto01" value="<?= @$procedimentoescolhido[0]->qtdech; ?>"/>
                        </td>
                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Valor CH</label>
                        </td>
                        <td>
                            <input type="text" name="valorch" id="valorch" class="texto01" value="<?= @$procedimentoescolhido[0]->valorch; ?>"/>
                        </td>
                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Qtde Filme</label>
                        </td>
                        <td>
                            <input type="text" name="qtdefilme" id="qtdefilme" class="texto01" value="<?= @$procedimentoescolhido[0]->qtdefilme; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Valor Filme</label>
                        </td>
                        <td>
                            <input type="text" name="valorfilme" id="valorfilme" class="texto01" value="<?= @$procedimentoescolhido[0]->valorfilme; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Qtde Porte</label>
                        </td>
                        <td>
                            <input type="text" name="qtdeporte" id="qtdeporte" class="texto01" value="<?= @$procedimentoescolhido[0]->qtdeporte; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Valor Porte</label>
                        </td>
                        <td>
                            <input type="text" name="valorporte" id="valorporte" class="texto01" value="<?= @$procedimentoescolhido[0]->valorporte; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Qtde UCO</label>
                        </td>
                        <td>
                            <input type="text" name="qtdeuco" id="qtdeuco" class="texto01" value="<?= @$procedimentoescolhido[0]->qtdeuco; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Valor UCO</label>
                        </td>
                        <td>
                            <input type="text" name="valoruco"id="valoruco" class="texto01" value="<?= @$procedimentoescolhido[0]->valoruco; ?>" />
                        </td>

                    </tr>
                    <tr id="valoresdiv">
                        <td>
                            <label>Valor TOTAL</label>
                        </td>
                        <td>
                            <input readonly type="text" name="valortotal"  id="valortotal" class="texto01" value="<?= @$procedimentoescolhido[0]->valortotal; ?>" />
                        </td>

                    </tr>

                </table>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        <!--</div>-->
    </div>
</div> <!-- Final da DIV content -->

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
    $(function () {
        $("#accordion").accordion();
        $("#form_procedimentoplano").css("height", '370pt');
    });
    
    <? if (@$procedimentoescolhido[0]->associado == 't') { ?>
        $("#conv_secundario").val('t');
        $("tr#brasindice_div").hide();
        $("tr#grupodiv").hide();
        $("tr#valoresdiv").hide();
        $("tr#procedimentodiv").show();
        $("#form_procedimentoplano").css("height", '500pt');
    <?} else{ ?>
        $("#grupodiv").hide();
    <? } ?>
        
    $('#brasindice').change(function () {
        if ($(this).is(":checked")) {
//            console.log($("#valoresdiv"));
            $("tr#valoresdiv").hide();
            $("tr#procedimentodiv").hide();
            $("tr#grupodiv").show();
            $("grupo").prop('required', true);
            
        } else {
            $("tr#valoresdiv").show();
            $("tr#procedimentodiv").show();
            $("tr#grupodiv").hide();
            $("#grupo").prop('required', false);
//            $("#procedimento").toggle();
        }
    });

    $("#grupo").prop('required', false);
    
    $(function () {
        $('#convenio').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/buscarconveniosecundario', {convenio: $(this).val(), ajax: true}, function (j) {
                    if(j[0].associado == 't'){                        
                        $("#conv_secundario").val('t');
//                        $("#conv_principal_id").val(j[0].associacao_convenio_id);
                        $("tr#brasindice_div").hide();
                        $("tr#grupodiv").hide();
                        $("tr#valoresdiv").hide();
                        $("tr#procedimentodiv").show();
//                        $.getJSON('<?= base_url() ?>autocomplete/buscarprocedimentoconvenioprincipal', {convenio: j[0].associacao_convenio_id, ajax: true}, function (p) {
//                            options = '<option value=""></option>';
//                            for (var c = 0; c < p.length; c++) {
//                                options += '<option value="' + p[c].procedimento_tuss_id + '">' + p[c].codigo + ' - ' + p[c].nome + '</option>';
//                            }
//                            $("#accordion tr#procedimentodiv #procedimento option").remove();
//                            $('#accordion tr#procedimentodiv #procedimento').append(options);
//                            $("#procedimento_chosen .chosen-drop .chosen-results li").trigger('destroy');  
//                            $('select#procedimento').chosen_reset({width:'369px'});
//                            $('.chosen-').chosen("destroy").chosen();
//                        });
                    }
                    else{                        
                        $("#conv_secundario").val('f');
                        $("tr#brasindice_div").show();
//                        $("#grupodiv").show();
                        $("tr#valoresdiv").show();                    
                    }
                });
            } 
        });
    });

//                            $(document).ready(function () {

//                            function multiplica()
//                            {
//                                total = 0;
//                                numer1 = parseFloat(document.form_procedimentoplano.qtdech.value);
//                                numer2 = parseFloat(document.form_procedimentoplano.valorch.value);
//                                soma = numer1 * numer2;
//                                numer3 = parseFloat(document.form_procedimentoplano.qtdefilme.value);
//                                numer4 = parseFloat(document.form_procedimentoplano.valorfilme.value);
//                                soma2 = numer3 * numer4;
//                                numer5 = parseFloat(document.form_procedimentoplano.qtdeuco.value);
//                                numer6 = parseFloat(document.form_procedimentoplano.valoruco.value);
//                                soma3 = numer5 * numer6;
//                                numer7 = parseFloat(document.form_procedimentoplano.qtdeporte.value);
//                                numer8 = parseFloat(document.form_procedimentoplano.valorporte.value);
//                                soma4 = numer7 * numer8;
//                                total += soma + soma2 + soma3 + soma4;
//                                y = total.toFixed(2);
//                                $('#valortotal').val(y);
//                                //document.form_procedimentoplano.valortotal.value = total;
//                            }
//                            multiplica();


//                            });


    $(function () {
        $('#qtdech').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#qtdefilme').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#qtdeuco').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#qtdeporte').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#valorch').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#valorfilme').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#valoruco').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#valorporte').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });
        $('#qtdech').change(function () {
            valorch = parseFloat($('#qtdech').val()) * parseFloat($('#valorch').val());
            valorfilme = parseFloat($('#qtdefilme').val()) * parseFloat($('#valorfilme').val());
            valoruco = parseFloat($('#qtdeuco').val()) * parseFloat($('#valoruco').val());
            valorporte = parseFloat($('#qtdeporte').val()) * parseFloat($('#valorporte').val());
            valortotal = parseFloat(valoruco + valorfilme + valorporte + valorch).toFixed(2);
//                                    alert(valortotal);
            $('#valortotal').val(valortotal);
        });

    });

</script>
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>cadastros/contaspagar">
            Voltar
        </a>
        <!--ponto/horarioscontaspagar-->        
    </div>
    <?
    $permissoes = $this->caixa->listarpermissoesempresa();
    ?>
    <div id="accordion" >
        <h3 class="singular"><a href="#">Contas a pagar</a></h3>
        <div>
            <form name="form_contaspagar" id="form_contaspagar" action="<?= base_url() ?>cadastros/contaspagar/gravaralterardata" enctype="multipart/form-data" method="post">
            <table>
            <tr>
                <th class="tabela_header">Credor</th>
                <th class="tabela_header">Tipo</th>
                <th class="tabela_header">Classe</th>
                <th class="tabela_header">Conta</th>
                <th class="tabela_header">Parcela</th>
                <th class="tabela_header">Data</th>
            </tr>
            <?php
                        
            $estilo_linha = "tabela_content01";
            $dataatual = date("Y-m-d");
            $valortotal = 0;
            $i = 0;
            foreach ($lista as $item) {
                $i++;
                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                $valortotal = $valortotal + $item->valor;
                ?>
                <tr>
                    <? if ($dataatual > $item->data) { ?>
                        <td class="<?php echo $estilo_linha; ?>"><font color="red"><?= $item->razao_social; ?></td>
                    <? } else { ?>
                        <td class="<?php echo $estilo_linha; ?>"><?= $item->razao_social; ?></td>
                    <? } ?>
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->tipo; ?></td>
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->classe; ?></td>
                    
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->conta; ?></td>
                    <td class="<?php echo $estilo_linha; ?>">
                    <?if($item->parcela != ''){
//                                    echo $item->parcela, "ª"; 
                        echo $item->parcela, "/", $item->numero_parcela;
                    }?>
                    </td>   
                    <td class="<?php echo $estilo_linha; ?>">
                        <input type="hidden" name="contaspagar_id[]" id="contaspagar_id<?=$i?>" class="texto04" alt="date" value="<?= @$item->financeiro_contaspagar_id ?>" required=""/>
                        <input type="text" name="data[]" id="data<?=$i?>" class="texto02 data" alt="date" value="<?= substr(@$item->data, 8, 2) . '/' . substr(@$item->data, 5, 2) . '/' . substr(@$item->data, 0, 4);  ?>" required=""/>
                    </td> 
                </tr>
            <?}?>
            </tbody>
        </table>
               <br> 
               <br> 
                 
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>  

                
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
    
    $(function () {
        $("#credorlabel").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=credordevedor",
            minLength: 1,
            focus: function (event, ui) {
                $("#credorlabel").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#credorlabel").val(ui.item.value);
                $("#credor").val(ui.item.id);
                return false;
            }
        });
    });


    $(function () {
        $('#tipo').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalista', {nome: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                    }
                    $('#classe').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#classe').html('<option value="">TODOS</option>');
            }
        });
    });
   
    function mostrarParcelasRepetir() {
        var repetir_str = parseInt($('#repetir').val());
        var inicio_str = $('#inicio').val();
        if(repetir_str > 0 && inicio_str != ''){
            $.getJSON('<?= base_url() ?>cadastros/contaspagar/repetirDiaDePagamento', {repetir: repetir_str, inicio: inicio_str, ajax: true}, function (j) {
                // console.log(j);
                var tr = '';
                $('#tableParcelas').html('');
                for (var c = 0; c < j.length; c++) {
                    tr += '<tr><td style="width: 100px;">'+ (c + 1) +' Parcela</td><td>'+j[c]+'</td></tr>';
                }
                $('#tableParcelas').html(tr);
            });
        }
    }
        
  

    function date_picker (id){
        // alert(id);
        $("#txtdata" + id).datepicker({
            beforeShowDay: function(d) {
        // normalize the date for searching in array
            var dmy = "";
            dmy += ("00" + d.getDate()).slice(-2) + "-";
            dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "-";
            dmy += d.getFullYear();
//            console.log(dmy);
            return [$.inArray(dmy, array_Dates) >= 0 ? true : false, ""];
            },
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
//    });
    }


    $(function () {
        $(".data").datepicker({
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
        $("#accordion").accordion();
    });



    $(document).ready(function () {
        jQuery('#form_contaspagar').validate({
            rules: {
                valor: {
                    required: true
                },
                credor: {
                    required: true
                },
                classe: {
                    required: true
                },
                conta: {
                    required: true
                },
                inicio: {
                    required: true
                }
            },
            messages: {
                valor: {
                    required: "*"
                },
                credor: {
                    required: "*"
                },
                classe: {
                    required: "*"
                },
                conta: {
                    required: "*"
                },
                inicio: {
                    required: "*"
                }
            }
        });
    });

</script>
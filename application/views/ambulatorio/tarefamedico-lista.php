<?
$empresa_id = $this->session->userdata('empresa_id');
$data['empresa'] = $this->empresa->listarempresatoten($empresa_id);
@$endereco = $data['empresa'][0]->endereco_toten;
$data['permissao'] = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
?>

<div class="content"> <!-- Inicio da DIV content -->

    <div id="accordion">
        <h3 class="singular"><a href="#">Tarefas</a></h3>

        <div>
            <?
            $operador_id = $this->session->userdata('operador_id');
            $salas = $this->exame->listartodassalas();
            $empresa = $this->guia->listarempresasaladeespera();
            $operador = $this->operador_m->listaroperadoratendimento($operador_id);
            @$ordem_chegada = @$empresa[0]->ordem_chegada;
            @$ordenacao_situacao = @$empresa[0]->ordenacao_situacao;
            @$atendimento_medico = @$empresa[0]->atendimento_medico;
            @$retirar_ordem_medico = @$empresa[0]->retirar_ordem_medico;
            @$retirar_medico_cadastro = @$operador[0]->atendimento_medico;
            @$imprimir_medico = @$empresa[0]->imprimir_medico;
            @$bloquear_botao = @$empresa[0]->bloquear_botao;
            $medicos = $this->operador_m->listarmedicos();
            $perfil_id = $this->session->userdata('perfil_id');
            ?>
            <table>
                <thead>
                <form method="get" action="<?= base_url() ?>ambulatorio/exametemp/listartarefasmedico">
                    <tr>
                        <th class="tabela_title">Nome do paciente</th>
                        <th class="tabela_title">Nome da tarefa</th>
                        <th class="tabela_title">Data</th>
                        <? if ($perfil_id != 4) { ?>
                            <th class="tabela_title">Médico</th>                                      
                        <? } ?>

                        <th class="tabela_title">Status</th>
                        <th class="tabela_title"> </th>
                    </tr>
                    <tr>
                        <th class="tabela_title">
                            <input type="text" name="nomepaciente" value="<?php echo @$_GET['nomepaciente']; ?>" />
                        </th>
                        <th class="tabela_title">
                            <input type="text" name="nome" value="<?php echo @$_GET['nome']; ?>" />
                        </th>
                        <th class="tabela_title">
                            <input type="text"  id="data" alt="date" name="data" class="size1"  value="<?php echo @$_GET['data']; ?>" />
                        </th>
                        <? if ($perfil_id != 4) { ?>
                            <th class="tabela_title">

                                <select name="medico">
                                    <option value=""> Selecione</option>
                                    <?php
                                    foreach ($medicos as $item) {
                                        ?>
                                        <option value="<?= $item->operador_id; ?>" <?php
                                        if (@$_GET['medico'] == $item->operador_id) {
                                            echo "Selected";
                                        }
                                        ?> ><?= $item->nome; ?></option>
                                                <?
                                            }
                                            ?>

                                </select>

                            </th>
                        <? } ?>
                        <th class="tabela_title">
                            <select name="status">
                                <option value="">Selecione</option>
                                <option value="ATENDENDO" <?php
                                if (@$_GET['status'] == "ATENDENDO") {
                                    echo "Selected";
                                }
                                ?>>ATENDENDO</option>
                                <option value="AGUARDANDO" <?php
                                if (@$_GET['status'] == "AGUARDANDO") {
                                    echo "Selected";
                                }
                                ?>>AGUARDANDO</option>
                                <option value="FINALIZADO" <?php
                                if (@$_GET['status'] == "FINALIZADO") {
                                    echo "Selected";
                                }
                                ?>>FINALIZADO</option>
                            </select>
                        </th> 

                        <th class="tabela_title">
                            <button type="submit" id="enviar">Pesquisar</button>
                        </th>
                    </tr>
                </form>
                </thead>
            </table>
            <?
            $listas = $this->exametemp->listartarefas($_GET)->get()->result();
            ?>

            <table>
                <tr>
                    <th class="tabela_header">Status</th>
                    <th class="tabela_header">Nome do paciente</th>
                    <th class="tabela_header">Nome da tarefa</th>
                    <th class="tabela_header">Data</th>
                       <th class="tabela_header">Operador Cadastro</th>
                    <th class="tabela_header" colspan="3"  style="text-align: center;">Ações</th>
                </tr>
                <?php
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                $limit = 100;
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $lista = $this->exametemp->listartarefas($_GET)->orderby('tm.ordenador', 'asc')->limit($limit, $pagina)->get()->result();
                $total = count($listas);
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            if ($item->status == "ATENDENDO") {
                                @$cor = "green";
                            } elseif ($item->status == "AGUARDANDO") {
                                @$cor = "red";
                            } elseif ($item->status == "FINALIZADO") {
                                @$cor = "blue";
                            } else {
                                @$cor = "";
                            }
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"> <b style="color:<?= @$cor; ?>;"><?= @$item->status; ?></b> </td>  
                                <td class="<?php echo $estilo_linha; ?>"> <?= @$item->paciente; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"> <?= @$item->nome_tarefa; ?></td>

                                <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime(@$item->data)); ?> </td>
                                <td class="<?php echo $estilo_linha; ?>"><?=  @$item->operador_cadastro; ?> </td>
                                <td class="<?php echo $estilo_linha; ?>"> <div class="bt_link"><a  
                                             onclick="abrirtarefamedico(<?= @$item->tarefa_medico_id; ?>,<?= @$item->paciente_id; ?>)"
                                            style="cursor:pointer;" 
                                            
                                            >Visualizar</a> </div></td>
                                <td class="<?php echo $estilo_linha; ?>"> 
                                    <div class="bt_link">
                                        <a  href="<?= base_url() ?>ambulatorio/exametemp/impressaotarefa/<?= @$item->tarefa_medico_id; ?>" target="_blank">Imprimir</a>
                                    </div>
                                </td>                                    
                                <?php
                                if (@$this->session->userdata('perfil_id') == 1) {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>"> 
                                        <div class="bt_link">
                                            <a href="<?= base_url() ?>ambulatorio/exametemp/excluirtarefa/<?= @$item->tarefa_medico_id; ?>/<?= @$item->paciente_id; ?>" onclick="return confirm('Deseja realmente excluir?');" target="_blank"> Excluir</a>
                                        </div>
                                    </td>
                                <? } ?>
 
                            </tr>
                        <?php } ?>




                    </tbody>
                <?php } ?>
                <tfoot>
                    <tr>

                        <th class="tabela_footer" colspan="16">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>


            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>    
<script type="text/javascript">
     function abrirtarefamedico(tarefa_medico_id,paciente_id){
//       href="<?= base_url() ?>ambulatorio/exametemp/visualizartarefamedic/<?= @$item->tarefa_medico_id; ?>/<?= @$item->paciente_id; ?>" target="_blank"
         window.open('<?= base_url() ?>ambulatorio/exametemp/visualizartarefamedic/'+tarefa_medico_id+'/'+paciente_id+'', '_blank');
       
   }
                                    $(document).ready(function () {
//alert('teste_parada');
                                        if ($('#especialidade').val() != '') {
                                            $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidade', {txtcbo: $('#especialidade').val(), ajax: true}, function (j) {
                                                var options = '<option value=""></option>';
                                                var slt = '';
                                                for (var c = 0; c < j.length; c++) {
                                                    if (j[0].operador_id != undefined) {
                                                        if (j[c].operador_id == '<?= @$_GET['medico'] ?>') {
                                                            slt = 'selected';
                                                        }
                                                        options += '<option value="' + j[c].operador_id + '" ' + slt + '>' + j[c].nome + '</option>';
                                                        slt = '';
                                                    }
                                                }
                                                $('#medico').html(options).show();
                                                $('.carregando').hide();



                                            });
                                        }
                                        $(function () {
                                            $('#especialidade').change(function () {

                                                if ($(this).val()) {

//              alert('teste_parada');
                                                    $('.carregando').show();
//                    alert('teste_parada');
                                                    $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidade', {txtcbo: $(this).val(), ajax: true}, function (j) {
                                                        options = '<option value=""></option>';
                                                        console.log(j);

                                                        for (var c = 0; c < j.length; c++) {


                                                            if (j[0].operador_id != undefined) {
                                                                options += '<option value="' + j[c].operador_id + '">' + j[c].nome + '</option>';

                                                            }
                                                        }
                                                        $('#medico').html(options).show();
                                                        $('.carregando').hide();



                                                    });
                                                } else {
                                                    $('.carregando').show();
//                    alert('teste_parada');
                                                    $.getJSON('<?= base_url() ?>autocomplete/medicoespecialidadetodos', {txtcbo: $(this).val(), ajax: true}, function (j) {
                                                        options = '<option value=""></option>';
                                                        console.log(j);

                                                        for (var c = 0; c < j.length; c++) {


                                                            if (j[0].operador_id != undefined) {
                                                                options += '<option value="' + j[c].operador_id + '">' + j[c].nome + '</option>';

                                                            }
                                                        }
                                                        $('#medico').html(options).show();
                                                        $('.carregando').hide();



                                                    });

                                                }
                                            });
                                        });




                                        $(function () {
                                            $("#txtCICPrimariolabel").autocomplete({
                                                source: "<?= base_url() ?>index.php?c=autocomplete&m=cid1",
                                                minLength: 3,
                                                focus: function (event, ui) {
                                                    $("#txtCICPrimariolabel").val(ui.item.label);
                                                    return false;
                                                },
                                                select: function (event, ui) {
                                                    $("#txtCICPrimariolabel").val(ui.item.value);
                                                    $("#txtCICPrimario").val(ui.item.id);
                                                    return false;
                                                }
                                            });
                                        });



                                        $(function () {
                                            $("#data").datepicker({
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

                                        setTimeout('delayReload()', 20000);
                                        function delayReload()
                                        {
                                            if (navigator.userAgent.indexOf("MSIE") != -1) {
                                                history.go(0);
                                            } else {
                                                window.location.reload();
                                            }
                                        }

                                    });

<? if (($endereco != '')) { ?>
                                        function enviarChamadaPainel(url, toten_fila_id, medico_id, toten_sala_id) {
                                            // alert('Teste');
                                            $.ajax({
                                                type: "POST",
                                                data: {teste: 'teste'},
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: "<?= $endereco ?>/webService/telaChamado/proximo/" + medico_id + '/ ' + toten_fila_id + '/' + toten_sala_id,
                                                success: function (data) {

                                                    alert('Operação efetuada com sucesso');


                                                },
                                                error: function (data) {
                                                    console.log(data);
                                                    alert('Erro ao chamar paciente');
                                                }
                                            });
                                            $.ajax({
                                                type: "POST",
                                                data: {teste: 'teste'},
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: "<?= $endereco ?>/webService/telaChamado/cancelar/" + toten_fila_id,
                                                success: function (data) {

                                                    //                            alert('Operação efetuada com sucesso');


                                                },
                                                error: function (data) {
                                                    console.log(data);
                                                    //                            alert('Erro ao chamar paciente');
                                                }
                                            });

                                        }
                                        function chamarPaciente(url, toten_fila_id, medico_id, toten_sala_id) {
                                            //   alert(medico_id);
                                            $.ajax({
                                                type: "POST",
                                                data: {teste: 'teste'},
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: url,
                                                success: function (data) {
                                                    //                console.log(data);
                                                    //                    alert(data.id);

                                                    $("#idChamada").val(data.id);

                                                },
                                                error: function (data) {
                                                    console.log(data);

                                                }
                                            });
                                            setTimeout(enviarChamadaPainel, 1000, url, toten_fila_id, medico_id, toten_sala_id);

                                        }

<? } ?>
</script>


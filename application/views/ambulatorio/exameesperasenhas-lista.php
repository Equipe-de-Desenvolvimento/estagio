
<div class="content"> <!-- Inicio da DIV content -->


    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Senhas</a></h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $medicos = $this->operador_m->listarmedicos();
            ?>
            <table>
                <td>
                    <table>
                        <tr>
                            <td style="width: 120px;">
                                <div class="bt_link_new">
                                    <a href="#" id="botaochamar">
                                        Chamar Senha
                                    </a>
                                </div>
                            </td>
                            <td style="width: 120px;">
                                <div class="bt_link_new">
                                    <a href="#" id="botaoatender">
                                        Atender
                                    </a>
                                </div>
                            </td>  
                        </tr>
                        <tr>
                            <?$contador_td = 0;?>
                            <?if(count($setores) > 0){?>
                                <?foreach ($setores as $key => $value) {?>
                                    <td style="width: 120px;">
                                        <div class="bt_link_new" >
                                            <a href="#" id="botaochamarunico" onclick="chamarSetor(<?=$value->id;?>);">
                                            <?=str_replace('-', '', $value->nome);?>  Qt: <?=$value->contador?>
                                            </a>
                                        </div>
                                    </td> 
                                    <?
                                    $contador_td++;
                                    if($contador_td > 5){
                                        // Fecha o tr e abre de novo pra poder criar uma nova linha caso tenha mais de 5 botões
                                        echo '</tr><tr>';
                                        $contador_td = 0;
                                    }
                                    ?>
                                <?}?>

                            <?}?>

                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                            <tr>
                                <th class="tabela_header">Senhas já retiradas</th>
                            </tr> 
                            <?foreach ($setores as $key => $value) {?>
                                <tr>
                                    <td class="tabela_content01"><?=str_replace('-', '', $value->nome);?>: <?=$value->contadorTotal?></td>
                                </tr> 
                            <?}?>
                           
                    </table>
                </td>
                <?php if($permissao[0]->hora_agendamento == "t"){?>
                <tr>
                    <td >
                        <label>  Associar médico</label>
                      <br>
                      <select name="medico_id" id="medico_id">
                           <option value="0"></option>
                        <?php foreach($operadorpoltrona as $item){?>
                           <option value="<?= $item->operador_id; ?>"><?= $item->nome; ?></option>
                        <?php }?>
                        </select> 
                    </td> 
                </tr>
                <?php }?>
            </table>
           
            
            <form id="formIdFila">
                <input type="hidden" name="IdFila" id="IdFila">
                <input type="hidden" name="SenhaFila" id="SenhaFila">
            </form>
            <table>
                <tr>
                    <td>
                        
                   
            <table>
                <thead>
                    <tr>
                        <th class="tabela_title">Pendentes</th>
                    </tr>
                    <tr> 
                    </tr> 
                    <tr>
                        <th class="tabela_header">ID</th>
                        <th class="tabela_header">Data</th>
                        <th class="tabela_header">Senha</th>
                        <th class="tabela_header">Espera</th>
                        <!--<th class="tabela_header"><center>A&ccedil;&otilde;es</center></th>-->
                    </tr>
                </thead>
                <?php
                $operador_id = $this->session->userdata('operador_id');
                if (count($senhas) > 0) {
                    ?>
                    <tbody>
                        <?php
                        $perfil_id = $this->session->userdata('perfil_id');
                        
                        $estilo_linha = "tabela_content01";
                        foreach ($senhas as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            $horarioObj = $this->operador_m->listardatahorariosenha($item->id);
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $horarioObj[0]->data;
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $espera = $diff->format('%H:%I:%S'); 
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->id; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y H:i:s",strtotime($dataAtual)); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->senha ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $espera ?></td>
                            </tr>

                    
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="4">
                                Total de registros: <?php echo count($senhas); ?>
                            </th>
                        </tr>
                    </tfoot>
               
            <? }
            ?> 
            </table>
               </td>
                              <?php if($permissao[0]->solicitacaotempo != ""){ ?>
           <td>
                <table>
                    <thead>
                        <tr>
                            <th class="tabela_title" colspan="2">Solicitações de Exame</th>
                        </tr>
                        <tr>
                            <th class="tabela_header" style="width: 100px;">Paciente</th>
                            <th class="tabela_header" colspan="2"><center>Ações</center></th>
                        </tr>
                        <?php 
                           $endereco = $empresa[0]->endereco_toten;                       
                           $estilo_linha = "tabela_content01";
                        foreach($solicitacoes as $item){                      
                            $tempo = gmdate('H:i:s', abs( strtotime( $item->data_cadastro ) - strtotime( date('H:i:s') ) ) );                        
                            if(!(strtotime($tempo) >= strtotime($permissao[0]->solicitacaotempo.":00"))){
                                continue;
                            }
                          ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                          if ($item->paciente != '') {
                                if ($item->cpf != '') {
                                    $cpf = $item->cpf;
                                } else {
                                    $cpf = 'null';
                                }
                                if ($item->toten_fila_id != '') {
                                    $toten_fila_id = $item->toten_fila_id;
                                } else {
                                    $toten_fila_id = 'null';
                                }
                                if ($item->toten_sala_id != '') {
                                    $toten_sala_id = $item->toten_sala_id;
                                } else {
                                    $toten_sala_id = 'null';
                                }
                                $url_enviar_ficha = "$endereco/webService/telaAtendimento/enviarFicha/$toten_fila_id/$item->paciente/$cpf/$item->medico_consulta_id/$item->medicoconsulta/$toten_sala_id/false";
                            } else {
                                $url_enviar_ficha = '';
                            }
                            
                            ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->paciente; ?></td>
                             <td class="<?php echo $estilo_linha; ?>" width="70px;" colspan="">
                                <? if ($endereco != '') { ?>
                                        <div class="bt_link">
                                            <a onclick="chamarPaciente('<?= $url_enviar_ficha ?>', <?= $toten_fila_id ?>, <?= $item->medico_consulta_id ?>, <?= $toten_sala_id ?>);" >Chamar</a>
                                        </div>   
                                <? } else { ?>
                                        <div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpacientesalaespera/<?= $item->agenda_exames_id ?>');" >Chamar</a>
                                        </div> 
                                <? } ?>
                           </td>
                           <td class="<?php echo $estilo_linha; ?>" width="70px;" >
                               <div class="bt_link">
                                   <a href="<?= base_url() ?>ambulatorio/laudo/excluirsolicitacaochamar/<?= $item->solicitacao_exame_chamar_id; ?>" target="_blank">Excluir</a> 
                               </div>
                           </td>
                        </tr>
                        <?
                        }
                        ?>
                        
                    </thead>
                </table>
           </td>
           <?php }?>
                </tr>
                
            </table>
            <br><br>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
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

    function chamarSetor(setor_id) {
        
        $.ajax({
            type: "POST",
            data: {teste: 'teste'},
            //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
            url: "<?= $endereco ?>/webService/telaAtendimento/proximo/"+ setor_id +"/Guichê <?= $guiche ?>/true/false/<?= $operador_id ?>/1",
            success: function (data) {
//                alert('asdsadsd');
//                console.log(data);
//                console.log(data.filaDeEspera.data);
                alert('Senha chamada: ' + data.filaDeEspera.senha + '');
                $('#IdFila').val(data.filaDeEspera.id);
                $('#SenhaFila').val(data.filaDeEspera.senha);
                gravarSenha(data.filaDeEspera.senha, data.filaDeEspera.id, data.filaDeEspera.data);
                
            },
            error: function (data) {
//                console.log(data);

            }
        });
    }

    $("#botaochamar").click(function () {
    //    alert('<?= $endereco ?>/webService/telaAtendimento/proximo/<?= $setor_string ?>/Guiche <?= $guiche ?>/true/false/<?= $operador_id ?>/1');
        $.ajax({
            type: "POST",
            data: {teste: 'teste'},
            //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
            url: "<?= $endereco ?>/webService/telaAtendimento/proximo/<?= $setor_string ?>/Guichê <?= $guiche ?>/true/false/<?= $operador_id ?>/1",
            success: function (data) {
//                alert('asdsadsd');
//                console.log(data);
//                console.log(data.filaDeEspera.data);
                alert('Senha chamada: ' + data.filaDeEspera.senha + '');
                $('#IdFila').val(data.filaDeEspera.id);
                $('#SenhaFila').val(data.filaDeEspera.senha);
                gravarSenha(data.filaDeEspera.senha, data.filaDeEspera.id, data.filaDeEspera.data);
                
            },
            error: function (data) {
//                console.log(data);

            }
        });
    });
    
    $("#botaoatender").click(function () {
        var idFila = $('#IdFila').val();
        var SenhaFila = $('#SenhaFila').val();
        var medico_id = $("#medico_id").val();
        //alert(idFila);
        $.ajax({
            type: "POST",
            data: {teste: 'teste'},
            //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
            url: "<?= $endereco ?>/webService/telaAtendimento/cancelar/" + idFila +"" ,
            success: function () {
             //   alert('Senha ' + SenhaFila + ' atendida com sucesso');
                atenderSenha(idFila);
                if(medico_id == 0){
                    alert('Senha ' + SenhaFila + ' atendida com sucesso');
                    window.location.reload();
                }else{
                    gravarmedico(medico_id, idFila, SenhaFila)
                }
               // window.location.reload();
            },
            error: function (data) {
//                console.log(data);
                alert('Chame uma senha antes de atendê-la');
            }
        });
    });

    function gravarmedico(medico_id, id, SenhaFila){
        $.ajax({
            type: "POST",
            data: {
                id: id,
                medico_id:medico_id,
            
            },
            url: "<?= base_url() ?>autocomplete/gravaridmedicototem",
            success: function (data) {   
                alert('Senha ' + SenhaFila + ' atendida com sucesso');   
                window.location.reload();      
            },
            error: function (data) {
                // console.log(data);
                alert('Chame uma senha antes de atendê-la');
            }
        });
    }
  
    function gravarSenha(senha, id, data){
        var medico_id = $("#medico_id").val(); 
        $.ajax({
            type: "POST",
            data: {
                senha: senha,
                id: id,
                data: data,
                medico_id:medico_id,
            
            },
            //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
            url: "<?= base_url() ?>autocomplete/gravarsenhatoten",
            success: function (data) {
//                alert('asdsadsd');
//                console.log(data);
//                console.log(data.filaDeEspera.senha);
                alert('Senha chamada: ' + data.filaDeEspera.senha + '');
                
            },
            error: function (data) {
                console.log(data);
            }
        });
    
    }
    
    function atenderSenha(id){
       
        $.ajax({
            type: "POST",
            data: {
                id: id
            },
            //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
            url: "<?= base_url() ?>autocomplete/atendersenhatoten",
            success: function (data) {
//                  alert('asdsadsd');
//                console.log(data);
//                console.log(data.filaDeEspera.senha);
//                alert('Senha chamada: ' + data.filaDeEspera.senha + '');
                
            },
            error: function (data) {
//                console.log(data);

            }
        });
    
    }


</script>

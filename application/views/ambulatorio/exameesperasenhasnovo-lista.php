
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Senhas</a></h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $medicos = $this->operador_m->listarmedicos();
            ?>
            <table>
                <tr>
                    <td style="width: 80px;">
                        <div class="bt_link_new">
                            <a href="#" id="botaochamar" onclick="chamarSenha(<?= $guiche ?>);">
                                Chamar Senha
                            </a>
                        </div>
                    </td>
                    <td style="width: 80px;">
                        <div class="bt_link_new">
                            <a href="#" id="botaoatender">
                                Cancelar Senha Atual
                            </a>
                        </div>
                    </td>  
                </tr>
                
            </table>
            <form id="formIdFila">
                <input type="hidden" name="IdFila" id="IdFila">
                <input type="hidden" name="SenhaFila" id="SenhaFila">
            </form>
            <table>
                 <tr>
                    <td>
                    <table >
                        <thead>
                            <tr>
                                <th class="tabela_title">Pendentes</th>
                            </tr>

                            <tr>
                                <th class="tabela_header">Senha</th>
                                <th class="tabela_header">Descrição</th>
                            </tr>


                        </thead>
                <tbody>
                    <?php
                    $perfil_id = $this->session->userdata('perfil_id');
                    
                    $estilo_linha = "tabela_content01";
                    if(count($senhas) > 0){
                    foreach ($senhas as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->prefixo . " " . $item->numero; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->descricao ?></td>

                        </tr>

                
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                <?php
                $operador_id = $this->session->userdata('operador_id');
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
                            <th class="tabela_header" >Paciente</th>
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
            
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>    

<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css"> 
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

    function chamarSenha(guiche_id){
        $.getJSON('<?=base_url()?>autocomplete/chamarSenhas/' + guiche_id, function (j) {
            alert('Senha chamada ' + j.tipo_senha.descricao + ' ' + j.numero);
        });
    }
    
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


<script>
 
function envio() {
    
var r = confirm("Tem certeza que deseja enviar TODOS?");
   if (r == true) {
     todosenviar.submit(); 
   }
 
}
</script>
 

<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Sala de Espera</a></h3>
        <div>
            <?
            $salas = $this->exame->listartodassalas();
            $empresa = $this->guia->listarempresasaladeespera();
            @$ordem_chegada = @$empresa[0]->ordem_chegada;
            @$administrador_cancelar = @$empresa[0]->administrador_cancelar;
            @$gerente_recepcao_cancelar = @$empresa[0]->gerente_cancelar_sala;
            $medicos = $this->operador_m->listarmedicos();
            $situacaocaixa = $this->exame->listarcaixaempresa();
            $data['empresaPermissao'] = $this->guia->listarempresapermissoes();

//            var_dump($situacaocaixa);
//            die;
            ?>
            
            
            
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="tabela_title">Salas</th>
                        <th class="tabela_title">Medico</th>
                        <th class="tabela_title">Tipo</th>
                        <th class="tabela_title">Nome</th>
                    </tr>
                    <tr>
                <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarsalasespera">
                    <th colspan="2" class="tabela_title">
                        <select name="sala" id="sala" class="size1">
                            <option value="">TODAS</option>
                            <? foreach ($salas as $value) : ?>
                                <option value="<?= $value->exame_sala_id; ?>" <?
                                if (@$_GET['sala'] == $value->exame_sala_id):echo 'selected';
                                endif;
                                ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </th>
                    <th class="tabela_title">
                        <select name="medico" id="medico" class="size1">
                            <option value=""></option>
                            <? foreach ($medicos as $value) : ?>
                                <option value="<?= $value->operador_id; ?>" <?
                                if (@$_GET['medico'] == $value->operador_id):echo 'selected';
                                endif;
                                ?>><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                        </select>
                    </th>
                    <th class="tabela_title">
                        <select name="tipo" id="tipo" class="size1" >
                            <option value='' ></option>
                            <option value='EXAME' <?
                            if (@$_GET['tipo'] == 'EXAME'):echo 'selected';
                            endif;
                            ?> >EXAME</option>
                            <option value='CONSULTA'  <?
                            if (@$_GET['tipo'] == 'CONSULTA'):echo 'selected';
                            endif;
                            ?>>CONSULTA</option>
                        </select>
                    </th>
                    <th colspan="3" class="tabela_title">
                        <input type="text" name="nome" class="texto07" value="<?php echo @$_GET['nome']; ?>" />
                    </th>
                    <th class="tabela_title">
                        <button type="submit" id="enviar">Pesquisar</button>
                    </th>
                </form>
                </th>
                </tr>
                <tr>
                    <?php 
                     if ($data['empresaPermissao'][0]->espera_intercalada == 't') { 
                         ?>
                   <th class="tabela_header">Posição</th>
                    <?
                     }
                    ?>
                    <th class="tabela_header">Ordem</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Idade</th>
                    <?  if ($data['empresaPermissao'][0]->data_hora_sala_espera == 't') { ?>
                        <th class="tabela_header">Data Autorização</th>
                    <?} else {?>
                        <th class="tabela_header">Tempo</th>
                    <?}?>
                    <!-- <th class="tabela_header">Tempo</th> -->
                    <th class="tabela_header">Agenda</th>
                    <th class="tabela_header">Sala</th>
                    <th class="tabela_header">Procedimento</th>              
                    <th class="tabela_header">Médico</th>              
                    <th class="tabela_header">Obs.</th>

                    <th class="tabela_header" colspan="4"><center>A&ccedil;&otilde;es

                     

                  

                </center>  
              <th class="tabela_header" colspan="0"> 
  
              <?
                    if (@$data['empresaPermissao'][0]->enviar_para_atendimento == 't') {
                        
                        ?>
                  <form action="<?= base_url()?>ambulatorio/exame/enviartodosparaatendimento" name="todosenviar" method="post" target="_blank"> 
                        <?
                        $lista2 = $this->exame->listarexameagendaconfirmada2($_GET, @$ordem_chegada)->get()->result();
                         foreach($lista2 as $atendi){
                        ?>
                        
                        <input type="hidden" name="atendimentos[]" value="<?= $atendi->agenda_exames_id; ?>" >
                        
                        <?
                         }
                        ?>
                        
                        <input type="button" value="Todos" onclick="envio()">
                        
                    </form>
                        <? }
                    ?>
                  
                   </th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $limit = $limite_paginacao;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                $consulta = $this->exame->listarexameagendaconfirmada($_GET);
                $total = $consulta->count_all_results();
                $lista = $this->exame->listarexameagendaconfirmada2($_GET, @$ordem_chegada)->limit($limit, $pagina)->get()->result();
                // echo '<pre>';
                // print_r($lista);
                // die;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $perfil_id = $this->session->userdata('perfil_id');
                        $operador_id = $this->session->userdata('operador_id');
                        $estilo_linha = "tabela_content01";  
                        
                        if ($data['empresaPermissao'][0]->espera_intercalada == 't') { 
                            $array = json_decode(json_encode($lista), true);
                            $lista_nova = Array(); 
                            $par = 0;
                            $impa = 1;
                            $i = 0; 
                            foreach($array as $ch => $value) { 
                                      if ($value['ordenador'] == 3  && $i == 0) {
                                              $lista_nova[($par)] = $value;
                                              $par = $par + 2;  
                                              $i = 2;
                                              continue;
                                        }
                                        if ($value['ordenador'] == 3  && $i == 2) { 
                                            $lista_nova[($impa)] = $value;
                                            $impa = $impa + 2; 
                                            
                                            $i = 0; 
                                        } 
                                    } 
                                foreach($array as $ch => $value) { 
                                      if($value['ordenador'] % 2 != 0  && $value['ordenador'] != 3) {  
                                           $lista_nova[($impa)] = $value;
                                           $impa = $impa + 2;
                                      }elseif($value['ordenador'] % 2 == 0 && $value['ordenador'] != 3  ){  
                                           $lista_nova[($par)] = $value;
                                           $par = $par + 2; 
                                      }  
                                }
                              
                                ksort($lista_nova); 
                                $lista= json_decode(json_encode($lista_nova)); 
                              }
 
                   
                         $j = 1;
                         
                        foreach ($lista as $item) {
                            date_default_timezone_set('America/Fortaleza');
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_autorizacao;
                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%H:%I:%S');
                            
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
 
                            if ($item->ordenador == 1) {
                                $ordenador = 'Normal';
                                $cor = 'blue';
                            } elseif ($item->ordenador == 2) {
                                $ordenador = 'Prioridade';
                                $cor = 'darkorange';
                            } elseif ($item->ordenador == 3) {
                                $ordenador = 'Urgência';
                                $cor = 'red';
                            } else {
                                $ordenador = $item->ordenador;
                            }
                            
                            
                            ?>
                            <tr>
                                 <?php 
                     if ($data['empresaPermissao'][0]->espera_intercalada == 't') { 
                         ?>
                          <td   style="color: <?= @$cor ?>" class="<?php echo $estilo_linha; ?>"><?= $j++; ?></td>
                    <?
                     }
                    ?>
                                <td style="color: <?= @$cor ?>" class="<?php echo $estilo_linha; ?>"><?= $ordenador; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/examepacientedetalhes/<?= $item->paciente_id; ?>/<?= $item->procedimento_tuss_id; ?>/<?= $item->guia_id; ?>/<?= $item->agenda_exames_id; ?>', 'toolbar=no,Location=no,menubar=no,width=500,height=200');"><?= $item->paciente; ?></a></td>
                                <?
                                $idade = date("Y-m-d") - $item->nascimento;
                                ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $idade; ?></td>

                                <?
                                    if ($data['empresaPermissao'][0]->data_hora_sala_espera == 't') {
                                ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?=substr($item->data_autorizacao, 8, 2) . "/" . substr($item->data_autorizacao, 5, 2) . "/" . substr($item->data_autorizacao, 0, 4)?> <br> <?=substr($item->data_autorizacao, 11,8)?></td>
                                <?} else{?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $teste; ?></td>
                                <?}?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->inicio; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?
                                    echo $item->sala;
                                    if (isset($item->numero_sessao)) {
                                        echo "<br> SESSAO: " . $item->numero_sessao . "/" . $item->qtde_sessao;
                                    }
                                    ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                                <td class="<?php echo $estilo_linha; ?>">
                                    <?
                                    $nome = explode(" ", $item->medico);
                                    ?>
                                    <span style="cursor: pointer;" title="<?= $item->medico; ?>"><?= @$nome[0]; ?>...</span>
                                </td>

                                <td class="<?php echo $estilo_linha; ?>"><font color="red" title="<?=$item->observacoes?>"><b><?= substr($item->observacoes, 0, 30).' ...'; ?></b></td>
                                <? if ($situacaocaixa[0]->caixa == 't') { ?>
            <? if ($item->dinheiro == 'f') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/examesala/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Enviar
                                                </a></div>
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/exame/examesalatodos/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id ?>">Todos

                                                </a></div>
                                        </td>
            <? } elseif ($item->faturado == 't') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/examesala/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Enviar

                                                </a></div>
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/exame/examesalatodos/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id ?>">Todos

                                                </a></div>
                                        </td>
            <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                        </td>
                                    <? } ?>
        <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/examesala/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id; ?> ', 'toolbar=no,Location=no,menubar=no,width=500,height=200');">Enviar
                                            </a></div>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a href="<?= base_url() ?>ambulatorio/exame/examesalatodos/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>/<?= $item->guia_id ?>/<?= $item->agenda_exames_id ?>">Todos

                                            </a></div>
                                    </td>
        <? } ?>
        <!--                                <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                        <a href="<?= base_url() ?>ambulatorio/laudo/chamarpaciente2/<?= $item->ambulatorio_laudo_id ?> ">
                            Chamar</a></div>
                                                            impressaolaudo 
                </td>-->
                                <? if ($empresa[0]->cancelar_sala_espera == 't') { ?>
            <? if ($item->agrupador_pacote_id == '') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <!-- <a href="<?= base_url() ?>ambulatorio/exame/esperacancelamento/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>">Cancelar -->
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/esperacancelamento/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">Cancelar

                                                </a></div>
                                        </td>
            <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link_new">
                                                <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/esperacancelamentopacote/<?= $item->guia_id ?>/<?= $item->paciente_id ?>/<?= $item->agrupador_pacote_id ?>">Cancelar Pacote

                                                </a></div>
                                        </td>
                                    <? } ?>

                                <? } elseif ((@$administrador_cancelar == 't' && $perfil_id == 1) || $operador_id == 1 || (@$gerente_recepcao_cancelar == 't' && $perfil_id == 5)) { ?>
            <? if ($item->agrupador_pacote_id == '') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <!-- <a href="<?= base_url() ?>ambulatorio/exame/esperacancelamento/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>">Cancelar -->
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/esperacancelamento/<?= $item->agenda_exames_id ?>/<?= $item->paciente_id ?>/<?= $item->procedimento_tuss_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">Cancelar
                                                </a></div>
                                        </td>
            <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link_new">
                                                <a target="_blank" href="<?= base_url() ?>ambulatorio/exame/esperacancelamentopacote/<?= $item->guia_id ?>/<?= $item->paciente_id ?>/<?= $item->agrupador_pacote_id ?>">Cancelar Pacote

                                                </a></div>
                                        </td>
                                    <? } ?>
        <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                    </td> 
        <? } ?>

                                    <td class="<?php echo $estilo_linha; ?>" width="60px;" colspan="2"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/observacao/<?= $item->agenda_exames_id ?>/<?= $item->paciente; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=500,height=230');">Obs.
                                        </a></div>
                                </td>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="15">
<?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?> 
                            <div style="display: inline">
                                <span style="margin-left: 15px; color: white; font-weight: bolder;"> Limite: </span>
                                <select style="width: 57px">
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarsalasespera/25');" <?
                                    if ($limit == 25) {
                                        echo "selected";
                                    }
                                    ?>>25 </option>
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarsalasespera/50');" <?
                                    if ($limit == 50) {
                                        echo "selected";
                                    }
                                    ?>>50 </option>
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarsalasespera/100');" <?
                                    if ($limit == 100) {
                                        echo "selected";
                                    }
                                    ?>> 100 </option>
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>ambulatorio/exame/listarsalasespera/todos');" <?
                                    if ($limit == "todos") {
                                        echo "selected";
                                    }
                                    ?>> Todos </option>
                                </select>
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

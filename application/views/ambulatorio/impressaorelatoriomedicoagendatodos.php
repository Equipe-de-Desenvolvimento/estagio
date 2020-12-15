 
<meta charset="UTF-8">
<title>Relatório Agenda</title>
<div class="content"> <!-- Inicio da DIV content -->
    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Agenda Consultas</h4>
    <h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>
    <h4>Médico: <?
        if (count(@$medico) > 0 && $medico != null) {
            echo $medico[0]->operador;
        } else {
            echo 'TODOS';
        }
        ?></h4>

    <? if (count(@$_POST['operador']) > 0 && !in_array('0', @$_POST['operador'])) { ?>
        <h4>Operador: <?
            @$cont_virgula = count($operador);

            foreach ($operador as $value) {
                echo $value->nome;
                if (@$cont_virgula <= 1) {
                    
                } else {
                    @$cont_v++;
                    if (@$cont_v == @$cont_virgula) {
                        
                    } else {
                        echo ",";
                    }
                }
            }
            ?></h4>
    <? } ?>
    <? if (count(@$_POST['operador']) == 0 || in_array('0', @$_POST['operador'])) { ?>
        <h3>Operador: TODOS</h3>
    <? } ?>

    <h4>Convênio: <?= (@$convenios > 0) ? $convenios[0]->nome : 'TODOS'; ?></h4>
    <hr>

    <?
    @$empresa_id = $this->session->userdata('empresa_id');
    @$data['retorno'] = $this->exame->permisoesempresa($empresa_id);
    @$agenda_modificada = $data['retorno'][0]->agenda_modificada;
// var_dump($agenda_modificada); die;
    ?>

    <style>
        .right{
            text-align: right;
        }
        .background{
            background-color: #d3d3d3;
        }

    </style>
    <table border="1" cellspacing=0 cellpadding=2>

        <?php 
          $contmedicosturno = Array();
         foreach ($relatorio as $key => $item) {
                $hora = date("H:i", strtotime($item->inicio));
                $manha = date("H:i", strtotime("06:00"));
                $tarde = date("H:i", strtotime("12:00"));
                $noite = date("H:i", strtotime("18:00"));

                if ($hora >= $manha && $hora < $tarde) {
                    $turno = 'Manhã';
                } elseif ($hora >= $tarde && $hora < $noite) {
                    $turno = 'Tarde';
                } elseif ($hora >= $noite) {
                    $turno = 'Noite';
                }
              @$contmedicosturno[$item->medico_agenda.$item->medicoagenda][$turno]++; 
              
              
         }
        ?>
 <!-- <tbody> -->
        <?php
        $paciente = "";
        $mostrarHeader = false;
        $turno = '';
        $medico_atual = '';
        $turno_atual = '';
        $data_atual = '';
        $contador = 0; 
        $contmedicos = Array();
        $consolidado = Array();
                    
                    
        if (count($relatorio) > 0) {
            foreach ($relatorio as $key => $item) {
                @$contmedicos[$item->medico_agenda.$item->medicoagenda]++; 
                @$detalhes[$item->medico_agenda."grupo"][$item->grupo]++;    
//                if($item->telefonema ! =)
                if($item->telefonema == "t"){
                    $confirmado = "CONFIRMADA";
                }else{
                    $confirmado = "NÃO CONFIRMADA";
                }
                @$detalhes[$item->medico_agenda."status"][$confirmado]++;  
                
                $consolidado[$item->medico_agenda] = Array('cont'=> $contmedicos[$item->medico_agenda.$item->medicoagenda],'medico'=>$item->medicoagenda,'detalhes'=>$detalhes[$item->medico_agenda."grupo"],'status' => $detalhes[$item->medico_agenda."status"]); 
                

                $hora = date("H:i", strtotime($item->inicio));
                $manha = date("H:i", strtotime("06:00"));
                $tarde = date("H:i", strtotime("12:00"));
                $noite = date("H:i", strtotime("18:00"));

                if ($hora >= $manha && $hora < $tarde) {
                    $turno = 'Manhã';
                } elseif ($hora >= $tarde && $hora < $noite) {
                    $turno = 'Tarde';
                } elseif ($hora >= $noite) {
                    $turno = 'Noite';
                }
                
                    

                if (($medico_atual != $item->medico_agenda) || ($turno_atual != $turno)) {
                    $mostrarHeader = true;
                } else {
                    $mostrarHeader = false;
                }


                $turno_atual = $turno;
                $medico_atual = $item->medico_agenda;

                if ($_POST['medicos'] > 0) {
                    $mostrarHeader = false;
                }
                $contador++;
                if ($key == 0 || $mostrarHeader) {
                    ?>
                    <? if ($mostrarHeader) { ?>
                    </table>
                    <br>
                    <table border="1" cellspacing=0 cellpadding=2>
                        <thead>
 
                            <tr class="background">
                                <th colspan="7">
                                    Turno: <?= $turno ?> <? //=$contador                                              ?>
                                    <?
                                    $contador = 0;
                                    ?>
                                </th>
                                 <th colspan="2" class="right">
                                    Qtde Atendimento: <?= $contmedicosturno[$item->medico_agenda.$item->medicoagenda][$turno]; ?>
                                </th>
                                <th colspan="3" class="right">
                                    Médico: <?= $item->medicoagenda ?>
                                </th>
                            </tr>
                        <? } ?>
                        <tr>
                            <?
                            if ($permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="20px;">Nº</th>
                                <?
                            } else {

                                if (@$agenda_modificada == 't') {
                                    ?>
                                    <th class="tabela_header" width="20px;">Nº</th>
                                    <?
                                } else {
                                    ?>
                                    <th class="tabela_header" width="70px;">Status</th>
                                    <?
                                }
                            }
                            ?>

                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="250px;">Pront.</th> 
                                <?
                            }
                            ?>    

                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                @$nomeclatura = "Nome do Paciente";
                            } else {
                                @$nomeclatura = "Nome";
                            }
                            ?>
                            <th class="tabela_header" width="250px;"><?php echo @$nomeclatura; ?></th> 

                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="250px;">Data de Nascimento </th> 
                                <?
                            }
                            ?>
                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="250px;">Procedimento</th> 
                                <?
                            }
                            ?>

                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {
                                ?>
                                <th class="tabela_header" width="70px;">Resp.</th>

                                <?php
                            }
                            ?>
 
                            <th class="tabela_header">Telefone</th>
                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="70px;">Cartão SUS</th>
                                <?
                            }
                            ?>
                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                ?>
                                <th class="tabela_header" width="70px;">Endereço</th>
                                <?
                            }
                            ?>

                            <? if (@$_POST['data_mostrar'] == 'SIM') { ?>
                                <th class="tabela_header" width="70px;">Data</th>
                            <? } ?>
                            <?
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {

                                if (@$agenda_modificada == 't') {
                                    
                                } else {
                                    ?>
                                    <th class="tabela_header" width="70px;">Dia</th>

                                    <?
                                }
                            }
                            ?>
                            <?
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {

                                if (@$data['retorno'][0]->agenda_modificada == 't') {
                                    ?>
                                    <th class="tabela_header" width="70px;">Horário</th>
                                    <?
                                } else {
                                    ?>
                                    <th class="tabela_header" width="70px;">Agenda</th>   
                                    <?
                                }
                            }
                            ?>
                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {
                                ?>
                                <th class="tabela_header" width="150px;">Médico</th>
                                <?php
                            }
                            ?>
                            <?
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {
                                if (@$data['retorno'][0]->agenda_modificada == 't') {
                                    
                                } else {
                                    if (@$_POST['procedimento'] == 'SIM') {
                                        ?>
                                        <th class="tabela_header" width="150px;">Procedimentos</th>
                                        <?
                                    }
                                }
                            }
                            ?>

                            <?php
                            if (@$permissaoempresa[0]->agendahias == "t") {
                                
                            } else {
                                ?>
                                <th class="tabela_header" width="150px;">Convênio</th>

                                <th class="tabela_header"><center>Status Telefonema</center></th>
                        <th class="tabela_header" width="250px;">Observa&ccedil;&otilde;es</th>
                        <?php
                    }
                    ?>

                    </tr>
                    </thead>


                    <?
                }
                $dataFuturo = date("Y-m-d H:i:s");
                $dataAtual = $item->data_atualizacao;

                if ($item->celular != "") {
                    $telefone = $item->celular;
                } elseif ($item->telefone != "") {
                    $telefone = $item->telefone;
                } else {
                    $telefone = "";
                }

                $date_time = new DateTime($dataAtual);
                $diff = $date_time->diff(new DateTime($dataFuturo));
                $teste = $diff->format('%H:%I:%S');

                if ($item->paciente == "" && $item->bloqueado == 't') {
                    $situacao = "Bloqueado";
                    $paciente = "Bloqueado";
                    $verifica = 5;
                } else {
                    $paciente = "";

                    if ($item->realizada == 't' && $item->situacaolaudo != 'FINALIZADO') {
                        $situacao = "Aguardando";
                        $verifica = 2;
                    } elseif ($item->realizada == 't' && $item->situacaolaudo == 'FINALIZADO') {
                        $situacao = "Finalizado";
                        $verifica = 4;
                    } elseif ($item->confirmado == 'f') {
                        if ($item->encaixe == 't') {
                            $situacao = "Encaixe";
                        } else {
                            $situacao = "Agenda";
                        }

                        $verifica = 1;
                    } else {
                        $situacao = "Espera";
                        $verifica = 3;
                    }
                }
                if ($item->paciente == "" && $item->bloqueado == 'f') {
                    $paciente = "vago";
                }
                $data = $item->data;
                $dia = strftime("%A", strtotime($data));

                switch ($dia) {
                    case"Sunday": $dia = "Domingo";
                        break;
                    case"Monday": $dia = "Segunda";
                        break;
                    case"Tuesday": $dia = "Terça";
                        break;
                    case"Wednesday": $dia = "Quarta";
                        break;
                    case"Thursday": $dia = "Quinta";
                        break;
                    case"Friday": $dia = "Sexta";
                        break;
                    case"Saturday": $dia = "Sabado";
                        break;
                }
                ?>
                <tr>
                    <td >
                        <b> 


                            <?
                            if ($permissaoempresa[0]->agendahias == "t") {
                                echo @$numero = 1 + $numero;
                            } else {
                                ?>                            
                                <?
                                if (@$agenda_modificada == 't') {
                                    echo @$numero = 1 + $numero;
                                } else {
                                    echo @$situacao;
                                }
                            }
                            ?>
                        </b>


                    </td>
                    <?php
                    if ($permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <td > <b  style="margin:40%;" ><?= $item->paciente_id; ?></b></td>
                        <?
                    }
                    ?>

                    <td> <b><?= $item->paciente; ?></b></td>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <td><?= substr($item->data_nascimento, 8, 2) . "/" . substr($item->data_nascimento, 5, 2) . "/" . substr($item->data_nascimento, 0, 4); ?></td>
                        <?
                    }
                    ?>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <td ><?= @$item->procedimento; ?></td>
                        <?
                    }
                    ?>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <td ><?= substr($item->secretaria, 0, 9); ?></td> 
                    <?php }
                    ?>                    

                    <td ><?= $telefone; ?></td>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <td ><?= @$item->convenionumero; ?></td> 
                        <?
                    }
                    ?>


                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <td ><?= @$item->logradouro; ?> <?php
                            if ($item->cep != "") {
                                echo " - " . $item->cep;
                            }
                            ?></td> 
                        <?
                    }
                    ?>    


                    <?
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        if (@$_POST['data_mostrar'] == 'SIM') {
                            ?>
                            <td><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                            <?
                        }
                    }
                    ?>


                    <?
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        if (@$agenda_modificada == 't') {
                            
                        } else {
                            ?>
                            <?
                            echo "<td>";
                            echo substr($dia, 0, 3);
                            echo " </td>";
                        }
                    }
                    ?> 

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>   
                        <td ><?= $item->inicio; ?></td>

                        <?php
                    }
                    ?>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <td  width="150px;">
                            <?
                            if (@!($agenda_modificada == 't')) {
                                  if (@$_POST['sala'] == 'SIM') {
                                    echo $item->sala . " - ";
                                }
                            }  
                            echo substr($item->medicoagenda, 0, 15);
                            ?></td>
                    <?php }
                    ?>



                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <?
                        if (@$agenda_modificada == 't') {
                            
                        } else {
                            ?>      
                            <? if (@$_POST['procedimento'] == 'SIM') {
                                ?>
                                <td ><?= $item->procedimento; ?></td>  
                                <?
                            }
                        }
                        ?>
                    <?php }
                    ?>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <td ><?= $item->convenio; ?></td>

                        <?php
                    }
                    ?>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <? if ($item->bloqueado == 't') { ?>
                            <td width="60px;"> Bloqueado</td>
                            <?
                        } elseif ($item->telefonema == 't') {
                            ?>
                            <td width="60px;">Confirmado</td>
                        <? } else { ?>
                            <td width="60px;">Não-Confirmado</td>
                        <? }
                        ?>
                        <td ><?= $item->observacoes; ?></td>

                        <?php
                    }
                    ?>

                </tr>


                <?php
                @$contaragenda{$item->operador_atualizacao} ++;
            }
        } else {
            echo "<h4>Não há resultados para esta consulta.</h4>";
        }
        ?>
        <!-- </tbody> -->

    </table>

</div> <!-- Final da DIV content -->
 
<br>


<?php  if (!(count(@$medico) > 0 && $medico != null)) {?>




<table border="1" cellspacing=0 cellpadding=2>
    <thead>
        <tr>
            <th colspan="4">Consolidado</th>
        </tr>
     <tr>
        <th>PROFISSIONAL</th>
        <th>QTEDE</th>
        <th>DETALHE</th>
        <th>STATUS</th>
     </tr>
    </thead>
    <?php foreach($consolidado as $key => $item){ ?>
      <tr>
        <td><?= $item['medico']; ?></td>
        <td><?= $item['cont']; ?></td>
        <td><? 
        $contbarra = 1;
        foreach($item['detalhes'] as $key2 => $value){
            $contbarra++;
            if(count($item['detalhes']) >=  $contbarra ){ 
               echo $value." ".$key2." / ";  
            }else{
                echo $value." ".$key2; 
            } 
            
        } 
         ?></td>
        <td><? 
        $contbarra2 = 1;
        foreach($item['status'] as $key2 => $value){
            $contbarra2++;
            if(count($item['status']) >=  $contbarra2 ){ 
               echo $value." ".$key2." / ";  
            }else{
                echo $value." ".$key2; 
            } 
            
        } 
         ?></td>
      </tr> 
    <? } ?>
</table>
<?}?>

<?
if (count($relatorio) > 0) {

    if (count(@$_POST['operador']) == 0 || in_array('0', @$_POST['operador'])) {
        ?>

    <? } else { ?> 
        <br><br> 
        <table border="1" cellspacing=0 cellpadding=2>
            <tr class="background">
                <td>Nome</td>
                <td>Quantidade</td>
            </tr>
            <?php
                    
            foreach ($relatorio as $item) {
                if (@$verificaat{$item->secretaria}{$item->operador_atualizacao} >= 1) {
                    
                } else {
                    ?>
                    <tr>
                        <td><?= @$item->secretaria ?></td>
                        <td><?= @$contaragenda{$item->operador_atualizacao}; ?></td>
                    </tr> 
                    <?
                    @$verificaat{$item->secretaria}{$item->operador_atualizacao} ++;
                }
            }
            ?>
        </table>
        <?
    }
}
?>


<br><br> 


<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>

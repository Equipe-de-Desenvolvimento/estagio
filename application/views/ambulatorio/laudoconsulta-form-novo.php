<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />


<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --> 

<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/jquery.tinymce.min.js"></script> -->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> -->


<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script> -->

<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/langs/pt_BR.js"></script> -->


<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/plugins/spellchecker/plugin.min.js"></script> -->


<script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<!-- <script src="https://cdn.tiny.cloud/1/3q8vm8ad5imn28j1rprx3ye865hgkid7c78mxua4g4dup4r3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->


<!-- <script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/themes/modern/theme.min.js"></script> -->
<!--<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/tinymce/jquery.tinymce.min.js"></script>-->
<!--<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/tinymce/tinymce.min.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>

<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<style>
    
    .btn_verde_3{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 260px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_rosa {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 135px;
  height: 35px;
  background-color: #DC143C;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 135px;
  height: 35px;
  background-color: #0066b8;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_verde_2{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 220px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}
.destaque{
    color: red;
    font-size: 30px;
    font-weight: bold;
    text-decoration: underline red;
}

</style>
<head>
    <title>Laudo Consulta</title>
</head>
<div> 
    <? 
$informacao_exemplo = "Exemplo:  
Paciente com alergia a Dipirona...  
Paciente com Diabete...
Paciente com escoliose...";
                                
                                
    if (@$obj->_cabecalho == "") {
        $cabecalho = @$obj->_procedimento;
    } else {
        $cabecalho = @$obj->_cabecalho;
    }
                                
    $perfil_id = $this->session->userdata('perfil_id');
                                
                                
    $dataFuturo = date("Y-m-d");
    $dataAtual = @$obj->_nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');
//    var_dump(isset($obj->_peso), isset($obj->_altura)); die;
    if (isset($obj->_peso)) {
        $peso = @$obj->_peso;
    } else {
        $peso = @$laudo_peso[0]->peso;
    }
    if (isset($obj->_altura)) {
        $altura = @$obj->_altura;
    } else {
        $altura = @$laudo_peso[0]->altura;
    }
    if (isset($obj->_pulso)) {
        $pulso = @$obj->_pulso;
    } else {
        $pulso = @$laudo_peso[0]->pulso;
    }
    if (isset($obj->_temperatura)) {
        $temperatura = @$obj->_temperatura;
    } else {
        $temperatura = @$laudo_peso[0]->temperatura;
    }
    if (isset($obj->_pressao_arterial)) {
        $pressao_arterial = @$obj->_pressao_arterial;
    } else {
        $pressao_arterial = @$laudo_peso[0]->pressao_arterial;
    }
    if (isset($obj->_f_respiratoria)) {
        $f_respiratoria = @$obj->_f_respiratoria;
    } else {
        $f_respiratoria = @$laudo_peso[0]->f_respiratoria;
    }
    if (isset($obj->_spo2)) {
        $spo2 = @$obj->_spo2;
    } else {
        $spo2 = @$laudo_peso[0]->spo2;
    }


    if ($obj->_hipertensao != '') {
        $hipertensao = @$obj->_hipertensao;
    } else {
        $hipertensao = @$laudo_peso[0]->hipertensao;
    }

    if ($obj->_diabetes != '') {
        $diabetes = @$obj->_diabetes;
    } else {
        $diabetes = @$laudo_peso[0]->diabetes;
    }

    if ($obj->_medicacao != '') {
        $medicacao = @$obj->_medicacao;
    } else {
        $medicacao = @$laudo_peso[0]->medicacao;
    }


    if (@$empresapermissao[0]->campos_atendimentomed != '') {
        $opc_telatendimento = json_decode(@$empresapermissao[0]->campos_atendimentomed);
    } else {
        $opc_telatendimento = array();
    }
//    var_dump($empresapermissao[0]->dados_atendimentomed); die;
    if (@$empresapermissao[0]->dados_atendimentomed != '') {
        $opc_dadospaciente = json_decode(@$empresapermissao[0]->dados_atendimentomed);
//        echo "<pre>";
//        print_r( $opc_dadospaciente );
    } else {
        $opc_dadospaciente = array();
    }



    $operador_id = $this->session->userdata('operador_id');
    if (@$obj->_status == 'FINALIZADO' && $laudo_sigiloso == 't' && $operador_id != 1) {
        $readonly = 1;
    } else {
        $readonly = 0;
    }
    if (@$obj->_status == 'FINALIZADO' && $laudo_sigiloso == 't') {
        $adendo = true;
        $readonlyadendo = 0;
    } else {
        $adendo = false;
          $readonlyadendo = 1;
    }
    if (@$obj->_estado_civil == 1):$estado_civil = 'Solteiro';
    endif;
    if (@$obj->_estado_civil == 2):$estado_civil = 'Casado';
    endif;
    if (@$obj->_estado_civil == 3):$estado_civil = 'Divorciado';
    endif;
    if (@$obj->_estado_civil == 4):$estado_civil = 'Viuvo';
    endif;
    if (@$obj->_estado_civil == 5):$estado_civil = 'Outros';
    endif;
//    var_dump($laudo_sigiloso); die;
    ?>

    <div >
        <form name="form_laudo" id="form_laudo" enctype="multipart/form-data" action="<?= base_url() ?>ambulatorio/laudo/gravaranaminese/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>" method="POST">
            <div >
                <input type="hidden" name="guia_id" id="guia_id" class="texto01"  value="<?= @$obj->_guia_id; ?>"/>
                <fieldset>
                    <legend>Dados</legend>

                    <!-- <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                <label>Paciente: </label> <?= @$obj->_nome ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('cpf', $opc_dadospaciente)) { ?>
                                <label>CPF: </label> <?= @$obj->_cpf ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('nascimento', $opc_dadospaciente)) { ?>
                         <label>Nascimento: </label><?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('idade', $opc_dadospaciente)) { ?>
                        <label>Idade: </label><?= $teste ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('sexo', $opc_dadospaciente)) { ?>
                        <label>Sexo: </label><?= @$obj->_sexo ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('convenio', $opc_dadospaciente)) { ?>
                        <label>Convenio:  </label><?= @$obj->_convenio; ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                        <label> Telefone:  </label><?= @$obj->_telefone ?> &nbsp;&nbsp;&nbsp;
                    <? } ?> 
                    <br>
                    <? if (in_array('celular', $opc_dadospaciente)) { ?>
                        <label> Telefone 2:  </label><?= @$obj->_celular ?> &nbsp;&nbsp;&nbsp;
                    <? } ?> 

                    <? if (in_array('ocupacao', $opc_dadospaciente)) { ?>
                        <label> Ocupação:  </label><?= @$obj->_profissao_cbo ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('estadocivil', $opc_dadospaciente)) { ?>
                        <label> Estado Civíl:  </label><?= @$estado_civil ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('sala', $opc_dadospaciente)) { ?>
                        <label>
                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/alterarsalalaudo/<?= $ambulatorio_laudo_id; ?>/<?= $exame_id; ?>/<?= $paciente_id; ?>/<?= $procedimento_tuss_id; ?>/<?= @$obj->_sala_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=400');">
                                Sala:
                                <?= @$obj->_sala ?>
                            </a>
                        </label>  &nbsp;&nbsp;&nbsp;
                    <? } ?>                            

                    <? if (in_array('exame', $opc_dadospaciente)) { ?>
                        <label> Exame: </label><?= @$obj->_procedimento ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>                                                     

                    <? if (in_array('solicitante', $opc_dadospaciente)) { ?>
                        <label>Solicitante: </label><?= @$obj->_solicitante ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <br>
                    <? if (in_array('indicacao', $opc_dadospaciente)) { ?>
                        <label>Indicaçao: </label><?= @$obj->_indicacao ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('endereco', $opc_dadospaciente)) { ?>
                        <label>Endereco:  </label><?= @$obj->_logradouro ?>, <?= @$obj->_numero . ' ' . @$obj->_bairro ?> <?= (in_array('cidade', $opc_dadospaciente)) ? ' , ' . @$obj->_cidade : ''; ?> - <?= @$obj->_uf ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('nome_pai', $opc_dadospaciente)) { ?>
                        <label>Pai: </label><?= @$obj->_nome_pai ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <? if (in_array('cpf_pai', $opc_dadospaciente)) { ?>
                        <label>CPF Pai: </label><?= @$obj->_cpf_pai ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <? if (in_array('ocupacao_pai', $opc_dadospaciente)) { ?>
                        <label>Ocupação: </label><?= @$obj->_ocupacao_pai ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <br>

                    <? if (in_array('nome_mae', $opc_dadospaciente)) { ?>
                        <label>Mãe: </label><?= @$obj->_nome_mae ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <? if (in_array('cpf_mae', $opc_dadospaciente)) { ?>
                        <label>CPF Mãe: </label><?= @$obj->_cpf_mae ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>
                    <? if (in_array('ocupacao_mae', $opc_dadospaciente)) { ?>
                        <label>Ocupação: </label><?= @$obj->_ocupacao_mae ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('conjuge', $opc_dadospaciente)) { ?>
                        <label>Cônjuge: </label><?= @$obj->_nome_conjuge ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <?
                        $dataFuturo = date("Y-m-d");
                        $dataAtual = @$obj->_nascimento_conjuge;
                        $date_time = new DateTime($dataAtual);
                        $diff2 = $date_time->diff(new DateTime($dataFuturo));
                        $teste2 = $diff2->format('%Ya %mm %dd');
                    ?>
                    <? if (in_array('idade_conjuge', $opc_dadospaciente)) { ?>
                        <label> Idade do Cônjuge: </label><?= @$teste2 ?> &nbsp;&nbsp;&nbsp;
                    <? } ?>

                    <? if (in_array('prontuario_antigo', $opc_dadospaciente)) { ?>
                        <label> Prontuário antigo: </label><?= @$obj->_prontuario_antigo ?> &nbsp;&nbsp;&nbsp;
                    <? } ?> -->

                   <!-- <img src="<?= base_url() ?>upload/webcam/pacientes/<?= $paciente_id ?>.jpg" width="100" height="120" /> -->


                    <table style="border-collapse: collapse; width: 100%;"> 
                        <tr >
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Paciente:</b><?= @$obj->_nome ?></td>
                            <? } ?>
                            <? if (in_array('cpf', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>CPF:</b> <?= @$obj->_cpf ?></td>
                            <? } ?>
                            <? if (in_array('nascimento', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Nascimento:</b><?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?></td>
                            <? } ?>
                            <? if (in_array('idade', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Idade: </b><?= $teste ?></td>
                            <? } ?>
                            <? if (in_array('sexo', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Sexo: </b><?= @$obj->_sexo ?></td>
                            <? } ?>


                            <td rowspan="7"><img src="<?= base_url() ?>upload/webcam/pacientes/<?= $paciente_id ?>.jpg" width="100" height="120" /></td>
                        </tr>
                        <tr>
                            <? if (in_array('convenio', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Convenio:</b><?= @$obj->_convenio; ?></td>
                            <? } ?>
                            <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Telefone:</b> <?= @$obj->_telefone ?></td>
                            <? } ?> 
                            <? if (in_array('celular', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Telefone 2: </b><?= @$obj->_celular ?></td>
                            <? } ?> 
                            <? if (in_array('ocupacao', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Ocupação: </b><?= @$obj->_profissao_cbo ?> </td>
                            <? } ?>

                            <? if (in_array('estadocivil', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Estado Civíl: </b><?= @$estado_civil ?> </td>
                            <? } ?>

                        </tr>
                        <tr>
                            <? if (in_array('endereco', $opc_dadospaciente)) { ?>
                                <td colspan="12"><b>Endereco:</b> <?= @$obj->_logradouro ?>, <?= @$obj->_numero . ' ' . @$obj->_bairro ?> <?= (in_array('cidade', $opc_dadospaciente)) ? ' , ' . @$obj->_cidade : ''; ?> - <?= @$obj->_uf ?></td>
                            <? } ?>
                        </tr>

                        <tr>

                            <? if (in_array('sala', $opc_dadospaciente)) { ?>
                                <td colspan="3" >
                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/alterarsalalaudo/<?= $ambulatorio_laudo_id; ?>/<?= $exame_id; ?>/<?= $paciente_id; ?>/<?= $procedimento_tuss_id; ?>/<?= @$obj->_sala_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=400');">
                                    <b>Sala:</b>
                                        <?= @$obj->_sala ?>
                                    </a>
                                </td>
                            <? } ?>                            

                            <? if (in_array('exame', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Exame:</b> <?= @$obj->_procedimento ?></td>
                            <? } ?> 

                            <? if (in_array('agendamento', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Agendamento:</b> <?= date('H:i:s', strtotime(@$obj->_inicio)) ?></td>
                            <? } ?>  

                            <? if (in_array('solicitante', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Solicitante: </b><?= @$obj->_solicitante ?></td>
                            <? } ?>
                            <? if (in_array('indicacao', $opc_dadospaciente)) { ?>
                                <td colspan="3"><b>Indicaçao: </b><?= @$obj->_indicacao ?></td>
                            <? } ?>                                                   

                        </tr>

                        <tr>

<!--<td>Indicacao: <?= @$obj->_indicado ?></td>-->

                        </tr>

                        <tr>
                            <? if (in_array('nome_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Pai:</b> <?= @$obj->_nome_pai ?></td>
                            <? } ?>
                            <? if (in_array('cpf_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>CPF Pai: </b><?= @$obj->_cpf_pai ?></td>
                            <? } ?>
                            <? if (in_array('ocupacao_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Ocupação: </b><?= @$obj->_ocupacao_pai ?></td>
                            <? } ?>
                        </tr>

                        <tr>
                            <? if (in_array('nome_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4"><b>Mãe:</b> <?= @$obj->_nome_mae ?></td>
                            <? } ?>
                            <? if (in_array('cpf_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>CPF Mãe: </b><?= @$obj->_cpf_mae ?></td>
                            <? } ?>
                            <? if (in_array('ocupacao_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4"><b>Ocupação: </b><?= @$obj->_ocupacao_mae ?></td>
                            <? } ?>

                        </tr>
                        <tr>
                            <? if (in_array('conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Cônjuge: </b><?= @$obj->_nome_conjuge ?></td>
                            <? } ?>
                            <?
                            $dataFuturo = date("Y-m-d");
                            $dataAtual = @$obj->_nascimento_conjuge;
                            $date_time = new DateTime($dataAtual);
                            $diff2 = $date_time->diff(new DateTime($dataFuturo));
                            $teste2 = $diff2->format('%Ya %mm %dd');
                            ?>
                            <? if (in_array('idade_conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Idade do Cônjuge: </b><?= @$teste2 ?></td>
                            <? } ?>
                            <? if (in_array('prontuario_antigo', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Prontuário antigo: </b><?= @$obj->_prontuario_antigo ?></td>
                            <? } ?>
                        </tr>

                    </table>

                    <table>
                        <tr>
                            <td>
                                <? if (in_array('preencherform', $opc_telatendimento)) { ?>
                                    <div class="bt_link_new">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherformulario/<?= $ambulatorio_laudo_id ?>');" >
                                            Formulário</a></div>
                                <? } ?>
                            </td> 
                        </tr>                        
                    </table>



                </fieldset>

                <table>
                    <tr>
                        <td >
                            <? //=date("Y-m-d",strtotime(@$obj->_data_senha)) ?>
                            <? if (in_array('chamar', $opc_telatendimento)) { ?>
                                <? if (($endereco != '')) { ?>

                                    <div class="bt_link_new">
                                        <a href='#' id='botaochamar' >Chamar</a>
                                    </div>


                                <? } else {
                                    ?>
                                    <div class="bt_link_new">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpaciente/<?= $ambulatorio_laudo_id ?>');" >
                                            Chamar</a>
                                    </div>  
                                    <?
                                }
                            }
                            ?>
                            <? if (in_array('chamar', $opc_telatendimento)) { ?>

                                <?
                            }
                            ?>
                        </td>

                        <td>
                            <? if (in_array('editar', $opc_telatendimento)) { ?>
                                <div class="bt_link_new">
                                    <a onclick="javascript:window.open('<?= base_url() ?>cadastros/pacientes/carregar/<?= $paciente_id ?>');" >
                                        Editar</a></div>
                            <? } ?>
                        </td>

                        <? if (@$obj->_status != 'FINALIZADO') { ?>
                            <td>
                                <div class="bt_link_new">
                                    <a onclick="javascript: return confirm('Deseja realmente deixar o atendimento pendente?');" href="<?= base_url() ?>ambulatorio/laudo/pendenteespecialidade/<?= $exame_id ?>" >
                                        Pendente
                                    </a>
                                </div>
                            </td>
                        <? } ?>

                        <td>
                            <? if (in_array('encaminhar', $opc_telatendimento)) { ?>
                                <div class="bt_link_new">
                                    <a href="<?= base_url() ?>ambulatorio/laudo/encaminharatendimento/<?= $ambulatorio_laudo_id ?>" >
                                        Encaminhar
                                    </a>
                                </div>
                            <? } ?>
                        </td>
                        <td>
                            <? if (in_array('histconsulta', $opc_telatendimento)) { ?>
                                <div class="bt_link_new"><a href="<?= base_url() ?>ambulatorio/laudo/carregarlaudohistorico/<?= $paciente_id ?>">Hist. Consulta</a></div>
                            <? } ?>
                        </td>
                        <td>
                            <div class="bt_link_new"><a href="<?= base_url() ?>ambulatorio/laudo/carregaranamineseantigo/<?= $paciente_id ?>">Hist. Antigo</a></div>
                        </td>
                        <td>
                            <div class="bt_link_new">
                                <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/carregarocorrencias/" . $paciente_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1280,height=650');"  >
                                    Ocorrência
                                </a>
                            </div>
                        </td>  
                    <!-- </tr>
                    <tr> -->
                    <td>
                        <div  class="item flex flex-wrap" style="float:right;">
                            <button  type="button" id="mostrarDadosExtras">+</button>
                        </div> 
                    </td>
                    </tr>
                </table>
                <div id="DadosExtras" hidden>

                    <fieldset>
                        <legend>MEDIDAS</legend>
                        <table>
                            <tr>
                                <td><font size = -1>Peso:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="Peso" id="Peso" class="texto02"  alt="decimal"  onblur="calculaImc()" value="<?= $peso ?>"/></font></td>
                                <td width="50px;"><font size = -1>Kg</font></td>
                                <td ><font size = -1>Altura:</font></td>
                                <td width="50px;"><font size = -1><input type="number" name="Altura" step="0.1" id="Altura" class="texto02" value="<?= $altura; ?>" onblur="calculaImc()"/></font></td>
                                <td width="50px;"><font size = -1>Cm</font></td>
                                <?
//                            $imc = 0;
//                            $peso =  @$obj->_peso;
//                            $altura = substr(@$obj->_altura, 0, 1) . "." .  substr(@$obj->_altura, 1, 2);
//                            $altura = floatval($altura);
//                            if($altura != 0){
//                            $imc = $peso / pow($altura, 2);
//                            }
                                ?>
                                <td><font size = -1>IMC</font></td>
                                <td width="60px;"><font size = -1><input type="text" name="imc" id="imc" class="texto02"  readonly/></font></td>
                                <td width="30px;"></td>


                                <td><font size = -1>Diabetes:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="diabetes" id="diabetes" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='nao'<?
                                        if (@$diabetes == 'nao'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='sim' <?
                                        if (@$diabetes == 'sim'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font></td>
                                <td width="20px;"></td>
                                <td><font size = -1>Hipertens&atilde;o:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="hipertensao" id="hipertensao" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='nao'<?
                                        if (@$hipertensao == 'nao'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='sim' <?
                                        if (@$hipertensao == 'sim'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font>
                                    </td>
                                    

                                    <td><font size = -1>Medicação:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="medicacao" id="medicacao" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='Nao'<?
                                        if (@$medicacao == 'f'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='Sim' <?
                                        if (@$medicacao == 't'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font>
                                    </td>
                            </tr>
                            </table>
                            <table>
                            <tr>

                                <td><font size = -1>Pulso:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="pulso" id="Pulso" class="texto02"  alt="decimal" value="<?= $pulso ?>"/></font></td>
                                <td width="50px;"><font size = -1>Bpm</font></td>

                                <td><font size = -1>Temperatura:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="temperatura" id="Temperatura" class="texto02"  alt="decimal" value="<?= $temperatura ?>"/></font></td>
                                <td width="50px;"><font size = -1>ºC</font></td>

                                <td><font size = -1>Pressao Arterial:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="pressao_arterial" id="pressao_arterial" class="texto02"  alt="decimal" value="<?= $pressao_arterial ?>"/></font></td>
                                <td width="50px;"><font size = -1>mm/Hg</font></td>

                                <td><font size = -1>F. Respiratoria:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="f_respiratoria" id="f_respiratoria" class="texto02"  alt="decimal" value="<?= $f_respiratoria ?>"/></font></td>
                                <td width="50px;"><font size = -1>Rpm</font></td>

                                <td><font size = -1>SPO2</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="spo2" id="spo2" class="texto02"  alt="decimal" value="<?= $spo2 ?>"/></font></td>
                                <td width="50px;"><font size = -1>%</font></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <? if ($empresapermissao[0]->oftamologia == 't' && @$obj->_grupo == 'OFTALMOLOGIA') { ?>
                    <script>
                        $(function () {
                        $("#tabs").tabs();
                        });
                        $(".tab-ativa").tabs("option", "active", 1);
                    </script>    

                <? }
                ?>
                <? if ($empresapermissao[0]->atendimento_medico == 't' && ($empresapermissao[0]->oftamologia == 'f' || @$obj->_grupo != 'OFTALMOLOGIA')) { ?>
                    <script>
                        $(function () {
                        $("#tabs").tabs();
                        });
                        $(".tab-ativa").tabs("option", "active", 1);
                    </script>    

                <? }
                ?>
                <div>

                    <fieldset>
                        <div id="tabs">
                            <? if ($empresapermissao[0]->oftamologia == 't' && @$obj->_grupo == 'OFTALMOLOGIA') { ?>
                                <ul>
                                    <li><a class="tab-ativa" href="#tabs-2">Anamnese</a></li>
                                    <li><a href="#tabs-1">Oftalmologia</a></li>
                                    <li><a href="#tabs-3">Histórico</a></li>
                                    <li><a href="#tabs-4">Dados</a></li>
                                </ul>
                            <? }
                            ?>

                            <? if ($empresapermissao[0]->atendimento_medico == 't' && ($empresapermissao[0]->oftamologia == 'f' || @$obj->_grupo != 'OFTALMOLOGIA')) { ?>
                                <?
                                    if (@$empresapermissao[0]->abas_atendimento != '') {
                                        $abas_atendimento = json_decode(@$empresapermissao[0]->abas_atendimento);
                                    } else {
                                        $abas_atendimento = array();
                                    }

                                    // print_r($modelo_atendimento);
                                ?>
                                
                                <ul>
                                     <li><a class="tab-ativa" href="#tabs-2">Evolução</a></li>  
                                    <?php if(in_array('evolucao', $abas_atendimento)) {?>
                                     <li><a class="tab-ativa" href="#tabs-15">1a Consulta</a></li> 
                                    <?php } ?> 
                                    <li <?= (in_array('receitas', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-5" onclick="tinyMCE.triggerSave();">Receituário</a></li>                                
                                    <li <?= (in_array('s_exames', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-6" onclick="tinyMCE.triggerSave();">Solicitar Exames</a></li>   
                                    <li <?= (in_array('anotacao_privada', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-9" onclick="tinyMCE.triggerSave();">Anotação Privada</a></li>                               
                                    <li <?= (in_array('tomadas', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabsTomada" onclick="tinyMCE.triggerSave();">Tomadas</a></li>    
                                    <li <?= (in_array('visualizar', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-8" onclick="tinyMCE.triggerSave();">Visualizar</a></li>                              
                                    <li <?= (in_array('historico', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-3" onclick="tinyMCE.triggerSave();">Histórico</a></li>
                                    <li <?= (in_array('dados', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-4" onclick="tinyMCE.triggerSave();">Dados</a></li>
                                    <li <?= (in_array('arquivos', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabsArquivo" onclick="tinyMCE.triggerSave();">Arquivos</a></li>
                                    <li <?= (in_array('receituario_simples', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-10" onclick="tinyMCE.triggerSave();">Receituário Simples</a></li>
                                    <li <?= (in_array('receituario_especial', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-11" onclick="tinyMCE.triggerSave();">Receituário Especial</a></li>
                                    <li <?= (in_array('solicitacao_sadt', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-7" onclick="tinyMCE.triggerSave();">Solicitação SADT</a></li> 
                                    
                                    <li <?= (in_array('atestado', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-12" onclick="tinyMCE.triggerSave();">Atestado</a></li>
                                   
                                    
                                    <li <?= (in_array('relatorio', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-13" onclick="tinyMCE.triggerSave();">Relatório</a></li>
                                    
                                    <li <?= (in_array('historico2', $abas_atendimento)) ? 'style="display:block"' : 'style="display:none"'; ?>><a href="#tabs-14" onclick="tinyMCE.triggerSave();">Histórico</a></li>
                                    
                                    
                                </ul>
                                <?
                            }
                            ?>
                              
                            
                      <?php if(in_array('evolucao', $abas_atendimento)) {?>
                            <div id="tabs-15">
                                <?php $json_evolucao = @json_decode($obj->_obj_evolucao,true);  ?>
                                <table width="100%"  >
                                    <tr>
                                        <td><b>Queixa Principal</b></td>  
                                    </tr>
                                    <tr>
                                       <td><textarea style="width: 50%;" rows="5" name="queixa_princialEvolucao" id="queixa_princialEvolucao"><?=  (isset($json_evolucao['queixa_princialEvolucao'])) ? $json_evolucao['queixa_princialEvolucao']:""; ?></textarea></td>
                                      
                                    </tr>
                                     <tr> 
                                        <td><b>Historia da Doença Atual</b></td> 
                                    </tr>
                                    <tr> 
                                      <td><textarea style="width: 50%;" rows="5" name="historia_doenca_atualEvolucao" id="historia_doenca_atualEvolucao"><?=  (isset($json_evolucao['historia_doenca_atualEvolucao'])) ? $json_evolucao['historia_doenca_atualEvolucao']:""; ?></textarea></td>
                                    </tr>
                                     
                                    
                                    <tr>
                                        <td><b>Comorbidades</b></td>
                                       
                                    </tr>
                                    <tr>
                                        <td><textarea style="width: 50%;" rows="5" name="comorbidadesEvolucao" id="comorbidadesEvolucao"><?=  (isset($json_evolucao['comorbidadesEvolucao'])) ? $json_evolucao['comorbidadesEvolucao']:""; ?></textarea></td>
                                       
                                    </tr>
                                    
                                     <tr>
                                        
                                        <td><b>Diagnostico</b></td>
                                    </tr>
                                    <tr> 
                                        <td><textarea style="width: 50%;" rows="5" name="diagnosticoEvolucao" id="diagnosticoEvolucao"><?= (isset($json_evolucao['diagnosticoEvolucao'])) ? $json_evolucao['diagnosticoEvolucao']:"";  ?></textarea></td>
                                    </tr>
                                     
                                    <tr>
                                        <td><b>Conduta</b></td>
                                        
                                    </tr>  
                                    <tr>
                                        <td><textarea  style="width: 50%;" rows="5" name="condutaEvolucao" id="condutaEvolucao"><?=  (isset($json_evolucao['condutaEvolucao'])) ? $json_evolucao['condutaEvolucao']:"";  ?></textarea></td>
                                    </tr>

                                    <tr>
                                        <td><b>Exame Físico</b></td>
                                        
                                    </tr>  
                                    <tr>
                                        <td><textarea  style="width: 50%;" rows="5" name="examefisicoEvolucao" id="examefisicoEvolucao"><?=  (isset($json_evolucao['examefisicoEvolucao'])) ? $json_evolucao['examefisicoEvolucao']:"";  ?></textarea></td>
                                    </tr>

                                    <tr>
                                        <td><b>Exames Anteriores</b></td>
                                        
                                    </tr>  
                                    <tr>
                                        <td><textarea  style="width: 50%;" rows="5" name="examesatentioresEvolucao" id="examesatentioresEvolucao"><?=  (isset($json_evolucao['examesatentioresEvolucao'])) ? $json_evolucao['examesatentioresEvolucao']:"";  ?></textarea></td>
                                    </tr>
                                </table>
                            </div>
                      <?php }?>
                            
                            <div id="tabs-13">
                             <!--@$obj->_medico_parecer1-->
                             <!--$ambulatorio_laudo_id--> 
                                        <div> 
                                            <input type='hidden' name="laudo_id" value="<?= $ambulatorio_laudo_id; ?>" />
                                            <input type='hidden' name="medico" value="<?= @$obj->_medico_parecer1; ?>" /> 
                                        </div>
                                        <div>
                                            <textarea id="receita" name="receita" rows="15" cols="80" style="width: 80%"></textarea>
                                        </div> 
                                        <div>
                                            <label>Nome</label>
                                            <input type="text" name="txtNome" id="txtNome" class="texto10" />
                                        </div>

                                        <hr/>
                                        <button type="submit" name="btnEnviar" id="btnEnviar">Salvar Relatorio</button>
                                        <button type="submit" name="btnSalvarModelo" id="btnSalvarModelo" disabled>Salvar Modelo</button>
                                        <button type="reset" name="btnLimpar">Limpar</button>
                      
                             <table id="table_agente_toxico" border="0">
                                <thead>
                                    <tr>
                                        <th class="tabela_header">Data</th>         
                                        <th class="tabela_header">Medico</th>                            
                                        <th class="tabela_header">Texto</th> 
                                        <th colspan="3" class="tabela_header">&nbsp;</th>
                                    </tr>
                                </thead>
                            <?  $estilo_linha = "tabela_content01";
                            foreach ($relatorios as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
        //                        var_dump($item);die;
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>upload/novoatendimento/<?= $item->laudo_id; ?>/relatorios_<?= $item->ambulatorio_relatorio_id; ?>.pdf');">Visualizar</a></div>
                                        </td>
                                          
                                    </tr>

                                </tbody>
                                <?
                            }?>
                            </table>
                       
                       
                            </div>
                             
                            <div id="tabs-2"> 
                                <table style="font-size: 12px">
                                    <tr>
                                        <td>
                                            <label>Laudo</label>
                                        </td>
                                        <td>
                                            
                                                
                                            <select name="exame" id="exame" class="size2" >
                                                <option value='' >Selecione</option>
                                                <?php foreach ($lista as $item) { ?>
                                                    <option value="<?php echo $item->ambulatorio_modelo_laudo_id; ?>" ><?php echo $item->nome; ?></option>
                                                <?php } ?>
                                            </select>
                                           
                                            
                                            
                                        </td>
                                        <td>
                                            <label>Queixa Principal</label>
                                        </td>
                                        <td>
                                       
                                            <input type="text" id="cabecalho" class="texto7" name="cabecalho" value="<?= $cabecalho ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>CID Primario</label>
                                        </td>
                                        <td>
                                           
                                            <input type="hidden" name="agrupadorfisioterapia" id="agrupadorfisioterapia" value="<?= @$obj->_agrupador_fisioterapia; ?>" class="size2" />
                                            <input type="hidden" name="txtCICPrimario" id="txtCICPrimario" value="<?= @$obj->_cid; ?>" class="size2" />
                                            <input type="text" name="txtCICPrimariolabel" id="txtCICPrimariolabel" value="<?= @$obj->_ciddescricao; ?>" class="size8" />
                                        </td>
                                        <td>
                                            <label>CID Secundario</label>
                                        </td>
                                        <td>
                                            
                                            <input type="hidden" name="txtCICSecundario" id="txtCICSecundario" value="<?= @$obj->_cid2; ?>" class="size2" />
                                            <input type="text" name="txtCICSecundariolabel" id="txtCICSecundariolabel" value="<?= @$obj->_cid2descricao; ?>" class="size8" />
                                        </td>
                                    </tr>
                                    <tr>
                                    <? if (in_array('pesquisar_codigo_tuss', $opc_telatendimento)) { ?>
                                        <td>
                                            <label>Pesquisar Código TUSS</label>
                                        </td>
                                        <td>
                                            
                                            <input type="hidden" name="txtCodigoTuss" id="txtCodigoTuss" value="<?= @$obj->_cid; ?>" class="size2" />
                                            <input type="text" name="txtCodigoTusslabel" id="txtCodigoTusslabel" value="<?= @$obj->_ciddescricao; ?>" class="size8" />

                                        </td>
                                    <? } ?>
                                    <? if (in_array('diagnostico', $opc_telatendimento)) { ?>
                                        <td>
                                            <label>Diagnóstico</label>
                                        </td>
                                        <td>
                                            
                                            <input type="text" name="txtDiagnostico" id="txtDiagnostico" value="<?= @$obj->_diagnostico; ?>" class="size8"  maxlength="50" />
                                        </td>
                                    <? } ?>
                                    </tr>
                                    
                                    <? if(count($listatemplates) > 0){?>
                                    <tr>
                                        <td>
                                            <label>Templates</label>
                                        </td>
                                        <td>
                                            <select name="template" id="template" class="size2" onchange="carregarTemplate();">
                                            <option value=''>Selecione</option>
                                            <?php foreach ($listatemplates as $item) { ?>
                                                <option value="<?php  echo $item->template_anamnese_id; ?>" 
                                                <?  if($item->template_anamnese_id == @$obj->_template_id){echo 'selected ';}?>>
                                                <?php  echo $item->nome_template; ?>
                                                </option>
                                            <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                            <?  } ?>
                                    
                                    <tr <?= (@$empresapermissao[0]->informacao_adicional == 'f') ? "style='display:none;'" : '' ?>>

                                    <td>
                                        <label>Lembrete </label>
                                    </td>
                                    <td colspan="3">
                                        <!-- <input name="informacao_laudo" id="informacao_laudo" class="texto12" value="<?= @$obj->_informacao_laudo ?>"/> -->
                                        <textarea id="informacao_laudo"  name="informacao_laudo" class="texto12" rows="2" ><?= @$obj->_informacao_laudo ?></textarea>
                                    </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="hidden" id="objTemplate" name="objTemplate" value='<?=@$obj->_template_obj?>'>
                                            <input type="hidden" id="arrayTemplate" name="arrayTemplate">
                                            
                                            <!-- <br> -->
                                        </td>
                                    </tr>

                                </table>
                                
                                <!-- <br> -->
                                <!-- <br> -->
                                



                                <style>
                                    .row{
                                        display: flex;
                                    }
                                    .col{
                                        flex: 1;
                                        align-self: center;
                                    }

                                </style>    
                                <div class="row" >
                                    <div class="col" >
                                        <?
                                        $contador_col = 0;
                                        ?>
 
                                        <table >
                                            <tr><td rowspan="11" >
                                                <div id="camposDiv" style="min-width: 600px; font-size: 12px;">
                                                
                                                </div>
                                                <div id="anamnesePadrao">
                                                    <textarea id="laudo" name="laudo" rows="30" class="tinymce" id="tinymce" cols="80" style="width: 800px"><?= @$obj->_texto; ?></textarea>
                                                </div>
                                                </td>
                                            
                                            <? if (in_array('receituario', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">

                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarreceituario/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                            Receituario</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if ($integracaosollis == 't') { ?>
                                                <td width="40px;"><div class="bt_link_new">

                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarprescricao/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>');" >
                                                            Prescrição</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('parecercirurgia', $opc_telatendimento)) { ?>

                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherparecer/<?= $ambulatorio_laudo_id ?>');" >
                                                            Parecer C.P</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>



                                            <? if (in_array('laudoapendicite', $opc_telatendimento)) { ?>

                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherlaudoapendicite/<?= $ambulatorio_laudo_id ?>');" >
                                                            Laudo Apendicite
                                                        </a>
                                                    </div>

                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>



                                            <!-- </tr>
                                            <tr> -->
                                            <? if (in_array('rotinas', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">

                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarrotinas/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                            Rotinas</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('historicoimprimir', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaohistoricoescolhermedico/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                            Imprimir Histórico</a></div>
                                                </td>




                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('cirurgias', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchercirurgia/<?= $ambulatorio_laudo_id ?>');" >
                                                            Cirurgias</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('receituarioesp', $opc_telatendimento)) { ?>
                                                <td width="40px;">
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarreceituarioespecial/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                            R. especial
                                                        </a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('cirurgias', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherexameslab/<?= $ambulatorio_laudo_id ?>');" >
                                                            E. Laboratoriais</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('solicitar_exames', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregarexames/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');" >
                                                            S. exames</a></div>
                                                    <!--                                        impressaolaudo -->
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('eco', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherecocardio/<?= $ambulatorio_laudo_id ?>');" >
                                                            Ecocardiograma</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('atestado', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/carregaratestado/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                            Atestado</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('ecostress', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherecostress/<?= $ambulatorio_laudo_id ?>');" >
                                                            Eco Stress</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('declaracao', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/escolherdeclaracao/<?= $paciente_id ?>/<?= @$obj->_guia_id; ?>/<?= $agenda_exames_id ?>');" >
                                                            Declaração</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('cate', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchercate/<?= $ambulatorio_laudo_id ?>');" >
                                                            Cateterismo</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('arquivos', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/anexarimagem/<?= $ambulatorio_laudo_id ?>');" >
                                                            Arquivos</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('holter', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherholter/<?= $ambulatorio_laudo_id ?>');" >
                                                            Holter 24h</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('aih', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a id="aih" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/imprimirmodeloaih/<?= $ambulatorio_laudo_id ?>');" >
                                                            AIH</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('cintil', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchercintilografia/<?= $ambulatorio_laudo_id ?>');" >
                                                            Cintilografia</a>
                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('consultar_procedimento', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/procedimentoplano/procedimentoplanoconsultalaudo);" >
                                                            Consultar Proc...</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('mapa', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchermapa/<?= $ambulatorio_laudo_id ?>');" >
                                                            Mapa</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('sadt', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
    <!--                                                            <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/pesquisarsolicitacaosadt/<?= $paciente_id ?>/<?= @$obj->_convenio_id ?>/<?= @$obj->_medico_parecer1 ?>');" >
                                                            Solicitação SADT</a>-->
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/gravarnovasolicitacaosadt/<?= $paciente_id ?>/<?= @$obj->_convenio_id ?>/<?= @$obj->_medico_parecer1 ?>/<?= @$obj->_ambulatorio_laudo_id ?>/<?= @$obj->_sala_id ?>');" >
                                                            Solicitação SADT</a>

                                                    </div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <? if (in_array('te', $opc_telatendimento)) { ?>
                                                <td>
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchertergometrico/<?= $ambulatorio_laudo_id ?>');" >
                                                            Teste Ergométrico</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>

                                            <!-- </tr> -->
                                            <!-- <tr> -->
                                            <? if (in_array('cadastro_aso', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/cadastroaso/<?= $paciente_id ?>/<?= @$obj->_medico_parecer1 ?>');" >
                                                            Cadastro ASO</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>
                                            <? if (in_array('solicitarparecer', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/solicitarparecer/<?= $paciente_id ?>/<?= $ambulatorio_laudo_id ?>');" >
                                                            Solicitar Parecer</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>
                                            <?
                                            $data['retorno_permissao'] = $this->laudo->listarpermissao();
                                            if ($data['retorno_permissao'][0]->agenda_atend == 't') {
                                                ?>
                                                <td> 
                                                    <div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preenchersalas/<?= $ambulatorio_laudo_id ?>/<?= @$obj->_medico_parecer1 ?>');" >
                                                            Agendar Atend.</a>
                                                    </div>
                                                </td>  
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                                <?
                                            }
                                            ?>
                                            <? if (in_array('riscocirurgico', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/empresa/riscocirurgico/<?= $paciente_id ?>');" >
                                                            Risco Cirúrgico</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>
                                            <? if (in_array('zoom', $opc_telatendimento)) { ?>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a target="_blank" href="<?=@$obj->_link_reuniao?>" >
                                                            Zoom</a></div>
                                                </td>
                                                <?
                                                $contador_col++;
                                                if ($contador_col == 2) {
                                                    $contador_col = 0;
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                            <? } ?>
                                            

                                            </tr>
                                            





                                        </table>
                                        <table>
                                            <tr>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');" >
                                                            Visualizar</a></div>
                                                </td>

                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <? if ($adendo) { ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <label><h3>Adendo</h3></label>
                                                            <textarea id="adendo"   name="adendo" class="adendo" rows="30" cols="80" style="width: 80%"></textarea>
                                                        </div>  
                                                    </td>
                                                </tr>
                                            <? }
                                            ?>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- ESSE É O RELOGIO, VAI FICAR COMENTADO PRA CLINICA DE SAO PAULO -->
                                    <!-- AGORA O RELATORIO É CONFIGURAVEL NO MANTER EMPRESA EM "Tela de atendimento Medico -->
                                     <div class="col">
                                        <table>
                                        <? if (in_array('relogio', $opc_telatendimento)) { ?>
                                            <tr>

                                                <td>
                                                    
                                                    <center><font color="#FF0000" size="6" face="Arial Black"><span id="clock1"></span><script>setTimeout('getSecs()', 1000);</script></font></center>
                                                </td>
                                            </tr>
                                        <? } ?>
                                            <tr>
                                                <td style="text-align:center;">
                                                    <? if (@$obj->_primeiro_atendimento == 't') { ?>
                                                        <span style="color: #189d00;font-size: large;font-weight: bold">Primeiro Atendimento</span>

                                                    <? } ?>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td>
                                                    <div <? (@$empresapermissao[0]->informacao_adicional == 'f') ? "style='display:none;'" : '' ?>>
                                                        <label for="informacao_extra">Informação</label><br>
                                                        <textarea id="informacao_laudo"  name="informacao_laudo" class="informacao_laudo" rows="10" cols="40" ><? @$obj->_informacao_laudo ?></textarea>
                                                    </div>

                                                </td>

                                            </tr>

                                        </table>  
                                    </div> 

                                </div>
                               

                            </div>
                            
                            <div <?
                            if ($empresapermissao[0]->oftamologia == 't' && @$obj->_grupo == 'OFTALMOLOGIA') {
                                echo "style='display:inline;'";
                            } else {
                                echo "style='display:none;'";
                            }
                            ?> id="tabs-1">

                                <table>
                                    <tr style="text-align: left;">
                                        <th>
                                            Inspeção Geral 
                                        </th>
                                        <th>
                                            Motilidade Ocular 
                                        </th>
                                    </tr>
                                    <tr>

                                        <td>
                                            <!--<label></label>-->
                                            <textarea  id="inspecao_geral" name="inspecao_geral" rows="5" cols="60" style="resize: none"><?= @$obj->_inspecao_geral; ?></textarea>
                                        </td>
                                        <td>
                                            <!--<label></label>-->
                                            <textarea id="motilidade_ocular" name="motilidade_ocular" rows="5" cols="60" style="resize: none"><?= @$obj->_motilidade_ocular; ?></textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table border="0">
                                                <tr>
                                                    <th style="text-align:left" colspan="2">
                                                        <label>Acuidade Visual Sem Correção</label>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>

                                                        OE <select name="acuidade_oe" id="acuidade_oe" class="size2">
                                                            <option value=""> </option>
                                                            <? foreach ($listaracuidadeoe as $value) : ?>
                                                                <option value="<?= $value->nome; ?>"<?
                                                                if (@$obj->_acuidade_oe == $value->nome):echo "selected = 'true'";
                                                                endif;
                                                                ?>><?= $value->nome; ?></option>
                                                                    <? endforeach; ?>
                                                        </select> 
                                                    </td>
                                                    <td>
                                                        OD <select name="acuidade_od" id="acuidade_od" class="size2">
                                                            <option value=""> </option>
                                                            <? foreach ($listaracuidadeod as $value) : ?>
                                                                <option value="<?= $value->nome; ?>"<?
                                                                if (@$obj->_acuidade_od == $value->nome):echo "selected = 'true'";
                                                                endif;
                                                                ?>><?= $value->nome; ?></option>
                                                                    <? endforeach; ?>
                                                        </select>   

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table border="0">
                                                <tr>
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" name="refracao_retinoscopia" <?
                                                                    if (@$obj->_refracao_retinoscopia == 'refracao') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> value="refracao">
                                                                    <span>Refração</span>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="refracao_retinoscopia" <?
                                                                    if (@$obj->_refracao_retinoscopia == 'retinoscopia') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> value="retinoscopia">
                                                                    <span>Retinoscopia</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" name="dinamica_estatica" value="dinamica"<?
                                                                    if (@$obj->_dinamica_estatica == 'dinamica') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <span>Dinâmica</span>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="dinamica_estatica" value="estatica"<?
                                                                    if (@$obj->_dinamica_estatica == 'estatica') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <span>Estática</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" name="carregar_refrator" <?
                                                                    if (@$obj->_carregar_refrator == 't') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <span>Carregar Refrator</span>
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="carregar_oculos"  <?
                                                                    if (@$obj->_carregar_oculos == 't') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <span>Carregar Óculos</span>
                                                                </td>
                                                            </tr>

                                                        </table>   
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Biomicroscopia</label>
                                            <textarea id="biomicroscopia" name="biomicroscopia" rows="5" cols="60" style="resize: none"><?= @$obj->_biomicroscopia; ?></textarea>
                                        </td>
                                        <td>
                                            <table border="0">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Esférico</th>
                                                        <th>Cilindrico</th>
                                                        <th>Eixo</th>
                                                        <th>A.V</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>OD </td>
                                                        <td>
                                                            <select name="oftamologia_od_esferico" id="oftamologia_od_esferico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listarodes as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_od_esferico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_od_cilindrico" id="oftamologia_od_cilindrico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listarodcl as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_od_cilindrico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_od_eixo" id="listarodes" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listarodeixo as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_od_eixo == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_od_av" id="listarodes" class="size2">
                                                                <option value="Selecione"></option>
                                                                <? foreach ($listarodav as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_od_av == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>OE </td>
                                                        <td>
                                                            <select name="oftamologia_oe_esferico" id="oftamologia_oe_esferico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listaroees as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_oe_esferico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_oe_cilindrico" id="oftamologia_oe_cilindrico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listaroecl as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_oe_cilindrico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_oe_eixo" id="oftamologia_oe_eixo" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listaroeeixo as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_oe_eixo == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_oe_av" id="oftamologia_oe_av" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listaroeav as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_oe_av == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>AD </td>
                                                        <td>
                                                            <select name="oftamologia_ad_esferico" id="oftamologia_ad_esferico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listarades as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_ad_esferico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <td>
                                                            <select name="oftamologia_ad_cilindrico" id="oftamologia_ad_cilindrico" class="size2">
                                                                <option value=""> </option>
                                                                <? foreach ($listaradcl as $value) : ?>
                                                                    <option value="<?= $value->nome; ?>"<?
                                                                    if (@$obj->_oftamologia_ad_cilindrico == $value->nome):echo "selected = 'true'";
                                                                    endif;
                                                                    ?>><?= $value->nome; ?></option>
                                                                        <? endforeach; ?>
                                                            </select> 
                                                        </td>
                                                        <!--<td>-->
                                                        <td width="40px;"><div class="bt_link_new">
                                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaoculos/<?= $ambulatorio_laudo_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>');" >
                                                                    Receita Óculos</a></div>
                                                        </td>
                                                        <!--</td>-->


                                                    </tr>
                                                </tbody>
                                            </table>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0">
                                                <thead>
                                                    <tr>
                                                        <th>Pressão Ocular</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            OD <input step="0.01" type="number" name="pressao_ocular_od" value="<?= @$obj->_pressao_ocular_od ?>">    
                                                        </td>

                                                        <td>
                                                            OE <input step="0.01" type="number" name="pressao_ocular_oe"  value="<?= @$obj->_pressao_ocular_oe ?>">    
                                                        </td>
                                                        <td>
                                                            Hora <input  type="text" id="refracao_retinoscopia_hora" name="pressao_ocular_hora" value="<?= @$obj->_pressao_ocular_hora ?>">    
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>
                                    <tr style="text-align: left;">
                                        <th>
                                            Mapeamento de Retinas  
                                        </th>
                                        <th>
                                            Conduta   
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <!--<label></label>-->
                                            <textarea id="mapeamento_retinas" name="mapeamento_retinas" rows="5" cols="60" style="resize: none"><?= @$obj->_mapeamento_retinas; ?></textarea>    
                                        </td>

                                        <td>
                                            <!--<label></label>-->
                                            <textarea id="conduta" name="conduta" rows="5" cols="60" style="resize: none"><?= @$obj->_conduta; ?></textarea>   
                                        </td>
                                    </tr>
                                </table>  
                            </div>
                            
                           <div id="tabs-14">
                                <script>
                                    $(function () {
                                    $("#tabsn2").tabs();
                                    });
                                    $(".tab").tabs("option", "active", 1);
                                </script>                                
                                <fieldset>
                                    
                                    <div id="tabsn2">
                                        <ul> 
                                            <li><a class="tab" href="#tabs-a2">Todos</a></li>
                                            <li><a href="#tabs-atendimento">Por Data</a></li> 
                                        </ul>
                                        <style>
                                            .row{
                                                display: flex;
                                            }
                                            .col{
                                                flex: 1;
                                                align-self: center;
                                            }

                                        </style>  
                                        <div id="tabs-atendimento">
                                            <table>
                                                <tr height='100px'>
                                                    <td>
                                                        <table>
                                                            <?
                                                            $hitoricototal = $this->laudo->listardatashistorico($paciente_id);
                                                            ?>                                                                
                                                            <tr>
                                                                 <td>
                                                                    <select name="tipoprocedimento" id="tipoprocedimento" class="size2">
                                                                          <option value="">Todos</option>
                                                                          <option value="atendimento">Atendimentos</option>
                                                                          <option value="exame">Exames</option>
                                                                    </select>
                                                                </td>
                                                                <td>                                                                    
                                                                    <select name="datahist2" id="datahist2" class="size2">
                                                                        <option value="">Selecione</option>
                                                                        <? $datahist = '00/00/0000'; ?>
                                                                        <? foreach ($hitoricototal as $item) : ?>

                                                                            <? if (date("d/m/Y", strtotime($item->data_cadastro)) != $datahist) { ?>
                                                                                <? $datahist = date("d/m/Y", strtotime($item->data_cadastro)); ?>
                                                                                <option value="<?= date("d/m/Y", strtotime($item->data_cadastro)); ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></option>
                                                                                <? // var_dump($datahist);var_dump(date("d/m/Y", strtotime($item->data_cadastro)));die;  ?>
                                                                            <? } ?>
                                                                        <? endforeach; ?>
                                                                    </select>
                                                                </td> 
                                                            </tr> 
                                                        </table>
                                                    </td>
                                                </tr>
                                                <style>
                                                    .row{
                                                        display: flex;
                                                    }
                                                    .col{
                                                        flex: 1;
                                                        align-self: center;
                                                    }

                                                </style> 
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col">
                                                                <table>
                                                                    <tr>
                                                                        <td rowspan="11" >
                                                                            <div id="pordata2">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>  
                                           <div id="tabs-a2">
                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de consultas</font></b></legend>
                                                <div>
                                                    <?
// Esse código serve para mostrar os históricos que foram importados
// De outro sistema STG.
// Na hora que o médico finaliza o atendimento, o sistema manda os dados para o endereço do sistema
// Digitado no cadastro do médico, caso exista ele salva numa tabela especifica.
// Para não criar um outro local onde iriam aparecer os atendimentos dessa tabela 
// Há essa lógica aqui embaixo para inserir no meio dos outros atendimentos da ambulatorio_laudo os outros
// da integração
                                                    $contador_teste = 0;
// Contador para utilizar no array
//                            $historico = array();
                                                    foreach ($historico as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebcon[$contador_teste])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <?
                                                                        $idade = 0;
                                                                        $dataFuturo2 = $historicowebcon[$contador_teste]->data;
                                                                        $dataAtual2 = @$obj->_nascimento;
                                                                        if ($dataAtual2 != '') {
                                                                            $date_time2 = new DateTime($dataAtual2);
                                                                            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                                                                            $teste2 = $diff2->format('%Y');
                                                                            $idade = $teste2;
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td >Idade no atendimento: <?= $idade ?> Anos</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Evolução: <?= $historicowebcon[$contador_teste]->enfermagem; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_teste ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebcon[$contador_teste])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <?
                                                                $idade = 0;
                                                                $dataFuturo2 = $item->data_cadastro;
                                                                $dataAtual2 = @$obj->_nascimento;
                                                                if ($dataAtual2 != '') {
                                                                    $date_time2 = new DateTime($dataAtual2);
                                                                    $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                                                                    $teste2 = $diff2->format('%Y');
                                                                    $idade = $teste2;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td >Idade no atendimento: <?= $idade ?> Anos</td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Evolução: <?= $item->enfermagem; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Queixa principal: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):
                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <hr>
                                                    <? }
                                                    ?>
                                                </div>
                                                <?
                                                if (count($historico) == 0 || $contador_teste < count($historicowebcon)) {
                                                    while ($contador_teste < count($historicowebcon)) {
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td><span style="color: #007fff">Integração</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                        <hr>

                                                        <?
                                                        $contador_teste++;
                                                    }
                                                }
                                                ?>



                                            </fieldset>

                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de exames</font></b></legend>
                                                <div>

                                                    <?
                                                    $contador_exame = 0;
                                                    foreach ($historicoexame as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebexa[$contador_exame])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_exame ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebexa[$contador_exame])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>


                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <?
                                                                    $this->load->helper('directory');
                                                                    $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        sort($arquivo_pastaimagem);
                                                                    }
                                                                    $i = 0;
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        foreach ($arquivo_pastaimagem as $value) {
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td >Imagens : <font size="2"><b> <?= $i ?></b>
                                                                        <?
                                                                        if ($arquivo_pastaimagem != false):
                                                                            foreach ($arquivo_pastaimagem as $value) {
                                                                                ?>
                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                                                                                <?
                                                                            }
                                                                            $arquivo_pastaimagem = "";
                                                                        endif
                                                                        ?>
                                                                        <!--                <ul id="sortable">
                                
                                                                                        </ul>-->
                                                                    </td >
                                                                </tr>
                                                                <tr>
                                                                    <td >Laudo: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):

                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                        border-bottom:none;mso-border-top-alt:none;border-left:
                                                                        none;border-right:none;' colspan="10">&nbsp;</th>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    <? }
                                                    ?>
                                                    <?
                                                    if (count($historico) == 0 || $contador_exame < count($historicowebexa)) {
                                                        while ($contador_exame < count($historicowebexa)) {
                                                            ?>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                            <hr>

                                                            <?
                                                            $contador_exame++;
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </fieldset>
                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de especialidades</font></b></legend>
                                                <div>

                                                    <?
                                                    $contador_especialidade = 0;
                                                    foreach ($historicoespecialidade as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebesp[$contador_especialidade])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_especialidade ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebesp[$contador_especialidade])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>


                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <?
                                                                    $this->load->helper('directory');
                                                                    $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$especialidade_id/");
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        sort($arquivo_pastaimagem);
                                                                    }
                                                                    $i = 0;
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        foreach ($arquivo_pastaimagem as $value) {
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td >Imagens : <font size="2"><b> <?= $i ?></b>
                                                                        <?
                                                                        if ($arquivo_pastaimagem != false):
                                                                            foreach ($arquivo_pastaimagem as $value) {
                                                                                ?>
                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                                                                                <?
                                                                            }
                                                                            $arquivo_pastaimagem = "";
                                                                        endif
                                                                        ?>
                                                                        <!--                <ul id="sortable">
                                
                                                                                        </ul>-->
                                                                    </td >
                                                                </tr>
                                                                <tr>
                                                                    <td >Laudo: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):

                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                        border-bottom:none;mso-border-top-alt:none;border-left:
                                                                        none;border-right:none;' colspan="10">&nbsp;</th>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    <? }
                                                    ?>
                                                    <?
                                                    if (count($historico) == 0 || $contador_especialidade < count($historicowebesp)) {
                                                        while ($contador_especialidade < count($historicowebesp)) {
                                                            ?>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                            <hr>

                                                            <?
                                                            $contador_especialidade++;
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </fieldset>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div id="tabs-3">
                                <script>
                                    $(function () {
                                    $("#tabsn").tabs();
                                    });
                                    $(".tab").tabs("option", "active", 1);
                                </script>                                
                                <fieldset>
                                    <div id="tabsn">
                                        <ul>
                                            <li><a class="tab" href="#tabs-a">Todos</a></li>
                                            <li><a href="#tabs-b">Por Data</a></li>
                                            <li><a href="#tabs-c">Rotinas</a></li>
                                            <li><a href="#tabs-d">Especial</a></li>
                                            <li><a href="#tabs-e">Laudo</a></li>
                                        </ul>
                                        <style>
                                            .row{
                                                display: flex;
                                            }
                                            .col{
                                                flex: 1;
                                                align-self: center;
                                            }

                                        </style> 
                                        <div id="tabs-a">
                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de consultas</font></b></legend>
                                                <div>
                                                    <?
// Esse código serve para mostrar os históricos que foram importados
// De outro sistema STG.
// Na hora que o médico finaliza o atendimento, o sistema manda os dados para o endereço do sistema
// Digitado no cadastro do médico, caso exista ele salva numa tabela especifica.
// Para não criar um outro local onde iriam aparecer os atendimentos dessa tabela 
// Há essa lógica aqui embaixo para inserir no meio dos outros atendimentos da ambulatorio_laudo os outros
// da integração
                                                    $contador_teste = 0;
// Contador para utilizar no array
//                            $historico = array();
                                                    foreach ($historico as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebcon[$contador_teste])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <?
                                                                        $idade = 0;
                                                                        $dataFuturo2 = $historicowebcon[$contador_teste]->data;
                                                                        $dataAtual2 = @$obj->_nascimento;
                                                                        if ($dataAtual2 != '') {
                                                                            $date_time2 = new DateTime($dataAtual2);
                                                                            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                                                                            $teste2 = $diff2->format('%Y');
                                                                            $idade = $teste2;
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td >Idade no atendimento: <?= $idade ?> Anos</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Evolução: <?= $historicowebcon[$contador_teste]->enfermagem; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_teste ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebcon[$contador_teste])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <?
                                                                $idade = 0;
                                                                $dataFuturo2 = $item->data_cadastro;
                                                                $dataAtual2 = @$obj->_nascimento;
                                                                if ($dataAtual2 != '') {
                                                                    $date_time2 = new DateTime($dataAtual2);
                                                                    $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                                                                    $teste2 = $diff2->format('%Y');
                                                                    $idade = $teste2;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td >Idade no atendimento: <?= $idade ?> Anos</td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Evolução: <?= $item->enfermagem; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Queixa principal: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):
                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <hr>
                                                    <? }
                                                    ?>
                                                </div>
                                                <?
                                                if (count($historico) == 0 || $contador_teste < count($historicowebcon)) {
                                                    while ($contador_teste < count($historicowebcon)) {
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td><span style="color: #007fff">Integração</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                        <hr>

                                                        <?
                                                        $contador_teste++;
                                                    }
                                                }
                                                ?>



                                            </fieldset>

                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de exames</font></b></legend>
                                                <div>

                                                    <?
                                                    $contador_exame = 0;
                                                    foreach ($historicoexame as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebexa[$contador_exame])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_exame ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebexa[$contador_exame])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>


                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <?
                                                                    $this->load->helper('directory');
                                                                    $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        sort($arquivo_pastaimagem);
                                                                    }
                                                                    $i = 0;
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        foreach ($arquivo_pastaimagem as $value) {
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td >Imagens : <font size="2"><b> <?= $i ?></b>
                                                                        <?
                                                                        if ($arquivo_pastaimagem != false):
                                                                            foreach ($arquivo_pastaimagem as $value) {
                                                                                ?>
                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                                                                                <?
                                                                            }
                                                                            $arquivo_pastaimagem = "";
                                                                        endif
                                                                        ?>
                                                                        <!--                <ul id="sortable">
                                
                                                                                        </ul>-->
                                                                    </td >
                                                                </tr>
                                                                <tr>
                                                                    <td >Laudo: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):

                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                        border-bottom:none;mso-border-top-alt:none;border-left:
                                                                        none;border-right:none;' colspan="10">&nbsp;</th>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    <? }
                                                    ?>
                                                    <?
                                                    if (count($historico) == 0 || $contador_exame < count($historicowebexa)) {
                                                        while ($contador_exame < count($historicowebexa)) {
                                                            ?>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                            <hr>

                                                            <?
                                                            $contador_exame++;
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </fieldset>
                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de especialidades</font></b></legend>
                                                <div>

                                                    <?
                                                    $contador_especialidade = 0;
                                                    foreach ($historicoespecialidade as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebesp[$contador_especialidade])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_especialidade ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebesp[$contador_especialidade])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>


                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <?
                                                                    $this->load->helper('directory');
                                                                    $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$especialidade_id/");
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        sort($arquivo_pastaimagem);
                                                                    }
                                                                    $i = 0;
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        foreach ($arquivo_pastaimagem as $value) {
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td >Imagens : <font size="2"><b> <?= $i ?></b>
                                                                        <?
                                                                        if ($arquivo_pastaimagem != false):
                                                                            foreach ($arquivo_pastaimagem as $value) {
                                                                                ?>
                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                                                                                <?
                                                                            }
                                                                            $arquivo_pastaimagem = "";
                                                                        endif
                                                                        ?>
                                                                        <!--                <ul id="sortable">
                                
                                                                                        </ul>-->
                                                                    </td >
                                                                </tr>
                                                                <tr>
                                                                    <td >Laudo: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):

                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                        border-bottom:none;mso-border-top-alt:none;border-left:
                                                                        none;border-right:none;' colspan="10">&nbsp;</th>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    <? }
                                                    ?>
                                                    <?
                                                    if (count($historico) == 0 || $contador_especialidade < count($historicowebesp)) {
                                                        while ($contador_especialidade < count($historicowebesp)) {
                                                            ?>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                            <hr>

                                                            <?
                                                            $contador_especialidade++;
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </fieldset>
                                        </div>
                                        <div id="tabs-b">
                                            <table>
                                                <tr height='100px'>
                                                    <td>
                                                        <table>
                                                            <?
                                                            $hitoricototal = $this->laudo->listardatashistorico($paciente_id);
                                                            ?>                                                                
                                                            <tr>
                                                                <td>                                                                    
                                                                    <select name="datahist" id="datahist" class="size2">
                                                                        <option value="">Selecione</option>
                                                                        <? $datahist = '00/00/0000'; ?>
                                                                        <? foreach ($hitoricototal as $item) : ?>

                                                                            <? if (date("d/m/Y", strtotime($item->data_cadastro)) != $datahist) { ?>
                                                                                <? $datahist = date("d/m/Y", strtotime($item->data_cadastro)); ?>
                                                                                <option value="<?= date("d/m/Y", strtotime($item->data_cadastro)); ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></option>
                                                                                <? // var_dump($datahist);var_dump(date("d/m/Y", strtotime($item->data_cadastro)));die;  ?>
                                                                            <? } ?>
                                                                        <? endforeach; ?>
                                                                    </select>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                </tr>
                                                <style>
                                                    .row{
                                                        display: flex;
                                                    }
                                                    .col{
                                                        flex: 1;
                                                        align-self: center;
                                                    }

                                                </style> 
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col">
                                                                <table>
                                                                    <tr>
                                                                        <td rowspan="11" >
                                                                            <div id="pordata">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="tabs-c">
                                            <?
                                            if (count($rotina) > 0) {
                                                ?>
                                                <table id="table_agente_toxico" border="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="tabela_header">Data</th>
                                                            <!--<th class="tabela_header">Procedimento</th>-->
                                                            <th class="tabela_header">Médico</th>
                                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>                                                            
                                                        </tr>
                                                    </thead>
                                                    <?
                                                    $estilo_linha = "tabela_content01";
                                                    foreach ($rotina as $item) {
                                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>

                                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>                                                               

                                                            </tr>

                                                        </tbody>
                                                        <?
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <div id="tabs-d">
                                            <?
                                            if (count($receita) > 0) {
                                                ?>
                                                <table id="table_agente_toxico" border="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="tabela_header">Data</th>
                                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>                                                            
                                                        </tr>
                                                    </thead>
                                                    <?
                                                    $estilo_linha = "tabela_content01";
                                                    foreach ($receita as $item) {
                                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>                                                               

                                                            </tr>

                                                        </tbody>
                                                        <?
                                                    }
                                                }
                                                ?>

                                            </table> 
                                        </div>
                                        <div id="tabs-e">
                                            <fieldset>
                                                <legend><b><font size="3" color="red">Historico de exames</font></b></legend>
                                                <div>

                                                    <?
                                                    $contador_exame = 0;
                                                    foreach ($historicoexame as $item) {
                                                        // Verifica se há informação
                                                        if (isset($historicowebexa[$contador_exame])) {
                                                            // Define as datas
                                                            $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                                                            $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data));
                                                            // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                                                            while ($data_while > $data_foreach) {
                                                                ?>

                                                                <table>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                                <?
                                                                $contador_exame ++;
                                                                // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                                                                if (isset($historicowebexa[$contador_exame])) {
                                                                    $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data_cadastro));
                                                                } else {
                                                                    // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <table>
                                                            <tbody>


                                                                <tr>
                                                                    <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $item->medico; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Tipo: <?= $item->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <?
                                                                    $this->load->helper('directory');
                                                                    $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        sort($arquivo_pastaimagem);
                                                                    }
                                                                    $i = 0;
                                                                    if ($arquivo_pastaimagem != false) {
                                                                        foreach ($arquivo_pastaimagem as $value) {
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td >Imagens : <font size="2"><b> <?= $i ?></b>
                                                                        <?
                                                                        if ($arquivo_pastaimagem != false):
                                                                            foreach ($arquivo_pastaimagem as $value) {
                                                                                ?>
                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                                                                                <?
                                                                            }
                                                                            $arquivo_pastaimagem = "";
                                                                        endif
                                                                        ?>
                                                                        <!--                <ul id="sortable">
                                
                                                                                        </ul>-->
                                                                    </td >
                                                                </tr>
                                                                <tr>
                                                                    <td >Laudo: <?= $item->texto; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Arquivos anexos:
                                                                        <?
                                                                        $this->load->helper('directory');
                                                                        $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                                                        $w = 0;
                                                                        if ($arquivo_pasta != false):

                                                                            foreach ($arquivo_pasta as $value) :
                                                                                $w++;
                                                                                ?>

                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                                                                <?
                                                                                if ($w == 8) {
                                                                                    
                                                                                }
                                                                            endforeach;
                                                                            $arquivo_pasta = "";
                                                                        endif
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                        border-bottom:none;mso-border-top-alt:none;border-left:
                                                                        none;border-right:none;' colspan="10">&nbsp;</th>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    <? }
                                                    ?>
                                                    <?
                                                    if (count($historico) == 0 || $contador_exame < count($historicowebexa)) {
                                                        while ($contador_exame < count($historicowebexa)) {
                                                            ?>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                            <hr>

                                                            <?
                                                            $contador_exame++;
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </fieldset>
                                        </div>   

                                    </div>
                                </fieldset>
                            </div>
                            <div id="tabs-4">
                                <table>
                                    <tr>
                                        <td rowspan="11" >
                                            <textarea class="tinymce" id="dados" name="dados" rows="30" cols="80" style="width: 100%"><?= @$obj->_dados; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="tabs-5">
                            <!-- <form name="form_receituario" id="form_receituario" action="<?= base_url() ?>ambulatorio/laudo/gravarreceituario/<?= $ambulatorio_laudo_id ?>" method="post"> -->
                                <fieldset>
                                    <legend>Receituário</legend>
                                    <div>
                                        <label>Modelos</label>
                                        <select name="modeloreceita" id="modeloreceita" class="size2" >
                                            <option value='' >Selecione</option>
                                            <?php foreach ($listareceita as $item) { ?>
                                                <option value="<?php echo $item->ambulatorio_modelo_receita_id; ?>" ><?php echo $item->nome; ?></option>
                                            <?php } ?>
                                        </select>

                                        <label>Medicamento</label>
                                        <input type="text" id="medicamento" class="texto02" name="medicamento"/>

                                        <label>Carimbo</label>
                                        <input type="checkbox" id="carimbo_receituario"  name="carimbo_receituario"/>

                                        <label>Assinatura</label>
                                        <input type="checkbox" id="assinatura_receituario" name="assinatura_receituario"/>
                                        
                                        <label>Receituário Especial?</label>
                                        <input type="checkbox" id="receituario_especial" name="receituario_especial"/>

                                    </div>
                                    <div>
                                        <input type="hidden" id="ambulatorio_laudo_id" name="ambulatorio_laudo_id" value="<?= $ambulatorio_laudo_id ?>"/>
                                        
                                    </div>
                                    <div>
                                        <textarea class="tinymce" id="receituario" name="receituario" rows="25" cols="80" style="width: 80%">
                                            
                                        </textarea>
                                    </div>
                                    <hr>
                                    
                                    <!-- <button type="button" id="btnReceituario" name="btnReceituario">Salvar</button> -->
                                </fieldset>
                                <?
                    if (count($receita) > 0) {
                        ?>
                        <table id="table_agente_toxico" border="0">
                            <thead>
                                <tr>
                                    <th class="tabela_header">Data</th>
                                    <!--<th class="tabela_header">Procedimento</th>-->
                                    <th class="tabela_header">Médico</th>
                                    <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                    <th colspan="6" class="tabela_header">&nbsp;</th>
                                </tr>
                            </thead>
                            <?
                            $estilo_linha = "tabela_content01";
                            foreach ($receita as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                    <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td> -->
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <?php 
                                              if ($item->especial == 't') {
                                                  ?>
                                                 <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Visualizar
                                                   </a>
                                                <?
                                              }else{  
                                                ?>                     
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Visualizar
                                                </a>
                                              <?php }?>

                                            </div>
                                        </td>

                                        <? 
                                        //if (@$obj->_status != 'FINALIZADO'){ 
                                            if(@$obj->_medico_parecer1 == $item->operador_id){ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarcarregarreceituario/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_receituario_id; ?>');">Editar
                                                </a></div>
                                            </td>
                                        <? } else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"></td>
                                        <? }
                                        //}?>
                                    
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="repetir(<?=$item->ambulatorio_receituario_id;?>)">Repetir
                                                </a></div>
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                        <input type="number" name="meses_receita" id="meses_receita_<?=$item->ambulatorio_receituario_id;?>" class="texto01"> 
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="100px;"> 
                                        <div class="bt_link">
                                                <a onclick="repetirpormes(<?=$item->ambulatorio_receituario_id;?>)">Repetir / Mês
                                                </a></div>
                                        </td>

                                    </tr>

                                </tbody>
                                <?
                            }
                        }
                        ?> 
                        </table>  
                    </div> 
                        <div id="tabs-6">
                            <fieldset>
                                <legend>Solicitar Exame</legend>
                                <div>
                                    <label>Modelos</label>
                                    <select name="modeloexame" id="modeloexame" class="size2" >
                                        <option value='' >Selecione</option>
                                        <?php foreach ($listaexames as $item) { ?>
                                            <option value="<?php echo $item->ambulatorio_modelo_solicitar_exames_id; ?>" ><?php echo $item->nome; ?></option>
                                        <?php } ?>
                                    </select>

                                    <label>Carimbo</label>
                                    <input type="checkbox" id="carimbo_exame"  name="carimbo_exame"/>

                                    <label>Assinatura</label>
                                    <input type="checkbox" id="assinatura_exame" name="assinatura_exame"/>

                                </div>
                                
                                <div>
                                    <textarea class="tinymce" id="solicitar_exames" name="solicitar_exames" rows="20" cols="80" style="width: 80%"></textarea></td>
                                </div>

                                <hr>
                            </fieldset>
                            <?
            if (count($solicitarexames) > 0) {
                ?>
                <table id="table_agente_toxico" border="0">
                    <thead>
                        <tr>
                            <th class="tabela_header">Data</th>                            
                            <th class="tabela_header">Médico</th>
                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                            <th colspan="3" class="tabela_header">&nbsp;</th>
                        </tr>
                    </thead>
                    
                    <?
                    $estilo_linha = "tabela_content01";
                    foreach ($solicitarexames as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
//                        var_dump($item);die;
                        ?>
                        <tbody>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaosolicitarexame/<?= $item->ambulatorio_exame_id; ?>');">Visualizar
                                        </a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarexame/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_exame_id; ?>');">Editar
                                        </a></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a onclick="repetir(<?=$item->ambulatorio_exame_id;?>)">Repetir
                                        </a></div>
                                </td>

                            </tr>

                        </tbody>
                        <?
                    }?>
                    </table>
                <?}?>
                            
                            
                    </div>
                            
                    <div id="tabsArquivo">
                        <fieldset>
                            <legend>Arquivos</legend>
                            <div >
                                <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
                                <input type="file" multiple="" name="arquivos[]"/>
                                
                            </div>
                            <br>
                            <br>
                            <div >
                                <table>
                                    <tr>
                                    <?
                                    $i=0;
                                    if ($arquivos_anexados != false):
                                        foreach ($arquivos_anexados as $value) :
                                        $i++;
                                            ?>
                                    
                                    <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>','_blank','toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>"><br><? echo substr($value, 0, 10)?><br><a href="<?= base_url() ?>ambulatorio/laudo/excluirimagem/<?= $ambulatorio_laudo_id ?>/<?= $value ?>">Excluir</a></td>
                                        <?
                                        if($i == 8){
                                            ?>
                                            </tr><tr>
                                            <?
                                        }
                                        endforeach;
                                    endif
                                    ?>
                                </table>
                            </div> 
                        </fieldset>
                        <fieldset>
                            <legend><b><font size="3" color="red">Arquivos Anexados Paciente</font></b></legend>
                            <table>
                                <tr>
                                    <?
                                    $l = 0;
                                    if ($arquivos_paciente != false):
                                        foreach ($arquivos_paciente as $value) :
                                            $l++;
                                            ?>

                                            <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>"><br><? echo substr($value, 0, 10) ?><br><a target="_blank"  href="<?= base_url() ?>cadastros/pacientes/excluirimagemlaudo/<?= $paciente_id ?>/<?= $value ?>">Excluir</a></td>
                                            <?
                                            if ($l == 8) {
                                                ?>
                                            </tr><tr>
                                                <?
                                            }
                                        endforeach;
                                    endif
                                    ?>
                            </table>
                        </fieldset>
                        <br>
                        <br>
                        <fieldset>
                            <legend><b><font size="3" color="red">Digitaliza&ccedil;&otilde;es</font></b></legend>
                            <div>
                                <table>
                                    <tbody>

                                        <tr>
                                            <td>
                                                <?
                                                $this->load->helper('directory');
                                                $arquivo_pasta = directory_map("./upload/paciente/$paciente_id/");

                                                $w = 0;
                                                if ($arquivo_pasta != false):

                                                    foreach ($arquivo_pasta as $value) :
                                                        $w++;
                                                        ?>

                                                    <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>"><br><? echo substr($value, 0, 10) ?></td>
                                                    <?
                                                    if ($w == 8) {
                                                        
                                                    }
                                                endforeach;
                                                $arquivo_pasta = "";
                                            endif
                                            ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </fieldset>
                    </div>
                    <div id="tabs-7">
                        <fieldset>
                            <legend>Adicionar Procedimentos</legend>

                            <fieldset>
                                <legend>Procedimentos</legend>
                                <input type="hidden" name="solicitacao_id" id="solicitacao_id" value="0"/>
                                <input type="hidden" name="paciente_id" id="paciente_id" value="<?=@$obj->_paciente_id?>"/>
                                <input type="hidden" name="convenio_id" id="convenio_id" value="<?=@$obj->_convenio_id?>"/>
                                <input type="hidden" name="medico_sadt" id="medico_sadt" value="<?=@$obj->_medico_parecer1?>" class="texto01"/>
                                <table>
                                    <?if($empresapermissao[0]->solicitar_sabin == 't'){?>
                                        <tr>
                                            <td>Quantidade</td>
                                            <td>
                                                <input type="text" name="quantidade" id="quantidade" value="1" class="texto01"/>
                                            </td>
                                        </tr>
                                    <?}else{?>
                                        <input type="hidden" name="quantidade" id="quantidade" value="1" class="texto01"/>
                                        <input type="hidden" name="direcao" id="direcao" value="2" class="texto01"/>
                                        
                                    <?}?>

                                    <?
                                    if (@$externo == "externo") {
                                        ?> 
                                        <tr>
                                            <td>Procedimento</td>
                                            <td>
                                                <input type="text" value="<?= $externo; ?>" name="externo" hidden>
                                                <input type="text" name="procedimentoescrito" required=""> 
                                            </td>
                                        </tr> 
                                        <?
                                    } else {
                                        ?>

                                        <tr>
                                            <td>Procedimento</td>
                                            <td>
                                                <select name="procedimento1" id="procedimento1" class="size4 chosen-select" tabindex="1" >
                                                    <option value="">Selecione</option>
                                                    <? foreach ($procedimentosadt as $item) { ?>
                                                        <option value="<?= $item->procedimento_convenio_id ?>"><?= $item->procedimento ?></option>
                                                    <? } ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <?
                                    }
                                    ?>
                                    <?if($empresapermissao[0]->solicitar_sabin == 't'){?>
                                        <tr>
                                            <td>Situação</td>
                                            <td>
                                                <select name="direcao" id="direcao" class="size2" tabindex="1" >
                                                    <option value="">Selecione</option>
                                                    <option value="1">Emergência</option>
                                                    <option value="2">Eletivo</option>                                     
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Justificativa</td>
                                            <td>

                                                <textarea name="justificativa" style="height:40px; width: 60%;"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Diagnóstico provável</td>
                                            <td>
                                                <textarea name="dig_provavel" style="height:40px; width: 60%;"></textarea>
                                            </td>
                                        </tr>
                                    <?}?>
                                    



                                    <!--<tr>-->
                                        <!--<td>Valor Unitario</td>-->
                                        <!--<td>-->

                                    <?
                                    if (@$externo == "externo") {
                                        ?>
                                        <input type="text"  alt="decimal" name="valor1" id="valor1" class="texto02"   hidden=""/>
                                        <?
                                    } else {
                                        ?>
                                        <input readonly="" type="text" name="valor1" id="valor1" <?
                                        if ($perfil_id != 1) {
                                            echo 'readonly';
                                        }
                                        ?> class="texto01" hidden=""/>

                                        <?
                                    }
                                    ?>


                                    <!--</td>-->
                                    <!--</tr>-->

                                </table>
                                <hr/>
                                <button type="button" name="adicionarProcedimento" onclick="enviarProcedimentoSADT();" id="adicionarProcedimento">Adicionar</button>
                                <table id="table_agente_toxico" border="0" style="width:600px">
                                    <thead>

                                        <tr>
                                            <th class="tabela_header">Procedimento</th>
                                            <th class="tabela_header">Quantidade</th>
                                            <th class="tabela_header">Convenio</th>
                                            <th class="tabela_header">Tipo</th>
                                            <th class="tabela_header">&nbsp;</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tabelaProcedimentos">

                                    </tbody>
                                </table>
                        </fieldset>
                        <div class="bt_link_new">
                            <a target href="#" onclick="imprimirsolicitacaoSADT();">
                                Visualizar
                            </a>
                        </div>
                        <?
                        $solicitante_id = @$obj->_medico_parecer1;
                        $convenio_id = @$obj->_convenio_id;
                        $solicitante_id = @$obj->_medico_parecer1;
                        ?>
                        <br><br>
                        <div class="bt_link_new">
                        <?if($empresapermissao[0]->solicitar_sabin == 't'){?>
                            <a href="<?= base_url() ?>ambulatorio/guia/gravarnovasolicitacaosadt/<?= $paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/<?=$ambulatorio_laudo_id?>/<?=@$obj->_sala_id?>/<? echo "externo"; ?>">
                                Guia Externo
                            </a>
                        <?}else{?>
                            <a target="_blank" href="<?= base_url() ?>ambulatorio/guia/imprimirguiasadtvazia/<?=$ambulatorio_laudo_id?>">
                                Guia Externo
                            </a>
                        <?}?>
                        </div>
                        
                        <fieldset>
                            <legend>Histórico de solicitações antigas</legend>
                            <table>
                                <tr>
                                    <th class="tabela_header">Solicitação</th>
                                    <th class="tabela_header">Convênio</th>
                                    <th class="tabela_header">Solicitante</th>
                                    <th class="tabela_header">Data</th>
                                    <th class="tabela_header" width="70px;" colspan="3"><center>Detalhes</center></th>
                                </tr>
                                </thead>
                                <?php
                                $total = count($guiahistorico);
                                if ($total > 0) {
                                    ?>
                                    <tbody>
                                        <?php
                                        $estilo_linha = "tabela_content01";
                    //                        echo "<pre>";
                    //                        print_r($guiahistorico);die;

                                        foreach ($guiahistorico as $item) {
                                            $atual = date("d/m/Y", strtotime($item->data_cadastro));
                                            if ($atual == date('d/m/Y')) {
                                                continue;
                                            }



                                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                            ?>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->solicitacao_sadt_id; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->solicitante ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y H:i:s", strtotime($item->data_cadastro)); ?></td>

                                                <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan="1">

                                                    <div class="bt_link" >


                                                        <?
                                                        if ($item->guia_externo == "t") {
                                                            ?>
                                                            <a href="<?= base_url() ?>ambulatorio/guia/cadastrarsolicitacaosadt/<?= $item->solicitacao_sadt_id ?>/<?= @$paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>/externo">Cadastrar</a> 
                                                            <?
                                                        } else {
                                                            ?>
                                                            <a href="<?= base_url() ?>ambulatorio/guia/cadastrarsolicitacaosadt/<?= $item->solicitacao_sadt_id ?>/<?= @$paciente_id ?>/<?= $convenio_id ?>/<?= $solicitante_id ?>">Cadastrar</a> 
                                                        <? } ?>


                                                </td>
                                                <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan="1"><div class="bt_link">
                                                        <a href="<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/<?= $item->solicitacao_sadt_id ?>">Visualizar</a></div>
                                                </td>



                                            </tr>
                                        <? } ?>


                                    </tbody>
                                <? } ?>
                                <tfoot>
                                    <tr>
                                        <th class="tabela_footer" colspan="8">
                                            Total de registros: <?php echo $total; ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </fieldset>
                            
                    </div>
                    <div id="tabsTomada">
                        <fieldset>
                            <legend>Adicionar Procedimentos</legend>

                            <fieldset>
                                <legend>Procedimentos</legend>
                                <input type="hidden" name="paciente_id" id="paciente_id" value="<?=@$obj->_paciente_id?>"/>
                                <input type="hidden" name="convenio_id" id="convenio_id" value="<?=@$obj->_convenio_id?>"/>
                                <input type="hidden" name="laudo_tomada_id" id="laudo_tomada_id" value="<?=@$obj->_ambulatorio_laudo_id?>"/>
                                <input type="hidden" name="medico_tomada" id="medico_tomada" value="<?=@$obj->_medico_parecer1?>" class="texto01"/>
                                <table>
                                    
                                    <tr>
                                        <td>Quantidade</td>
                                        <td>
                                            <input type="number" step="0.01" name="quantidade" id="quantidade_medicamento" value="1" class="texto01"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Medicamento</td>
                                        <td>
                                            <select name="medicamento_id" id="medicamento_id" class="size4 chosen-select" tabindex="1" >
                                                <option value="">Selecione</option>
                                                <? foreach ($medicamentos as $item) { ?>
                                                    <option value="<?= $item->medicamento_id ?>"><?= $item->nome . ' - ' . $item->posologia ?></option>
                                                <? } ?>
                                            </select>
                                        </td>
                                        </tr>

                                        <tr>
                                        <td>Periodo</td>
                                        <td>
                                            <select name="horario_texto" id="horario_texto"  >
                                                <option value="">Selecione</option>
                                                <option value="1">Após Café da Manhã</option>
                                                <option value="2">Antes do Almoço</option>
                                                <option value="3">Antes do Jantar</option>
                                                <option value="4">Ao Deitar</option>
                                            </select>
                                        </td>
                                        </tr>
                                        <tr>

                                        <td> Horario </td>
                                        <td> <input name="horario" id="horario"  class="texto01" alt="horario"> </td>
                                        </tr>
                                </table>
                                <hr/>
                                <button type="button" name="adicionarMedicamento" onclick="enviarMedicamentoSADT();" id="adicionarMedicamento">Adicionar</button>
                                <table id="table_agente_toxico" border="0" style="width:600px">
                                    <thead>

                                        <tr>
                                            <th class="tabela_header">Medicamento</th>
                                            <th class="tabela_header">Quantidade</th>
                                            <th class="tabela_header">Periodo</th>
                                            <th class="tabela_header">Horario</th>
                                            <th class="tabela_header">&nbsp;</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tabelaMedicamentos">

                                    </tbody>
                                </table>
                        </fieldset>
                        <fieldset>
                            <legend>Total</legend>
                            <table id="table_agente_toxico" border="0" style="width:600px">
                                <thead>

                                    <tr>
                                        <th class="tabela_header">Medicamento</th>
                                        <th class="tabela_header">Quantidade</th>

                                    </tr>
                                </thead>
                                <tbody id="tabelaMedicamentosTotal">

                                </tbody>
                            </table>
                        </fieldset>
                        <div class="bt_link_new">
                            <a target href="#" onclick="imprimirMedicamentosTomada();">
                                Visualizar
                            </a>
                        </div>
                    </div>
                        <div id="tabs-8">
                            <?
                            $receitaEspecial = false;
                            ?>
                            <h2>Visualizar Todos</h2>
                            <div class="bt_link_new">
                                <a onclick="abrirImpressaoTudo();" >
                                Visualizar Todos
                                </a>
                            </div>

                            <div class="bt_link_new">
                                <a title="Finalizar e Adicionar Assinatura Digital" onclick="abrirImpressaoTudo();" >
                                Finalizar Todos
                                </a>
                            </div>

                            <h2>Anamnese</h2>
                            <div class="bt_link_new">
                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');" >
                                Visualizar
                                </a>
                            </div>
                            <h2>Solicitação de Exames</h2>
                            <?
                            if (count($solicitarexames) > 0) {
                            ?>
                                <table id="table_agente_toxico" border="0" style="width: 900px;">
                                    <thead>
                                        <tr>
                                            <th class="tabela_header">Data</th>                            
                                            <th class="tabela_header">Médico</th>
                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                            <th colspan="3" class="tabela_header">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <?
                                    $estilo_linha = "tabela_content01";
                                    foreach ($solicitarexames as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                //                        var_dump($item);die;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaosolicitarexame/<?= $item->ambulatorio_exame_id; ?>');">Visualizar
                                                        </a></div>
                                                </td>
                                                

                                            </tr>

                                        </tbody>
                                        <?
                                    }?>
                                    </table>
                                <?}?>

                            <?
                            if (count($terapeuticas) > 0) {
                            ?>
                                <h2>Terapeuticas</h2>

                                <table id="table_agente_toxico" border="0" style="width: 900px;">
                                    <thead>
                                        <tr>
                                            <th class="tabela_header">Data</th>                            
                                            <th class="tabela_header">Médico</th>
                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                            <th colspan="3" class="tabela_header">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <?
                                    $estilo_linha = "tabela_content01";
                                    foreach ($terapeuticas as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                //                        var_dump($item);die;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoteraupetica/<?= $item->ambulatorio_terapeutica_id; ?>');">Visualizar
                                                        </a></div>
                                                </td>
                                                

                                            </tr>

                                        </tbody>
                                        <?
                                    }?>
                                    </table>
                                <?}?>

                                <?
                            if (count($relatorios) > 0) {
                            ?>
                                <h2>Relatorios</h2>

                                <table id="table_agente_toxico" border="0" style="width: 900px;">
                                    <thead>
                                        <tr>
                                            <th class="tabela_header">Data</th>                            
                                            <th class="tabela_header">Médico</th>
                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                            <th colspan="3" class="tabela_header">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <?
                                    $estilo_linha = "tabela_content01";
                                    foreach ($relatorios as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                //                        var_dump($item);die;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaorelatorio/<?= $item->ambulatorio_relatorio_id; ?>');">Visualizar
                                                        </a></div>
                                                </td>
                                                

                                            </tr>

                                        </tbody>
                                        <?
                                    }?>
                                    </table>
                                <?}?>


                            <h2>Receituário</h2>
                            <?
                    if (count($listareceitalaudo) > 0) {
                        ?>
                        <table id="table_agente_toxico" border="0" style="width: 900px;">
                            <thead>
                                <tr>
                                    <th class="tabela_header">Data</th>
                                    <!--<th class="tabela_header">Procedimento</th>-->
                                    <th class="tabela_header">Médico</th>
                                    <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                    <th colspan="4" class="tabela_header">&nbsp;</th>
                                </tr>
                            </thead>
                            <?
                            $estilo_linha = "tabela_content01";
                            foreach ($receita as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                if($item->especial == 't'){
                                    $receitaEspecial = true;
                                }
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                    <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td> -->
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></td>
                                      
                                       <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <?php 
                                              if ($item->especial == 't') {
                                                  ?>
                                                 <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Visualizar
                                                   </a>
                                                <?
                                              }else{  
                                                ?>                     
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Visualizar
                                                </a>
                                              <?php }?>

                                            </div>
                                        </td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="repetir(<?=$item->ambulatorio_receituario_id;?>)">Repetir
                                                </a></div>
                                        </td>
                                        <? 
                                        if (@$obj->_status != 'FINALIZADO'){ 
                                            if(@$obj->_medico_parecer1 == $item->operador_id){ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarcarregarreceituario/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_receituario_id; ?>');">Editar
                                                </a></div>
                                            </td>
                                        <? } else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"></td>
                                        <? }
                                        }?>

                                    </tr>

                                </tbody>
                                <?
                            }?>
                        </table> 
                        <?}
                        ?>
                        </div>
                        <div id="tabs-9">
                            <fieldset>
                                <legend>Anotação Privada</legend>
                                <div>
                                    <textarea class="tinymce" id="anotacao_privada" name="anotacao_privada" rows="20" cols="80" style="width: 80%"></textarea></td>
                                </div>

                                <hr>
                            </fieldset>
                                <?
                                if (count($anotacaoprivada) > 0) {
                                ?>
                                <table id="table_agente_toxico" border="0">
                                    <thead>
                                        <tr>
                                            <th class="tabela_header">Data</th>                            
                                            <th class="tabela_header">Médico</th>
                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                        </tr>
                                    </thead>
                                    <?
                                    $estilo_linha = "tabela_content01";
                                    foreach ($anotacaoprivada as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                //                        var_dump($item);die;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                            </tr>
                                        </tbody>
                                        <?
                                    }?>
                                </table>
                                <?}?>
                        </div>
                                                    
                                                    
                          <div id="tabs-10">
                            <!-- <form name="form_receituario" id="form_receituario" action="<?= base_url() ?>ambulatorio/laudo/gravarreceituario/<?= $ambulatorio_laudo_id ?>" method="post"> -->
                              <fieldset>
                                    <legend>Receituário Simples</legend>
                                    <div>
                                        <label>Modelos</label>
                                        <select name="modeloreceitasimples" id="modeloreceitasimples" class="size2" >
                                            <option value='' >Selecione</option>
                                            <?php foreach ($listareceita as $item) { ?>
                                                <option value="<?php echo $item->ambulatorio_modelo_receita_id; ?>" ><?php echo $item->nome; ?></option>
                                            <?php } ?>
                                        </select>

                                        <label>Medicamento</label>
                                        <input type="text" id="medicamento_simples" class="texto02" name="medicamento_simples"/>

                                        <label>Carimbo</label>
                                        <input type="checkbox" id="carimbo_receituario_simples"  name="carimbo_receituario_simples"/>

                                        <label>Assinatura</label>
                                        <input type="checkbox" id="assinatura_receituario_simples" name="assinatura_receituario_simples"/>
                                        
<!--                                        <label>Receituário Especial?</label>
                                        <input type="checkbox" id="receituario_especial" name="receituario_especial"/>-->

                                    </div>
                                    <div>
                                        <input type="hidden" id="ambulatorio_laudo_id" name="ambulatorio_laudo_id" value="<?= $ambulatorio_laudo_id ?>"/> 
                                    </div>
                                    <div>
                                        <textarea class="tinymce" id="receituario_simples" name="receituario_simples" rows="25" cols="80" style="width: 80%">
                                            
                                        </textarea>
                                    </div>
                                    <hr>
                                    
                                    <!-- <button type="button" id="btnReceituario" name="btnReceituario">Salvar</button> -->
                                </fieldset>
                                <?
                    if (count($receita) > 0) {
                        ?>
                        <table id="table_agente_toxico" border="0">
                            <thead>
                                <tr>
                                    <th class="tabela_header">Data</th>
                                    <!--<th class="tabela_header">Procedimento</th>-->
                                    <th class="tabela_header">Médico</th>
                                    <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                    <th colspan="6" class="tabela_header">&nbsp;</th>
                                </tr>
                            </thead>
                            <?
                            $estilo_linha = "tabela_content01";
                            foreach ($receita as $item) {
                                if($item->especial == "t"){
                                    continue;
                                }
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                    <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td> -->
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <?php 
                                              if ($item->especial == 't') {
                                                  ?>
                                                 <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Visualizar
                                                   </a>
                                                <?
                                              }else{  
                                                ?>                     
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Visualizar
                                                </a>
                                              <?php }?>

                                            </div>
                                        </td>

                                        <? 
                                        //if (@$obj->_status != 'FINALIZADO'){ 
                                            if(@$obj->_medico_parecer1 == $item->operador_id){ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarcarregarreceituario/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_receituario_id; ?>');">Editar
                                                </a></div>
                                            </td>
                                        <? } else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"></td>
                                        <? }
                                        //}?>
                                    
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="repetir_simples(<?=$item->ambulatorio_receituario_id;?>)">Repetir
                                                </a></div>
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                        <input type="number" name="meses_receita_simples" id="meses_receita_simples<?=$item->ambulatorio_receituario_id;?>" class="texto01"> 
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="100px;"> 
                                        <div class="bt_link">
                                                <a onclick="repetirpormes_simples(<?=$item->ambulatorio_receituario_id;?>)">Repetir / Mês
                                                </a></div>
                                        </td>

                                    </tr>

                                </tbody>
                                <?
                            }
                        }
                        ?> 
                        </table>  
                    </div>                            
                               
                                                    
                    <div id="tabs-11">
                            <!-- <form name="form_receituario" id="form_receituario" action="<?= base_url() ?>ambulatorio/laudo/gravarreceituario/<?= $ambulatorio_laudo_id ?>" method="post"> -->
                              <fieldset>
                                    <legend>Receituário Especial</legend>
                                    <div>
                                        <label>Modelos</label>
                                        <select name="modeloreceita_especial" id="modeloreceita_especial" class="size2" >
                                            <option value='' >Selecione</option>
                                            <?php foreach ($listareceita as $item) { ?>
                                                <option value="<?php echo $item->ambulatorio_modelo_receita_id; ?>" ><?php echo $item->nome; ?></option>
                                            <?php } ?>
                                        </select>

                                        <label>Medicamento</label>
                                        <input type="text" id="medicamento" class="texto02" name="medicamento"/>

                                        <label>Carimbo</label>
                                        <input type="checkbox" id="carimbo_receituario_especial"  name="carimbo_receituario"/>

                                        <label>Assinatura</label>
                                        <input type="checkbox" id="assinatura_receituario_especial" name="assinatura_receituario"/>
                                          
                                    </div>
                                    <div>
                                        <input type="hidden" id="ambulatorio_laudo_id" name="ambulatorio_laudo_id" value="<?= $ambulatorio_laudo_id ?>"/> 
                                    </div>
                                    <div>
                                        <textarea class="tinymce" id="receituario_especial" name="receituario_especial" rows="25" cols="80" style="width: 80%">
                                            
                                        </textarea>
                                    </div>
                                    <hr>
                                    
                                    <!-- <button type="button" id="btnReceituario" name="btnReceituario">Salvar</button> -->
                                </fieldset>
                                <?
                    if (count($receita) > 0) {
                        ?>
                        <table id="table_agente_toxico" border="0">
                            <thead>
                                <tr>
                                    <th class="tabela_header">Data</th>
                                    <!--<th class="tabela_header">Procedimento</th>-->
                                    <th class="tabela_header">Médico</th>
                                    <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                    <th colspan="6" class="tabela_header">&nbsp;</th>
                                </tr>
                            </thead>
                            <?
                            $estilo_linha = "tabela_content01";
                            foreach ($receita as $item) {
                                if($item->especial != "t"){
                                    continue;
                                }
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                    <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td> -->
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <?php 
                                              if ($item->especial == 't') {
                                                  ?>
                                                 <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Visualizar
                                                   </a>
                                                <?
                                              }else{  
                                                ?>                     
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Visualizar
                                                </a>
                                              <?php }?>

                                            </div>
                                        </td>

                                        <? 
                                        //if (@$obj->_status != 'FINALIZADO'){ 
                                            if(@$obj->_medico_parecer1 == $item->operador_id){ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/editarcarregarreceituario/<?= $ambulatorio_laudo_id ?>/<?= $item->ambulatorio_receituario_id; ?>');">Editar
                                                </a></div>
                                            </td>
                                        <? } else{ ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="60px;"></td>
                                        <? }
                                        //}?>
                                    
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                                <a onclick="repetir_especial(<?=$item->ambulatorio_receituario_id;?>)">Repetir
                                                </a></div>
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="60px;">
                                        <input type="number" name="meses_receita_especial" id="meses_receita_especial<?=$item->ambulatorio_receituario_id;?>" class="texto01"> 
                                        </td>

                                        <td class="<?php echo $estilo_linha; ?>" width="100px;"> 
                                        <div class="bt_link">
                                                <a onclick="repetirpormes_especial(<?=$item->ambulatorio_receituario_id;?>)">Repetir / Mês
                                                </a></div>
                                        </td>

                                    </tr>

                                </tbody>
                                <?
                            }
                        }
                        ?> 
                        </table>  
                    </div>   
                                                    
                     <div id="tabs-12">
                         <style>
                             #fontatestado{
                                 font-size: 15px;
                                 padding: 7px;
                             }
                             .checkboxatestado{
                                    -ms-transform: scale(1.6); /* IE */
                                    -moz-transform: scale(1.6); /* FF */
                                    -webkit-transform: scale(1.6); /* Safari and Chrome */
                                    -o-transform: scale(1.6); /* Opera */
                                    transform: scale(1.6);
                                    padding: 10px;
                             }
                             table.linhasAlternadas {
                                border-collapse: collapse; /* CSS2 */
                                background: #FFFFF0;
                                border-right: solid black 1px; 
                            }
                         </style>
                         <?php 
                         switch (date("m")) {
                                case "01":    $mes = 'Janeiro';     break;
                                case "02":    $mes = 'Fevereiro';   break;
                                case "03":    $mes = 'Março';       break;
                                case "04":    $mes = 'Abril';       break;
                                case "05":    $mes = 'Maio';        break;
                                case "06":    $mes = 'Junho';       break;
                                case "07":    $mes = 'Julho';       break;
                                case "08":    $mes = 'Agosto';      break;
                                case "09":    $mes = 'Setembro';    break;
                                case "10":    $mes = 'Outubro';     break;
                                case "11":    $mes = 'Novembro';    break;
                                case "12":    $mes = 'Dezembro';    break; 
                         }

                                
                         ?>
                            <!-- <form name="form_receituario" id="form_receituario" action="<?= base_url() ?>ambulatorio/laudo/gravarreceituario/<?= $ambulatorio_laudo_id ?>" method="post"> -->
                              <fieldset>
                                    <legend>Atestado</legend>  
                                    <input type="hidden" id="agenda_exames_id" value="<?= $agenda_exames_id; ?>" >
                                    <table   width="100%">
                                        <tr>
                                            <td style="width: 53%;">
                                    <table  class="linhasAlternadas"  width="100%"  >
                                        <tr>
                                            <td id="fontatestado" style="width: 200px;text-align: center;"> 
                                                <div style="border-right:1px solid #eb4d4b;margin: 15px;">
                                                <img  width="100px" height="100px"   src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta_logo[0]}" ?>">
                                                <input type="hidden" id="empresa_id" name="empresa_id" value="<?= (@$empresa_id == "") ? $this->session->userdata('empresa_id') : $empresa_id; ?>" >
                                            </div>
                                            </td>
                                            <td colspan="2" id="fontatestado" style="text-align: center;">
                                                <b  >
                                             <?= $empresapermissao[0]->logradouro;?> <?= $empresapermissao[0]->numero;?> - <?= $empresapermissao[0]->municipio;?> <br>
                                             <?= $empresapermissao[0]->telefone; ?>   /   <?= $empresapermissao[0]->celular; ?> <br></b>
                                                <b style="font-family: arial;font-size: 14px; font-weight: normal;">
                                             <?= $empresapermissao[0]->site_empresa; ?> &nbsp;&nbsp;<img  style="width: 13px;  " src="<?= base_url(); ?>img/logo-facebook.png" > <?= $empresapermissao[0]->facebook_empresa; ?> &nbsp;&nbsp;<img  style="width: 15px;" src="<?= base_url(); ?>img/logo-instagram.png" >  <?= $empresapermissao[0]->instagram_empresa; ?>
                                            </b>
                                            </td>
                                        </tr>
                                        <tr>
                                             <td id="fontatestado" colspan="3" style="text-align: center; font-weight: bold;font-size: 20px;">ATESTADO</td> 
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="3"><b>O(a) Sr.(a)&nbsp;&nbsp;<u><?= @$obj->_nome ?></u>&nbsp;&nbsp;compareceu a esta Clínica às&nbsp;&nbsp; <u><?= date('H:i:s',strtotime($obj->_data_autorizacao)); ?></u> &nbsp;&nbsp;horas para: </b> <b><?= @$obj->_procedimento; ?></b> </td>
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="3" ></td> 
                                        </tr>
                                         <tr>
                                             <td id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                         <tr>
                                             <td id="fontatestado" colspan="3" ><b>Outrossim comunicamos que:</b></td> 
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="2" ><b>a. pode voltar em seguida ao trabalho  <u><?= date('H:i:s',strtotime($obj->_data_autorizacao)); ?></u> </b> </td> 
                                            <td  id="fontatestado" style="width: 20px;"><input id="voltaemseguidatrabalho" name="checkboxatestado" class="checkboxatestado" type="checkbox" ></td>
                                        </tr>
                                        <tr>
                                              <td id="fontatestado" colspan="2" ><b>b. deverá ficar afastado(a) no dia de hoje <u><?= date('d/m/Y'); ?></u></b>
                                               <input type="hidden" id="diaafastado" value="<?= date('d/m/Y'); ?>" ></td> 
                                              <td id="fontatestado"><input id="afastadohoje"  name="afastadohoje"  class="checkboxatestado"  type="checkbox" ></td>
                                        </tr>
                                          <tr>
                                              <td id="fontatestado" colspan="2" ><b>c. deverá ficar afastado(a) do trabalho &nbsp;&nbsp;&nbsp; (<input type="number" id="qtd_dias_afastado" class="texto01">) dias </b></td>
                                              <td id="fontatestado"><b></b></td>
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="3" ><b>a contar desta data <input type="text" id="data_afastamento"  value="<?= date('d/m/Y'); ?>"> </b></td> 
                                             
                                        </tr>
                                        <tr>
                                             <td  id="fontatestado" colspan="2" ><b>d. CID 
                                                 <input type="hidden" name="txtCICPrimarioAtestado" id="txtCICPrimarioAtestado"   class="size2" />
                                                 <input type="text" name="txtCICPrimariolabelAtestado" id="txtCICPrimariolabelAtestado"  class="size8" />
                                                 </b></td> 
                                                 <td id="fontatestado"> </td>
                                        </tr>
                                        <tr>
                                              <td id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                        <tr>
                                              <td  id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                       
                                        <tr>
                                              <td> </td> 
                                              <td colspan="2" id="fontatestado" style="text-align: right;"> <b><?= $empresapermissao[0]->municipio; ?>, <u><?= date('d');?></u> de <u><?= $mes; ?></u> , <u><?= date('Y'); ?></u></b>  
                                             </td> 
                                        </tr>
                                         <tr>
                                              <td id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>   
                                    </table> 
                                          </td>
                                          <td style="text-align: center; "  >
                                              
                                          </td>
                                        </tr>
                                        <tr>
                                         <td style="text-align: center; cursor:pointer;" onclick="imprimirAtestado()" >
                                             <div class="bt_link_new"> 
                                              <b style="font-size: 12px;"> Imprimir</b>
                                              </div>
                                          </td>
                                        </tr>
                                    </table>
                              </fieldset>
                                    
                     </div>
                    
                        
                    </div> 
                    
                   
                    
                    <hr>
                        
                        <?
                        $perfil_id = $this->session->userdata('perfil_id');
                        ?>
                        <div>
                            <label <?= ($perfil_id == 1) ? '' : "style='display:none;'"; ?> >M&eacute;dico respons&aacutevel</label>


                            <select name="medico" id="medico" class="size2" <?= ($perfil_id == 1) ? '' : "style='display:none;'"; ?>>
                                <? foreach ($operadores as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>"<?
                                    if (@$obj->_medico_parecer1 == $value->operador_id):echo "selected = 'true'";
                                    endif;
                                    ?>><?= $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>

                            <? if ($empresapermissao[0]->desativar_personalizacao_impressao != 't') { ?>
                                <?php
                                if (@$obj->_assinatura == "t") {
                                    ?>
                                    <input type="checkbox" name="assinatura" checked ="true" /><label>Assinatura</label>
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="assinatura"  /><label>Assinatura</label>
                                    <?php
                                }
                                ?>

                                <?php
                                if (@$obj->_rodape == "t") {
                                    ?>
                                    <input type="checkbox" name="rodape" checked ="true" /><label>Rodape</label>
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="rodape"  /><label>Rodape</label>
                                    <?php
                                }
                                ?>

                            <? } ?>
                            <label>situa&ccedil;&atilde;o</label>
                            <select name="situacao" id="situacao" class="size2" ">
                                <option value='DIGITANDO'<?
                                if (@$obj->_status == 'DIGITANDO'):echo "selected = 'true'";
                                endif;
                                ?> >DIGITANDO</option>
                                <option value='FINALIZADO' <?
                                if (@$obj->_status == 'FINALIZADO'):echo "selected = 'true'";
                                endif;
                                ?> >FINALIZADO</option>
                                <option value='PENDENCIA' <?
                                if (@$obj->_status == 'PENDENCIA'):echo "selected = 'true'";
                                endif;
                                ?> >PENDÊNCIA</option>
                                <option value='RECONVOCACAO' <?
                                if (@$obj->_status == 'RECONVOCACAO'):echo "selected = 'true'";
                                endif;
                                ?> >RECONVOCAÇÃO</option>
                            </select>
                            <input type="hidden" name="status" id="status" value="<?= @$obj->_status; ?>" class="size2" />

                            <label style="margin-left: 10pt" for="ret">Retorno?</label>
                            <input type="checkbox" name="ret" id="ret" <?= ( (int) @$obj->_dias_retorno != '0') ? 'checked' : '' ?>/>
                            <div style="display: inline-block" class="dias_retorno_div">
                                <input type="text" name="ret_dias" id="ret_dias" value="<?= @$obj->_dias_retorno; ?>" style="width: 80pt" />
                            </div>

                            <label style="margin-left: 10pt" for="rev">Revisão?</label>
                            <input type="checkbox" name="rev" id="rev" />
                            <div class="dias" style="display: inline">

                            </div>
                        </div>
                        <button type="submit" onclick="tinyMCE.triggerSave();" name="btnEnviar">Salvar</button>
                        <button type="submit" onclick="tinyMCE.triggerSave();" name="btnFinalizar">Salvar e Fechar</button>
                        <br>
                        
                </div>

            </div>

            <?
                if (@$empresapermissao[0]->modelos_atendimento != '') {
                    $modelo_atendimento = json_decode(@$empresapermissao[0]->modelos_atendimento);
                } else {
                    $modelo_atendimento = array();
                }

                // print_r($modelo_atendimento);
            ?>

            <table>
            <tr>
            <td>
            <div class="bt_link_new" style="display: inline-block">
                <a onclick="javascript:window.open('<?= base_url() ?>centrocirurgico/centrocirurgico/novasolicitacao/0/<?= $ambulatorio_laudo_id ?>');" >
                    Solicitar Cirurgia
                </a>
            </div>
            </td>

            <? if (in_array('receitas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloreceita/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelo Receitas </font>
                </a>
             </div>
            </td>
            <? } ?>


            <? if (in_array('receitas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloreceitaespecial/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelo R. Especial </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('S_exames', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelosolicitarexames/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelo S. Exames </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('teraupeticas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloterapeuticas/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  M. Terapeuticas </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('relatorio', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelorelatorio/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelo Relatorio </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('protocolo', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloprotocolo/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelo Protocolo </font>
                </a>
             </div>
            </td>
            <? } ?>

             </tr>
             </table>
                
                
            </form>

           
            <hr>
            </fieldset>
            <br>
            <br>
            <fieldset>
                <legend><b><font size="3" color="red">Arquivos Anexados Paciente</font></b></legend>
                <table>
                    <tr>
                        <?
                        $l = 0;
                        if ($arquivos_paciente != false):
                            foreach ($arquivos_paciente as $value) :
                                $l++;
                                ?>

                                <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>"><br><? echo substr($value, 0, 10) ?><br><a target="_blank"  href="<?= base_url() ?>cadastros/pacientes/excluirimagemlaudo/<?= $paciente_id ?>/<?= $value ?>">Excluir</a></td>
                                <?
                                if ($l == 8) {
                                    ?>
                                </tr><tr>
                                    <?
                                }
                            endforeach;
                        endif
                        ?>
                </table>
            </fieldset>
            <br>
            <br>
            <fieldset>
                <legend><b><font size="3" color="red">Arquivos Anexados Laudo</font></b></legend>
                <table>
                    <tr>
                        <?
                        $o = 0;
                        if ($arquivos_anexados != false):
                            foreach ($arquivos_anexados as $value) :
                                $o++;
                                ?>

                                <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>"><br><? echo substr($value, 0, 10) ?><br><a href="<?= base_url() ?>ambulatorio/laudo/excluirimagemlaudo/<?= $ambulatorio_laudo_id ?>/<?= $value ?>">Excluir</a></td>
                                <?
                                if ($o == 8) {
                                    ?>
                                </tr><tr>
                                    <?
                                }
                            endforeach;
                        endif
                        ?>
                </table>
            </fieldset>
            <br>
            <br>
            <!--            <fieldset>
                            <legend><b><font size="3" color="red">Historico de consultas</font></b></legend>
                            <div>
            <?
// Esse código serve para mostrar os históricos que foram importados
// De outro sistema STG.
// Na hora que o médico finaliza o atendimento, o sistema manda os dados para o endereço do sistema
// Digitado no cadastro do médico, caso exista ele salva numa tabela especifica.
// Para não criar um outro local onde iriam aparecer os atendimentos dessa tabela 
// Há essa lógica aqui embaixo para inserir no meio dos outros atendimentos da ambulatorio_laudo os outros
// da integração
            $contador_teste = 0;
// Contador para utilizar no array
//                            $historico = array();
            foreach ($historico as $item) {
                // Verifica se há informação
                if (isset($historicowebcon[$contador_teste])) {
                    // Define as datas
                    $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                    $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data));
                    // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                    while ($data_while > $data_foreach) {
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                <table>
                                                                                                                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                                                                                                                <hr>
                        <?
                        $contador_teste ++;
                        // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                        if (isset($historicowebcon[$contador_teste])) {
                            $data_while = date("Y-m-d", strtotime($historicowebcon[$contador_teste]->data_cadastro));
                        } else {
                            // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                            break;
                        }
                    }
                }
                ?>
                                                                                                                                <table>
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Medico: <?= $item->medico; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Tipo: <?= $item->procedimento; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Queixa principal: <?= $item->texto; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td>Arquivos anexos:
                <?
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                $w = 0;
                if ($arquivo_pasta != false):
                    foreach ($arquivo_pasta as $value) :
                        $w++;
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                        <?
                        if ($w == 8) {
                            
                        }
                    endforeach;
                    $arquivo_pasta = "";
                endif
                ?>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                                <hr>
            <? }
            ?>
                            </div>
            <?
            if (count($historico) == 0 || $contador_teste < count($historicowebcon)) {
                while ($contador_teste < count($historicowebcon)) {
                    ?>
                                                                                                                                                                                                                            <table>
                                                                                                                                                                                                                                <tbody>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                </tbody>
                                                                                                                                                                                                                            </table>
                                                                                                                                                                                                                            <hr>
                                                                                                                                                                                                    
                    <?
                    $contador_teste++;
                }
            }
            ?>
            
                            <div>
            <? foreach ($historicoantigo as $itens) {
                ?>
                                                                                                                                <table>
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td >Data: <?= substr($itens->data_cadastro, 8, 2) . "/" . substr($itens->data_cadastro, 5, 2) . "/" . substr($itens->data_cadastro, 0, 4); ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Queixa principal: <?= $itens->laudo; ?></td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                                <hr>
            <? }
            ?>
                            </div>
            
                        </fieldset>
            
                        <fieldset>
                            <legend><b><font size="3" color="red">Historico de exames</font></b></legend>
                            <div>
            
            <?
            $contador_exame = 0;
            foreach ($historicoexame as $item) {
                // Verifica se há informação
                if (isset($historicowebexa[$contador_exame])) {
                    // Define as datas
                    $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                    $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data));
                    // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                    while ($data_while > $data_foreach) {
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                <table>
                                                                                                                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                                                                                                                <hr>
                        <?
                        $contador_exame ++;
                        // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                        if (isset($historicowebexa[$contador_exame])) {
                            $data_while = date("Y-m-d", strtotime($historicowebexa[$contador_exame]->data_cadastro));
                        } else {
                            // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                            break;
                        }
                    }
                }
                ?>
                                                                                                                                <table>
                                                                                                                                    <tbody>
                                                                                                        
                                                                                                        
                                                                                                                                        <tr>
                                                                                                                                            <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Medico: <?= $item->medico; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Tipo: <?= $item->procedimento; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                <?
                $this->load->helper('directory');
                $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
                if ($arquivo_pastaimagem != false) {
                    sort($arquivo_pastaimagem);
                }
                $i = 0;
                if ($arquivo_pastaimagem != false) {
                    foreach ($arquivo_pastaimagem as $value) {
                        $i++;
                    }
                }
                ?>
                                                                                                                                            <td >Imagens : <font size="2"><b> <?= $i ?></b>
                <?
                if ($arquivo_pastaimagem != false):
                    foreach ($arquivo_pastaimagem as $value) {
                        ?>
                                                                                                                                                                                                                                                                                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                        <?
                    }
                    $arquivo_pastaimagem = "";
                endif
                ?>
                                                                                                                                                                <ul id="sortable">
                                                                                                        
                                                                                                                                                                </ul>
                                                                                                                                            </td >
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Laudo: <?= $item->texto; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td>Arquivos anexos:
                <?
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                $w = 0;
                if ($arquivo_pasta != false):

                    foreach ($arquivo_pasta as $value) :
                        $w++;
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                        <?
                        if ($w == 8) {
                            
                        }
                    endforeach;
                    $arquivo_pasta = "";
                endif
                ?>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                                                                                                border-bottom:none;mso-border-top-alt:none;border-left:
                                                                                                                                                none;border-right:none;' colspan="10">&nbsp;</th>
                                                                                                                                        </tr>
                                                                                                        
                                                                                                        
                                                                                                                                    </tbody>
                                                                                                                                </table>
            <? }
            ?>
            <?
            if (count($historico) == 0 || $contador_exame < count($historicowebexa)) {
                while ($contador_exame < count($historicowebexa)) {
                    ?>
                                                                                                                                                                                                                                <table>
                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Empresa: <?= $historicowebexa[$contador_exame]->empresa; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Data: <?= substr($historicowebexa[$contador_exame]->data, 8, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 5, 2) . "/" . substr($historicowebexa[$contador_exame]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Medico: <?= $historicowebexa[$contador_exame]->medico_integracao; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Tipo: <?= $historicowebexa[$contador_exame]->procedimento; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Queixa principal: <?= $historicowebexa[$contador_exame]->texto; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                <hr>
                                                                                                                                                                                                    
                    <?
                    $contador_exame++;
                }
            }
            ?>
                            </div>
            
                        </fieldset>
                        <fieldset>
                            <legend><b><font size="3" color="red">Historico de especialidades</font></b></legend>
                            <div>
            
            <?
            $contador_especialidade = 0;
            foreach ($historicoespecialidade as $item) {
                // Verifica se há informação
                if (isset($historicowebesp[$contador_especialidade])) {
                    // Define as datas
                    $data_foreach = date("Y-m-d", strtotime($item->data_cadastro));
                    $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data));
                    // Caso a data do Index atual da integracao seja maior que a data rodando no foreach, ele irá mostrar

                    while ($data_while > $data_foreach) {
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                <table>
                                                                                                                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td ><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                            <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                                                                                                                <hr>
                        <?
                        $contador_especialidade ++;
                        // Verifica se o próximo index existe e se sim, ele redefine a data_while pra poder rodar novamente o while
                        if (isset($historicowebesp[$contador_especialidade])) {
                            $data_while = date("Y-m-d", strtotime($historicowebesp[$contador_especialidade]->data_cadastro));
                        } else {
                            // Caso não exista ele simplesmente dá um break e deixa o foreach rodar
                            break;
                        }
                    }
                }
                ?>
                                                                                                                                <table>
                                                                                                                                    <tbody>
                                                                                                        
                                                                                                        
                                                                                                                                        <tr>
                                                                                                                                            <td >Data: <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Medico: <?= $item->medico; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Tipo: <?= $item->procedimento; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                <?
                $this->load->helper('directory');
                $arquivo_pastaimagem = directory_map("./upload/$item->exames_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$especialidade_id/");
                if ($arquivo_pastaimagem != false) {
                    sort($arquivo_pastaimagem);
                }
                $i = 0;
                if ($arquivo_pastaimagem != false) {
                    foreach ($arquivo_pastaimagem as $value) {
                        $i++;
                    }
                }
                ?>
                                                                                                                                            <td >Imagens : <font size="2"><b> <?= $i ?></b>
                <?
                if ($arquivo_pastaimagem != false):
                    foreach ($arquivo_pastaimagem as $value) {
                        ?>
                                                                                                                                                                                                                                                                                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $item->exames_id . "/" . $value ?>"></a>
                        <?
                    }
                    $arquivo_pastaimagem = "";
                endif
                ?>
                                                                                                                                                                <ul id="sortable">
                                                                                                        
                                                                                                                                                                </ul>
                                                                                                                                            </td >
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td >Laudo: <?= $item->texto; ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td>Arquivos anexos:
                <?
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                $w = 0;
                if ($arquivo_pasta != false):

                    foreach ($arquivo_pasta as $value) :
                        $w++;
                        ?>
                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                                <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                        <?
                        if ($w == 8) {
                            
                        }
                    endforeach;
                    $arquivo_pasta = "";
                endif
                ?>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <th style='width:10pt;border:solid windowtext 1.0pt;
                                                                                                                                                border-bottom:none;mso-border-top-alt:none;border-left:
                                                                                                                                                none;border-right:none;' colspan="10">&nbsp;</th>
                                                                                                                                        </tr>
                                                                                                        
                                                                                                        
                                                                                                                                    </tbody>
                                                                                                                                </table>
            <? }
            ?>
            <?
            if (count($historico) == 0 || $contador_especialidade < count($historicowebesp)) {
                while ($contador_especialidade < count($historicowebesp)) {
                    ?>
                                                                                                                                                                                                                                <table>
                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td><span style="color: #007fff">Integração</span></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Empresa: <?= $historicowebesp[$contador_especialidade]->empresa; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Data: <?= substr($historicowebesp[$contador_especialidade]->data, 8, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 5, 2) . "/" . substr($historicowebesp[$contador_especialidade]->data, 0, 4); ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Medico: <?= $historicowebesp[$contador_especialidade]->medico_integracao; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Tipo: <?= $historicowebesp[$contador_especialidade]->procedimento; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                            <td >Queixa principal: <?= $historicowebesp[$contador_especialidade]->texto; ?></td>
                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                    
                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                </table>
                                                                                                                                                                                                                                <hr>
                                                                                                                                                                                                    
                    <?
                    $contador_especialidade++;
                }
            }
            ?>
                            </div>
            
                        </fieldset>-->
            <fieldset>
                <legend><b><font size="3" color="red">Digitaliza&ccedil;&otilde;es</font></b></legend>
                <div>
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <?
                                    $this->load->helper('directory');
                                    $arquivo_pasta = directory_map("./upload/paciente/$paciente_id/");

                                    $w = 0;
                                    if ($arquivo_pasta != false):

                                        foreach ($arquivo_pasta as $value) :
                                            $w++;
                                            ?>

                                        <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/paciente/" . $paciente_id . "/" . $value ?>"><br><? echo substr($value, 0, 10) ?></td>
                                        <?
                                        if ($w == 8) {
                                            
                                        }
                                    endforeach;
                                    $arquivo_pasta = "";
                                endif
                                ?>
                                </td>
                            </tr>



                        </tbody>
                    </table>
                </div>

            </fieldset>

    </div> 
</div> 
</div> 
</div> <!-- Final da DIV content -->
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 1300px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<!--<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">-->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script> -->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui.js" ></script>


<script type="text/javascript">

    $(function () {
        $("#horario").mask("99:99");
    });

    $("#mostrarDadosExtras").click(function () {
            var botao = $("#mostrarDadosExtras").text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras").text('-');
            } else {
                $("#mostrarDadosExtras").text('+');
            }                                       
            $("#DadosExtras").toggle();

        });

    function abrirImpressaoTudo(){
        window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudotudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');
        window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitatodos/<?= $ambulatorio_laudo_id ?>');
        <?if($receitaEspecial){?>
            window.open('<?= base_url() ?>ambulatorio/laudo/imprimirReceitaEspecialTodos/<?= $ambulatorio_laudo_id ?>');
        <?}?> 
    }

    function enviarFormulario(){
        tinyMCE.triggerSave();
        saiuDoLaudo();
        $('#form_laudo').submit();
    }

    function saiuDoLaudo(){
        $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
        
        });
    }

    var submitted = false;

    $("#form_laudo").submit(function() {
     submitted = true;
     });

    window.onbeforeunload = function (e) {
    e = e || window.event;

    // For IE and Firefox prior to version 4
    if (e && !submitted) {
        e.returnValue = 'Sure?';
    }

    $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
        
    });

    if(!submitted){
        // For Safari
        return 'Sure?';
    }
};




    setTimeout(function(){
        timeout();
    }, 500);

    function timeout() {
    setTimeout(function () {
        tinyMCE.triggerSave();
        timeout();
    }, 1000);
}

   window.onload = function(){
   
        window.addEventListener("beforeunload", function(e){
        // Do something
            $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                
            });
        }, false);
        atualizarTextArea();       
//         document.getElementById('aih').onclick = function(){
// //                alert( tinyMCE.get('laudo').getContent() );
//                 var texto_antigo2  =  tinyMCE.get('laudo').getContent();
//                 <?= base_url() ?>ambulatorio/laudo/imprimirmodeloaih/<?= $ambulatorio_laudo_id ?>
                
//                  var texto_adicional_html2 = "</html>";
//                  var texto_adicional_body2 = "</body>";
//                  var texto_adicional_body3 = "<body>";
//                  var texto_adicional_html3 = "<html>";
//                   var texto_adicional_head2 = "<head>";
//                    var texto_adicional_head3 = "</head>";
//                    var texto_adicional_DOCTYPE2 = "<!DOCTYPE html>";
                 
//                  //aqui ele tira todas as tags html e body
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_head2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_head3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_DOCTYPE2, "");   
//                //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
//                    var colocartexto = texto_antigo2 ;
                     
//                       s = encodeURIComponent(colocartexto);
//                       testess =  s.replace(/~/g,'%7E').replace(/%20/g,'+');
// //                   alert(testess);
     
//                var ambulatorio_laudo_id = <?= $ambulatorio_laudo_id ?>;
//                url = '<?= base_url() ?>ambulatorio/laudo/imprimirmodeloaih/'+<?= $ambulatorio_laudo_id ?>+'?texto='+testess;
//                $("#aih").attr("href", url);
   
//         }
}
                                            
                                            
                                            jQuery('#rev').change(function () {
                                            if (this.checked) {
                                            var tag = '<input type="number" class="texto01" name="tempoRevisao"><label> Meses</label>';
                                            jQuery(".dias").append(tag);
                                            } else {
                                            jQuery(".dias span").remove();
                                            jQuery(".dias input").remove();
                                            jQuery(".dias label").remove();
                                            }
                                            });
<? if ((int) @$obj->_dias_retorno != '0') { ?>
                                                jQuery(".dias_retorno_div").show();
<? } else { ?>
                                                jQuery(".dias_retorno_div").hide();
<? } ?>

                                            jQuery('#ret').change(function () {
                                            if (this.checked) {
                                            jQuery(".dias_retorno_div").show();
                                            } else {
                                            jQuery(".dias_retorno_div").hide();
                                            }
                                            });
                                            // jQuery("#Altura").mask("999", {placeholder: " "});
                                            //                                                    jQuery("#Peso").mask("999", {placeholder: " "});

                                            ////////// ORDENANDO OS SELECTS DA OFTAMOLOGIA//////////////////

                                            function oftamologia_od_esferico() {
                                            var itensOrdenados = $('#oftamologia_od_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_esferico();
                                            function oftamologia_oe_esferico() {
                                            var itensOrdenados = $('#oftamologia_oe_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_esferico();
                                            function oftamologia_od_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_od_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_cilindrico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_cilindrico();
                                            function oftamologia_oe_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_oe_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_cilindrico ?>';
                                                $('#oftamologia_oe_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_cilindrico();
                                            function oftamologia_oe_eixo() {
                                            var itensOrdenados = $('#oftamologia_oe_eixo option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_eixo').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_eixo != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_eixo ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_eixo').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_eixo').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_eixo();
                                            function oftamologia_oe_av() {
                                            var itensOrdenados = $('#oftamologia_oe_av option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_av').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_av != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_av ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_av').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_av').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_av();
                                            function oftamologia_od_eixo() {
                                            var itensOrdenados = $('#oftamologia_od_eixo option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_eixo').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_eixo != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_eixo ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_eixo').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_eixo').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_eixo();
                                            function oftamologia_od_av() {
                                            var itensOrdenados = $('#oftamologia_od_av option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_av').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_av != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_av ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_av').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_av').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_av();
                                            function oftamologia_ad_esferico() {
                                            var itensOrdenados = $('#oftamologia_ad_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_ad_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_ad_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_ad_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_ad_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_ad_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_ad_esferico();
                                            function oftamologia_ad_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_ad_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_ad_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_ad_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_ad_cilindrico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_ad_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_ad_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_ad_cilindrico();
                                            function acuidade_oe() {
                                            var itensOrdenados = $('#acuidade_oe option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#acuidade_oe').html(itensOrdenados);
<? if (@$obj->_acuidade_oe != '') { ?>
                                                var teste = '<?= @$obj->_acuidade_oe ?>';
                                                //        alert(teste);
                                                $('#acuidade_oe').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#acuidade_oe').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            acuidade_oe();
                                            function acuidade_od() {
                                            var acuidade_oditensOrdenados = $('#acuidade_od option').sort(function (a, b) {
                                            //                        alert(b.text);
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            //        console.log(acuidade_oditensOrdenados);
                                            $('#acuidade_od').html(acuidade_oditensOrdenados);
<? if (@$obj->_acuidade_od != '') { ?>
                                                var teste = '<?= @$obj->_acuidade_od ?>';
                                                //        alert(teste);
                                                $('#acuidade_od').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#acuidade_od').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            acuidade_od();
                                            //////////////////////////////////////////////////



                                            function validar(dom, tipo) {
                                            switch (tipo) {
                                            case'num':
                                                    var regex = /[A-Za-z]/g;
                                            break;
                                            case'text':
                                                    var regex = /\d/g;
                                            break;
                                            }
                                            dom.value = dom.value.replace(regex, '');
                                            }


                                            pesob1 = document.getElementById('Peso').value;
                                            peso = parseFloat(pesob1.replace(',', '.'));
                                            //                                        peso = pesob1.substring(0, 2)  + "." + pesob1.substring(3, 1);
                                            alturae1 = document.getElementById('Altura').value;
                                            var res = alturae1.substring(0, 1) + "." + alturae1.substring(1, 3);
                                            var altura = parseFloat(res);
                                            imc = peso / Math.pow(altura, 2);
                                            //imc = res;
                                            resultado = imc.toFixed(2)
                                                    document.getElementById('imc').value = resultado.replace('.', ',');
                                            function calculaImc() {
                                            pesob1 = document.getElementById('Peso').value;
                                            peso = parseFloat(pesob1.replace(',', '.'));
                                            //                                        peso = pesob1.substring(0, 2)  + "." + pesob1.substring(3, 1);
                                            alturae1 = document.getElementById('Altura').value;
                                            var res = alturae1.substring(0, 1) + "." + alturae1.substring(1, 3);
                                            var altura = parseFloat(res);
                                            imc = peso / Math.pow(altura, 2);
                                            //imc = res;
                                            resultado = imc.toFixed(2)
                                                    document.getElementById('imc').value = resultado.replace('.', ',');
                                            }



                                            var sHors = "0" + 0;
                                            var sMins = "0" + 0;
                                            var sSecs = - 1;
                                            function getSecs() {
                                            sSecs++;
                                            if (sSecs == 60) {
                                            sSecs = 0;
                                            sMins++;
                                            if (sMins <= 9)
                                                    sMins = "0" + sMins;
                                            }
                                            if (sMins == 60) {
                                            sMins = "0" + 0;
                                            sHors++;
                                            if (sHors <= 9)
                                                    sHors = "0" + sHors;
                                            }
                                            if (sSecs <= 9)
                                                    sSecs = "0" + sSecs;
                                            clock1.innerHTML = sHors + "<font color=#000000>:</font>" + sMins + "<font color=#000000>:</font>" + sSecs;
                                            setTimeout('getSecs()', 1000);
                                            }


                                            $(document).ready(function () {
                                                buscarMedicamentosSADTAtend();
                                                buscarMedicamentosTomadaTotal();
                                            });
                                            $(document).ready(function () {
                                            $('#sortable').sortable();
                                            });
                                            $(document).ready(function () {
                                            jQuery('#ficha_laudo').validate({
                                            rules: {
                                            imagem: {
                                            required: true
                                            }
                                            },
                                                    messages: {
                                                    imagem: {
                                                    required: "*"
                                                    }
                                                    }
                                            });
                                            });
                                            function muda(obj) {
                                            if (obj.value != 'DIGITANDO') {
                                            document.getElementById('titulosenha').style.display = "block";
                                            document.getElementById('senha').style.display = "block";
                                            } else {
                                            document.getElementById('titulosenha').style.display = "none";
                                            document.getElementById('senha').style.display = "none";
                                            }
                                            }

                                   

                                    function repetir(receita_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituario', {receita: receita_id, ajax: true}, function (j) {
                                                options = ""; 
                                                options += j[0].texto;
                                                if(j[0].especial == 't'){
                                                  $('#receituario_especial').prop('checked', true);  
                                                }

                                                $('#receituario').val(options);
                                                var ed = tinyMCE.get('receituario');
                                                ed.setContent($('#receituario').val()); 
                                                    
                                        }); 
                                    }

                                    function repetirpormes(receita_id) {

                                        submitted = true;

                                        var meses =  $('#meses_receita_'+receita_id).val();

                                        if(meses == ''){
                                            alert('Quantidade de Meses não pode ser Vazio');
                                        }else if(meses == 0){
                                            alert('Quantidade de Meses não pode ser 0');
                                        }else{
                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituariopormes', {receita: receita_id, meses: meses, ajax: true}, function (j) {
                                                
                                                if(j == true){
                                                    $('#meses_receita_'+receita_id).val('');
                                                    window.location.reload();
                                                }             
                                        }); 
                                        }
                                        }
                                        
                                        
                                        
                                        
                                    function repetir_simples(receita_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituario', {receita: receita_id, ajax: true}, function (j) {
                                                options = "";

                                                options += j[0].texto;
                                                if(j[0].especial == 't'){
                                                  $('#receituario_especial').prop('checked', true);  
                                                }

                                                $('#receituario_simples').val(options);
                                                var ed = tinyMCE.get('receituario_simples');
                                                ed.setContent($('#receituario_simples').val());

                                                    
                                        }); 
                                    }

                                    function repetirpormes_simples(receita_id) {

                                        submitted = true;

                                        var meses =  $('#meses_receita_simples'+receita_id).val();

                                        if(meses == ''){
                                            alert('Quantidade de Meses não pode ser Vazio');
                                        }else if(meses == 0){
                                            alert('Quantidade de Meses não pode ser 0');
                                        }else{
                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituariopormes', {receita: receita_id, meses: meses, ajax: true}, function (j) {
                                                
                                                if(j == true){
                                                    $('#meses_receita_simples'+receita_id).val('');
                                                    window.location.reload();
                                                }             
                                        }); 
                                        }
                                     }
                                     
                                     
                                 function repetir_especial(receita_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituario', {receita: receita_id, ajax: true}, function (j) {
                                                options = "";

                                                options += j[0].texto;
                                                if(j[0].especial == 't'){
                                                  $('#receituario_especial').prop('checked', true);  
                                                }

                                                $('#receituario_especial').val(options);
                                                var ed = tinyMCE.get('receituario_especial');
                                                ed.setContent($('#receituario_especial').val());

                                                    
                                        }); 
                                    }

                                    function repetirpormes_especial(receita_id) {

                                        submitted = true;

                                        var meses =  $('#meses_receita_especial'+receita_id).val();

                                        if(meses == ''){
                                            alert('Quantidade de Meses não pode ser Vazio');
                                        }else if(meses == 0){
                                            alert('Quantidade de Meses não pode ser 0');
                                        }else{
                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituariopormes', {receita: receita_id, meses: meses, ajax: true}, function (j) {
                                                
                                                if(j == true){
                                                    $('#meses_receita_especial'+receita_id).val('');
                                                    window.location.reload();
                                                }             
                                        }); 
                                        }
                                     }

                                    function atualizarTextArea(){
                                        // console.log('aomething');
                                        tinyMCE.triggerSave();
                                    }

                                    function repetirexame(exame_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirexame', {exame_id: exame_id, ajax: true}, function (j) {
                                            options = "";

                                            options += j[0].texto;

                                            $('#solicitar_exames').val(options);
                                            var ed = tinyMCE.get('solicitar_exames');
                                            ed.setContent($('#solicitar_exames').val());

                                                
                                        }
                                     
                                    )}

                                    $(function () {
                                        $('#modeloexame').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelossolicitarexamesPreenchido', {exame: $(this).val(), ambulatorio_laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#solicitar_exames').val(options)
                                                    var ed = tinyMCE.get('solicitar_exames');
                                                    ed.setContent($('#solicitar_exames').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#solicitar_exames').html('value=""');
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#modeloreceita').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelosreceita', {exame: $(this).val(), ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#receituario').val(options);
                                                    var ed = tinyMCE.get('receituario');
                                                    ed.setContent($('#receituario').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#receituario').html('value=""');
                                            }
                                        });
                                    });
                                    
                                    
                                    $(function () {
                                        $('#modeloreceitasimples').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelosreceita', {exame: $(this).val(), ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#receituario_simples').val(options);
                                                    var ed = tinyMCE.get('receituario_simples');
                                                    ed.setContent($('#receituario_simples').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#receituario_simples').html('value=""');
                                            }
                                        });
                                    });
                                    
                                    
                                    $(function () {
                                        $('#modeloreceita_especial').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelosreceita', {exame: $(this).val(), ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#receituario_especial').val(options);
                                                    var ed = tinyMCE.get('receituario_especial');
                                                    ed.setContent($('#receituario_especial').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#receituario_especial').html('value=""');
                                            }
                                        });
                                    });
                                    
                                    $(function () {
                                        $('#btnReceituario').click(function () {
                                            // form_receituario
                                            // $('#form_receituario').submit();
                                        });
                                    });

                                   $(function () {
                                        $("#medicamento").autocomplete({
                                            source: "<?= base_url() ?>index.php?c=autocomplete&m=medicamentolaudo",
                                            minLength: 1,
                                            focus: function (event, ui) {
                                                $("#medicamento").val(ui.item.label);
                                                return false;
                                            },
                                            select: function (event, ui) {
                                                 $("#medicamento").val(ui.item.value);
                                            
                                                  var texto_antigo2 = tinyMCE.get('receituario').getContent();
                                                  var texto_adicional_html2 = "</html>";
                                                  var texto_adicional_body2 = "</body>";
                                                   //aqui ele tira todas as tags html e body
                                                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
                                                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
                                                     //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
                                                   var colocartexto = texto_antigo2 + ui.item.id;
                                                   var ed = tinyMCE.get('receituario');
                                                   ed.setContent(colocartexto); 
                                                   $("#medicamento").val(""); 
                                                return false;
                                            }
                                        });
                                    });

                                    function enviarProcedimentoSADT(){

                                        $.ajax({
                                                type: "POST",
                                                data: {
                                                        solicitacao_id: $("#solicitacao_id").val(),
                                                        solicitante_id: $("#medico_sadt").val(),
                                                        paciente_id: $("#paciente_id").val(),
                                                        convenio_id: $("#convenio_id").val(),
                                                        quantidade: $("#quantidade").val(),
                                                        procedimento1: $("#procedimento1").val(),
                                                        direcao: $("#direcao").val(),
                                                        justificativa: $("#justificativa").val(),
                                                        dig_provavel: $("#dig_provavel").val(),
                                                        valor1: $("#valor1").val(),
                                                    },
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: "<?= base_url() ?>ambulatorio/guia/gravarsolicitacaosadtatendi/",
                                                success: function (data) {
                                                    // console.log(data);
                                                    // alert('Operação efetuada com sucesso');
                                                    $("#solicitacao_id").val(data);
                                                    buscarProcedimentosSADTAtend();
                                                    // console.log($("#solicitacao_id").val());
                                                },
                                                error: function (data) {
                                                    alert('Erro ao chamar paciente');
                                                }
                                            });
                                    }

                                    function buscarProcedimentosSADTAtend(){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarProcedimentosSADTAtend', {solicitacao_id: parseInt($("#solicitacao_id").val()), ajax: true}, function (j) {
                                            table = "";
                                            // console.log(j);
                                            var situacao = '';
                                            for (var c = 0; c < j.length; c++) {
                                                table += "<tr>";
                                                if (j[c].emergencia == "t") {
                                                    situacao = "Emergência";
                                                } else if (j[c].eletivo == "t") {
                                                    situacao = "Eletivo";
                                                } else {
                                                    situacao = '';
                                                }
                                                table +="<td class='tabela_content01'>"+j[c].procedimento+"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].quantidade+"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].convenio+"</td>";
                                                table +="<td class='tabela_content01'>"+situacao+"</td>";
                                                table +="<td class='tabela_content01'><button type='button' onclick='apagarProcedimentoSADT("+parseInt($("#solicitacao_id").val())+","+j[c].solicitacao_sadt_procedimento_id+");'>Excluir</button></td>";
                                                table += "</tr>";
                                            }
                                            $('#tabelaProcedimentos tr').remove();
                                            $('#tabelaProcedimentos').append(table);
                                            
                                        });
                                    }

                                    function apagarProcedimentoSADT(solicitacao_id, id){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/excluirsolicitacaoprocedimentosadtatend/'+ solicitacao_id + '/' + id, {ajax: true}, function (j) {
                                            buscarProcedimentosSADTAtend();
                                        }); 
                                    }

                                    function imprimirsolicitacaoSADT(){
                                        solicitacao_id =  parseInt($("#solicitacao_id").val());
                                        if(solicitacao_id > 0){
                                            window.open('<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/' + solicitacao_id);
                                        }else{
                                            alert("Adicione um procedimento primeiro");
                                        }
                                       
                                    }

                                    function imprimirMedicamentosTomada(){
                                        laudo_id =  parseInt($("#laudo_tomada_id").val());
                                        
                                        window.open('<?= base_url() ?>ambulatorio/laudo/impressaoTomada/' + laudo_id);
                                        
                                       
                                    }

                                    function enviarMedicamentoSADT(){

                            $.ajax({
                                    type: "POST",
                                    data: {
                                            laudo_id: $("#laudo_tomada_id").val(),
                                            paciente_id: $("#paciente_id").val(),
                                            quantidade: $("#quantidade_medicamento").val(),
                                            medicamento_id: $("#medicamento_id").val(),
                                            horario_texto: $("#horario_texto").val(),
                                            horario: $("#horario").val()
                                        },
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: "<?= base_url() ?>ambulatorio/guia/gravarMedicamentoTomada/",
                                                success: function (data) {
                                                    // console.log(data);
                                                    // alert('Operação efetuada com sucesso');
                                                    buscarMedicamentosSADTAtend();
                                                    buscarMedicamentosTomadaTotal();
                                                    // console.log($("#solicitacao_id").val());
                                                },
                                                error: function (data) {
                                                    alert('Erro ao chamar paciente');
                                                }
                                            });
                                    }

        function buscarMedicamentosSADTAtend(){
            $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarMedicamentosTomada', {laudo_id: parseInt($("#laudo_tomada_id").val()), ajax: true}, function (j) {
                table = "";
                // console.log(j);
                var situacao = '';
                for (var c = 0; c < j.length; c++) {
                    var situacao = '';
                    table += "<tr>";
                    if (j[c].horario_texto == "1") {
                        situacao = "Após Café da Manhã";
                    } else if (j[c].horario_texto == "2") {
                        situacao = "Antes do Almoço";
                    } else if (j[c].horario_texto == "3"){
                        situacao = 'Antes do Jantar';
                    }else if (j[c].horario_texto == "4"){
                        situacao = 'Ao Deitar';
                    }


                    table +="<td class='tabela_content01'>"+j[c].medicamento+ " - " + j[c].posologia +"</td>";
                    table +="<td class='tabela_content01'>"+j[c].quantidade+"</td>";
                    table +="<td class='tabela_content01'>"+situacao+"</td>";
                    table +="<td class='tabela_content01'>"+j[c].horario+"</td>";
                    table +="<td class='tabela_content01'><button type='button' onclick='apagarMedicamentoTomada("+parseInt($("#laudo_tomada_id").val())+","+j[c].ambulatorio_laudo_tomada_id+");'>Excluir</button></td>";
                    table += "</tr>";
                }
                $('#tabelaMedicamentos tr').remove();
                $('#tabelaMedicamentos').append(table);
                
            });
        }
                                    function buscarMedicamentosTomadaTotal(){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarMedicamentosTomadaTotal', {laudo_id: parseInt($("#laudo_tomada_id").val()), ajax: true}, function (j) {
                                            table = "";
                                            // console.log(j);
                                            var situacao = '';
                                            for (var c = 0; c < j.length; c++) {
                                                table += "<tr>";
                                                if (j[c].emergencia == "t") {
                                                    situacao = "Emergência";
                                                } else if (j[c].eletivo == "t") {
                                                    situacao = "Eletivo";
                                                } else {
                                                    situacao = '';
                                                }
                                                table +="<td class='tabela_content01'>"+j[c].medicamento+ " - " + j[c].posologia +"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].quantidade+"</td>";
                                                table += "</tr>";
                                            }
                                            $('#tabelaMedicamentosTotal tr').remove();
                                            $('#tabelaMedicamentosTotal').append(table);
                                            
                                        });
                                    }

                                    function apagarMedicamentoTomada(laudo_tomada_id, id){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/excluirsolicitacaomedicamentoatend/'+ laudo_tomada_id + '/' + id, {ajax: true}, function (j) {
                                            buscarMedicamentosSADTAtend();
                                            buscarMedicamentosTomadaTotal();
                                        }); 
                                    }

                                    function imprimirsolicitacaoSADT(){
                                        solicitacao_id =  parseInt($("#solicitacao_id").val());
                                        if(solicitacao_id > 0){
                                            window.open('<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/' + solicitacao_id);
                                        }else{
                                            alert("Adicione um medicamento primeiro");
                                        }
                                       
                                    }





<? if (($endereco != '')) { ?>
    <?
    if ($obj->_cpf != '') {
        $cpf = $obj->_cpf;
    } else {
        $cpf = 'null';
    }
    $url_enviar_ficha = "$endereco/webService/telaAtendimento/enviarFicha/$obj->_toten_fila_id/$obj->_nome/$cpf/$obj->_medico_parecer1/$obj->_medico_nome/$obj->_toten_sala_id/false";
    ?>
                                                $("#botaochamar").click(function () {
                                                //    alert('<? //= $url_enviar_ficha        ?>');
                                                $.ajax({
                                                type: "POST",
                                                        data: {teste: 'teste'},
                                                        //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                        url: "<?= $url_enviar_ficha ?>",
                                                        success: function (data) {
                                                        //                console.log(data);
                                                        //                    alert(data.id);
                                                        $("#idChamada").val(data.id);
                                                        },
                                                        error: function (data) {
                                                        console.log(data);
                                                        //                alert('DEU MERDA');
                                                        }
                                                });
                                                setTimeout(enviarChamadaPainel, 2000);
                                                });
                                                function enviarChamadaPainel(){
                                                $.ajax({
                                                type: "POST",
                                                        data: {teste: 'teste'},
                                                        //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                        url: "<?= $endereco ?>/webService/telaChamado/proximo/<?= @$obj->_medico_parecer1 ?>/<?= @$obj->_toten_fila_id ?>/<?= @$obj->_toten_sala_id ?>",
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
                                                                    url: "<?= $endereco ?>/webService/telaChamado/cancelar/<?= @$obj->_toten_fila_id ?>",
                                                                                success: function (data) {

                                                                                //                            alert('Operação efetuada com sucesso');


                                                                                },
                                                                                error: function (data) {
                                                                                console.log(data);
                                                                                //                            alert('Erro ao chamar paciente');
                                                                                }
                                                                        });
                                                                        }
<? } ?>



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
                                                                    $("#txtCodigoTusslabel").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=procedimentotusspesquisa",
                                                                            minLength: 3,
                                                                            focus: function (event, ui) {
                                                                            $("#txtCodigoTusslabel").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#txtCodigoTusslabel").val(ui.item.value);
                                                                            $("#txtCodigoTuss").val(ui.item.id);
                                                                            //                                                                $("#txtcodigo").val(ui.item.codigo);
                                                                            //                                                                $("#txtdescricao").val(ui.item.descricao);
                                                                            return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $("#txtCICSecundariolabel").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=cid2",
                                                                            minLength: 3,
                                                                            focus: function (event, ui) {
                                                                            $("#txtCICSecundariolabel").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#txtCICSecundariolabel").val(ui.item.value);
                                                                            $("#txtCICSecundario").val(ui.item.id);
                                                                            return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    var readonly = <?= $readonly ?>;
                                                                    var readonlyadendo = <?= $readonlyadendo ?>;
                                                                    // NOVO TINYMCE

                                                                    // var specialChars = [
                                                                    // { text: 'exclamation mark', value: '!' },
                                                                    // { text: 'at', value: '@' },
                                                                    // { text: 'hash', value: '#' },
                                                                    // { text: 'dollars', value: '$' },
                                                                    // { text: 'percent sign', value: '%' },
                                                                    // { text: 'caret', value: '^' },
                                                                    // { text: 'ampersand', value: '&' },
                                                                    // { text: 'asterisk', value: '*' }
                                                                    // ];
                                                                    
                                                                    
                                                                    
                                                                    tinymce.init({ 
                                                                        selector: "div.edicao_rapida",
                                                                        plugins: [ 'quickbars' ],
                                                                        toolbar: false,
                                                                        menubar: false,
                                                                        inline: true
                                                                    });
                                                                    
                                                                        tinymce.init({
                                                                    selector: "#adendo",
                                                                            setup : function(ed)
                                                                            {
                                                                            ed.on('init', function()
                                                                            {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            ed.on('SetContent', function (e) {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            },
                                                                            language: 'pt_BR',
                                                                            readonly : readonlyadendo,
                                                                            // lists_indent_on_tab : false,
                                                                            // forced_root_block : '',
<? if (@$empresa[0]->impressao_laudo == 33) { ?>
                                                                        forced_root_block : '',
<? } ?>
                                                                    //                                                            browser_spellcheck : true,
                                                                    //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
                                                                    //                                                            nanospell_server: "php",
                                                                    //                                                            nanospell_dictionary: "pt_br" ,
                                                                    height: 450, // Pra tirar a lista automatica é só retirar o textpattern
                                                                            plugins: [
                                                                                    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                                    "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                            ],
                                                                            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                                                                            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
                                                                            // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                            menubar: false,
                                                                            toolbar_items_size: 'small',
                                                                            style_formats: [{
                                                                            title: 'Bold text',
                                                                                    inline: 'b'
                                                                            }, {
                                                                            title: 'Red text',
                                                                                    inline: 'span',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Red header',
                                                                                    block: 'h1',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Example 1',
                                                                                    inline: 'span',
                                                                                    classes: 'example1'
                                                                            }, {
                                                                            title: 'Example 2',
                                                                                    inline: 'span',
                                                                                    classes: 'example2'
                                                                            }, {
                                                                            title: 'Table styles'
                                                                            }, {
                                                                            title: 'Table row 1',
                                                                                    selector: 'tr',
                                                                                    classes: 'tablerow1'
                                                                            }],
                                                                            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
                                                                            templates: [{
                                                                            title: 'Test template 1',
                                                                                    content: 'Test 1'
                                                                            }, {
                                                                            title: 'Test template 2',
                                                                                    content: 'Test 2'
                                                                            }],
                                                                            init_instance_callback: function () {
                                                                            window.setTimeout(function () {
                                                                            $("#div").show();
                                                                            }, 1000);
                                                                            },
                                                                    });

                                                                    <? 
                                                                    $totalreceitas = count($listareceita);
                                                                    $qtde_receita = 0;
                                                                    ?>
                                                                    var receitas_tiny = [
                                                                    <?
                                                                        foreach($listareceita as $receita){
                                                                            $qtde_receita++;
                                                                            if($qtde_receita != $totalreceitas){
                                                                                echo "{ text: '$receita->nome', value: '#$receita->nome#'},";
                                                                            }else{
                                                                                echo "{ text: '$receita->nome', value: '#$receita->nome#'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalreceitas = count($listareceitaespecial);
                                                                    $qtde_receita = 0;
                                                                    ?>
                                                                    var receitas_especial_tiny = [
                                                                    <?
                                                                        foreach($listareceitaespecial as $receita){
                                                                            $qtde_receita++;
                                                                            if($qtde_receita != $listareceitaespecial){
                                                                                echo "{ text: '$receita->nome', value: '*$receita->nome*'},";
                                                                            }else{
                                                                                echo "{ text: '$receita->nome', value: '*$receita->nome*'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalexames = count($listaexames);
                                                                    $qtde_exames = 0;
                                                                    ?>
                                                                    var exames_tiny = [
                                                                    <?
                                                                        foreach($listaexames as $exames){
                                                                            $qtde_exames++;
                                                                            if($qtde_exames != $totalexames){
                                                                                echo "{ text: '$exames->nome', value: '@$exames->nome@'},";
                                                                            }else{
                                                                                echo "{ text: '$exames->nome', value: '@$exames->nome@'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalterapeuticas = count($listaterapeuticas);
                                                                    $qtde_terapeuticas = 0;
                                                                    ?>
                                                                    var terapeutica_tiny = [
                                                                    <?
                                                                        foreach($listaterapeuticas as $terapeuticas){
                                                                            $qtde_terapeuticas++;
                                                                            if($qtde_terapeuticas != $totalterapeuticas){
                                                                                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'},";
                                                                            }else{
                                                                                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalrelatorio = count($listarelatorios);
                                                                    $qtde_relatorio = 0;
                                                                    ?>
                                                                    var relatorio_tiny = [
                                                                    <?
                                                                        foreach($listarelatorios as $relatorio){
                                                                            $qtde_relatorio++;
                                                                            if($qtde_relatorio != $totalrelatorio){
                                                                                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'},";
                                                                            }else{
                                                                                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];

                                                                    <? 
                                                                    $totalprotocolo = count($listaprotocolos);
                                                                    $qtde_protocolo = 0;
                                                                    ?>
                                                                    var protocolo_tiny = [
                                                                    <?
                                                                        foreach($listaprotocolos as $protocolo){
                                                                            $qtde_protocolo++;
                                                                            if($qtde_protocolo != $totalprotocolo){
                                                                                echo "{ text: '$protocolo->nome', value: '!$protocolo->nome!'},";
                                                                            }else{
                                                                                echo "{ text: '$protocolo->nome', value: '!$protocolo->nome!'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];

                                                                    tinymce.init({
                                                                        selector: 'textarea.tinymce',
                                                                        width: 900,
                                                                        height: 600,
                                                                        // plugins: [
                                                                                    //  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                                    //  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                                    //  "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                            //  ],
                                                                        //  toolbar: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat | cut copy paste",
                                                                        plugins: [
                                                                            "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
                                                                            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                        ],

                                                                        //toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect ",
                                                                        //toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                                                                        //toolbar3: "table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                         toolbar: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                         //menubar: 'format edit table view tools',
                                                                         menubar: false,
                                                                         language: 'pt_BR',
                                                                        <? if (@$empresa[0]->impressao_laudo == 33) { ?>
                                                                        forced_root_block : '',
                                                                        <? } 
                                                                        if ($empresapermissao[0]->corretor_ortografico == 't'){ ?>
                                                                                browser_spellcheck: true,
                                                                                contextmenu: false,
                                                                        <? } ?>
                                                                        setup: function (editor) {
                                                                        /* An autocompleter that allows you to insert special characters */
                                                                        editor.ui.registry.addAutocompleter('receitas_tiny', {
                                                                        ch: '#',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = receitas_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('receitas_especial_tiny', {
                                                                        ch: '*',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = receitas_especial_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('exames_tiny', {
                                                                        ch: '@',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = exames_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('terapeutica_tiny', {
                                                                        ch: '$',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = terapeutica_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('relatorio_tiny', {
                                                                        ch: '%',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = relatorio_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('protocolo_tiny', {
                                                                        ch: '!',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = protocolo_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                            editor.on('init', function()
                                                                            {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            editor.on('SetContent', function (e) {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                    },
                                                                    toolbar_items_size: 'small',
                                                                            style_formats: [{
                                                                            title: 'Bold text',
                                                                                    inline: 'b'
                                                                            }, {
                                                                            title: 'Red text',
                                                                                    inline: 'span',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Red header',
                                                                                    block: 'h1',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Example 1',
                                                                                    inline: 'span',
                                                                                    classes: 'example1'
                                                                            }, {
                                                                            title: 'Example 2',
                                                                                    inline: 'span',
                                                                                    classes: 'example2'
                                                                            }, {
                                                                            title: 'Table styles'
                                                                            }, {
                                                                            title: 'Table row 1',
                                                                                    selector: 'tr',
                                                                                    classes: 'tablerow1'
                                                                            }],
                                                                            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
                                                                            init_instance_callback: function () {
                                                                            window.setTimeout(function () {
                                                                            $("#div").show();
                                                                            }, 1000);
                                                                            }
                                                                    });


//                                                                     tinymce.init({
//                                                                     selector: "textarea.tinymce",
//                                                                             setup : function(ed)
//                                                                             {
//                                                                             ed.on('init', function()
//                                                                             {
//                                                                             this.getDoc().body.style.fontSize = '12pt';
//                                                                             this.getDoc().body.style.fontFamily = 'Arial';
//                                                                             });
//                                                                             ed.on('SetContent', function (e) {
//                                                                             this.getDoc().body.style.fontSize = '12pt';
//                                                                             this.getDoc().body.style.fontFamily = 'Arial';
//                                                                             });
//                                                                             },
//                                                                             theme: "modern",
//                                                                             skin: "custom",
//                                                                             language: 'pt_BR',
//                                                                             readonly : readonly,
//                                                                             // lists_indent_on_tab : false,
//                                                                             // forced_root_block : '',
//                                                                     <? if (@$empresa[0]->impressao_laudo == 33) { ?>
//                                                                         forced_root_block : '',
//                                                                     <? } ?>
//                                                                     //                                                            browser_spellcheck : true,
//                                                                     //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
//                                                                     //                                                            nanospell_server: "php",
//                                                                     //                                                            nanospell_dictionary: "pt_br" ,
//                                                                     height: 450, // Pra tirar a lista automatica é só retirar o textpattern
//                                                                             plugins: [
//                                                                                     "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
//                                                                                     "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
//                                                                                     "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
//                                                                             ], 
// //                                                                              external_plugins: {'powerpaste': '<?= base_url().'js/powerpaste/plugin.min.js'?>'},
//                                                                             toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
//                                                                             toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
//                                                                             // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
//                                                                             menubar: false,
//                                                                             toolbar_items_size: 'small',
//                                                                             style_formats: [{
//                                                                             title: 'Bold text',
//                                                                                     inline: 'b'
//                                                                             }, {
//                                                                             title: 'Red text',
//                                                                                     inline: 'span',
//                                                                                     styles: {
//                                                                                     color: '#ff0000'
//                                                                                     }
//                                                                             }, {
//                                                                             title: 'Red header',
//                                                                                     block: 'h1',
//                                                                                     styles: {
//                                                                                     color: '#ff0000'
//                                                                                     }
//                                                                             }, {
//                                                                             title: 'Example 1',
//                                                                                     inline: 'span',
//                                                                                     classes: 'example1'
//                                                                             }, {
//                                                                             title: 'Example 2',
//                                                                                     inline: 'span',
//                                                                                     classes: 'example2'
//                                                                             }, {
//                                                                             title: 'Table styles'
//                                                                             }, {
//                                                                             title: 'Table row 1',
//                                                                                     selector: 'tr',
//                                                                                     classes: 'tablerow1'
//                                                                             }],
//                                                                             fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
//                                                                             templates: [{
//                                                                             title: 'Test template 1',
//                                                                                     content: 'Test 1'
//                                                                             }, {
//                                                                             title: 'Test template 2',
//                                                                                     content: 'Test 2'
//                                                                             }],
//                                                                             init_instance_callback: function () {
//                                                                             window.setTimeout(function () {
//                                                                             $("#div").show();
//                                                                             }, 1000);
//                                                                             }
//                                                                     });
                                                                    
                                                                    
                                                                    $(function () {
                                                                    $('#exame').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/modeloslaudo', {exame: $(this).val(), ajax: true}, function (j) {
                                                                    options2 = "";
                                                                    options2 += j[0].texto;
                                                                    //                                                document.getElementById("laudo").value = options2

<? if (@$empresapermissao[0]->nao_sobrepor_laudo == 't') : ?>

                                                                        //recuperando texto antigo do textarea
                                                                        var texto_antigo2 = tinyMCE.get('laudo').getContent();
                                                                        var texto_adicional_html2 = "</html>";
                                                                        var texto_adicional_body2 = "</body>";
                                                                        //aqui ele tira todas as tags html e body
                                                                        texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
                                                                        texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
                                                                        //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
                                                                        var colocartexto = texto_antigo2 + j[0].texto;
                                                                        var ed = tinyMCE.get('laudo');
                                                                        ed.setContent(colocartexto);
                                                                        $('#exame').val("");
<? else: ?>

                                                                        $('#laudo').val(options2)
                                                                                var ed = tinyMCE.get('laudo');
                                                                        ed.setContent($('#laudo').val());
                                                                        //                                                         alert('teste');

<? endif; ?>
                                                                    //$('#laudo').val(options);
                                                                    //$('#laudo').html(options).show();
                                                                    //                                                $('.carregando').hide();
                                                                    //history.go(0) 
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value=""');
                                                                    }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $('#linha').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/modeloslinhas', {linha: $(this).val(), ajax: true}, function (j) {
                                                                    options = "";
                                                                    options += j[0].texto;
                                                                    //                                                document.getElementById("laudo").value = $('#laudo').val() + options
                                                                    $('#laudo').val() + options
                                                                            var ed = tinyMCE.get('laudo');
                                                                    ed.setContent($('#laudo').val());
                                                                    //$('#laudo').html(options).show();
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value=""');
                                                                    }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $("#linha2").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=linhas",
                                                                            minLength: 1,
                                                                            focus: function (event, ui) {
                                                                            $("#linha2").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#linha2").val(ui.item.value);
                                                                            tinyMCE.triggerSave(true, true);
                                                                            document.getElementById("laudo").value = $('#laudo').val() + ui.item.id
                                                                                    $('#laudo').val() + ui.item.id
                                                                                    var ed = tinyMCE.get('laudo');
                                                                            ed.setContent($('#laudo').val());
                                                                            //$( "#laudo" ).val() + ui.item.id;
                                                                            document.getElementById("linha2").value = ''
                                                                                    return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    $(function (a) {
                                                                    $('#anteriores').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/laudosanteriores', {anteriores: $(this).val(), ajax: true}, function (i) {
                                                                    option = "";
                                                                    option = i[0].texto;
                                                                    tinyMCE.triggerSave();
                                                                    document.getElementById("laudo").value = option
                                                                            //$('#laudo').val(options);
                                                                            //$('#laudo').html(options).show();
                                                                            $('.carregando').hide();
                                                                    history.go(0)
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value="texto"');
                                                                    }
                                                                    });
                                                                    });
                                                                    //bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                                                                    //                $('.jqte-test').jqte();



                                                                    $(function () {
                                                                        $('#datahist').change(function () { 
                                                                            $.getJSON('<?= base_url() ?>autocomplete/historicopordia', {paciente: <?= $paciente_id ?>, dataescolhida: $(this).val()}, function (j) {
//                                                                            console.log(j);
                                                                            options = '';
                                                                            for (var c = 0; c < j.length; c++) {
        //                                                                  
                                                                            if (j[c].texto != '' && j[c].texto != null) {
                                                                            var texto = j[c].texto;
                                                                            } else {
                                                                            var texto = '';
                                                                            }
                                                                            options += 'Médico: ' + j[c].medico + ' <br> ' + 'Procedimento: ' + j[c].procedimento + ' <br> <hr> Queixa Principal:  ' + texto + ' <br><hr>';
        //                                                                                                        console.log(options);
                                                                            }

                                                                            $('#pordata').html(options);
                                                                            });
                                                                        });
                                                                        
                                                                        
                                                                         $('#tipoprocedimento').change(function () {
                                                                             
                                                                            var tipo = $("#tipoprocedimento").val(); 
                                                                             
                                                                            $.getJSON('<?= base_url() ?>autocomplete/datashistoricopordia', {paciente: <?= $paciente_id ?>, tipo:tipo}, function (j) {
                                                                    
                                                                                var  option  =   '<option value="">Selecione</option>'; 
                                                                                    for (let index = 0; index < j.length; index++) { 
                                                                                        var  anyString  = j[index].data_cadastro.toString();                                                                                       
                                                                                        var Ano = anyString.substring(0,4);
                                                                                        var Mes =  anyString.substring(7,5);
                                                                                        var Dia =  anyString.substring(10,8); 
                                                                                        var data_formatada = Dia+"/"+Mes+"/"+Ano;  
                                                                                        option += '<option value="'+data_formatada+'">'+data_formatada+'</option>'; 
                                                                                    }  
                                                                                    $('#pordata2').html("");
                                                                                    $('#datahist2').html(option).show();
                                                                                    $('.carregando').hide(); 
                                                                             
                                                                            });   
                                                                        });
                                                                        
                                                                         $('#datahist2').change(function () {
                                                                            var tipo = $("#tipoprocedimento").val(); 
                                                                            $.getJSON('<?= base_url() ?>autocomplete/historicopordiatipo', {paciente: <?= $paciente_id ?>, dataescolhida: $(this).val(),tipo:tipo}, function (j) {
//                                                                          console.log(j);
                                                                             
                                                                               $('#pordata2').html(j);
                                                                             
//                                                                            options = '';
//                                                                            for (var c = 0; c < j.length; c++) { 
////                                                                                if (j[c].texto != '' && j[c].texto != null) {
////                                                                                   var texto = j[c].texto;
////                                                                                } else {
////                                                                                   var texto = '';
////                                                                                } 
////                                                                                 options += 'Médico: ' + j[c].medico + ' <br> ' + 'Procedimento: ' + j[c].procedimento + ' <br> <hr> Queixa Principal:  ' + texto + ' <br><hr>';
//                                                                            }

                                                                          
                                                                            });
                                                                        });
                                                                        
                                                                         
                                                                        
                                                                        
                                                                    });
var campos = [];
var camposObj = [];

function carregarTemplate(){
    var id = $('#template').val();
    if(id == ''){
        $("#anamnesePadrao").show();
        $("#objTemplate").val('');
        $('#camposDiv').html('');
    }
    $.getJSON("<?= base_url() ?>ambulatorio/empresa/carregartemplatejson/" + id, {teste: '', ajax: true}, function (data) {
    //    console.log(data);
        campos = [];
        camposObj = [];
        $('#camposDiv').html('');
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
            carregarCampos(element);
        }
        $("#anamnesePadrao").hide();
        $("#objTemplate").val(JSON.stringify(camposObj));
        $("#arrayTemplate").val(campos);
    });
    
}
function carregarTemplateExi(){
   
    var data = JSON.parse($("#objTemplate").val());
    for (let index = 0; index < data.length; index++) {
        const element = data[index];
        carregarCampos(element);
    }
    
    $("#anamnesePadrao").hide();
    $("#objTemplate").val(JSON.stringify(camposObj));
    $("#arrayTemplate").val(campos);
}

if($("#objTemplate").val() != '' && $("#objTemplate").val() != null){ 
    carregarTemplateExi();
}else{
    carregarTemplate();
}


function carregarCampos(obj){

    var campoAdd = obj.nome;
    var campoAddName = limparEspacos(campoAdd);
    var campoTipo = obj.tipo;
    var campoValue = obj.value;

    if(typeof obj.opcoes !== 'undefined'){
        var opcoesCampo = obj.opcoes;
    }else{
        var opcoesCampo = [];
    }
    camposObj.push(obj);
    campos.push(campoAddName);
    // console.log(opcoesCampo);
    if(campoTipo == 'textoCurto'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'textoLongo'){
        var div = '<div id="'+campoAdd+'Div"><br><label>'+campoAdd+'</label><br><textarea onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" cols="40" rows="10" type="text" name="'+campoAddName+'" id="'+campoAddName+'">'+campoValue+'</textarea></div>';
    }
    else if(campoTipo == 'textoData'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'textoNumero'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="number" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'select'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><select onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" name="'+campoAddName+'" id="'+campoAddName+'" class="size2"></select></div>';
    }
    else if(campoTipo == 'multiplo'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><select onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" data-placeholder="Selecione uma ou mais opções" multiple name="'+campoAddName+'" id="'+campoAddName+'" class="size2 chosen-select"></select></div>';
    }
    else if(campoTipo == 'checkbox'){
        if(campoValue != ''){
            var checked = 'checked ';
        }else{
            var checked = '';
        }
        console.log(obj);
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input '+checked+' onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="checkbox" name="'+campoAddName+'" id="'+campoAddName+'" value="" /></div>';
    }
    $('#camposDiv').append(div);
    if(campoTipo == 'textoData'){
        calendario(campoAddName);
    }
    if(campoTipo == 'multiplo'){
        $('#' + campoAddName).chosen();
    }
    adicionarOpcaoCarrado(campoAddName, opcoesCampo, campoValue);

    // $('#nomeCampo').val('');
}

function alterarvalueTemplate(id){
    
    var index = campos.indexOf(id);
    
    camposObj[index].value = $('#'+id).val();
    // console.log($('#'+id).val());
    if($('#'+id).is(':checked') && $('#'+id).val() == ''){
        camposObj[index].value = 'on';
    }
    $("#objTemplate").val(JSON.stringify(camposObj));
    $("#arrayTemplate").val(campos);
}

function adicionarOpcaoCarrado(id, opcoesCampo, campoValue){
    // console.log(id);
    var option_str = '';
    for (let index = 0; index < opcoesCampo.length; index++) {
        const element = opcoesCampo[index];
        if(campoValue == element){
            var selected = 'selected ';
        }else{
            var selected = '';
        }
        option_str += '<option '+selected+' value="'+element+'">'+element+'</option>';
    }

    $('#'+id).append(option_str);
    $('#'+id).trigger("chosen:updated");
    $('#'+ id +'adc').val('');
}

function calendario(id){
    $("#"+ id).datepicker({
        autosize: true,
        changeYear: true,
        changeMonth: true,
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        buttonImage: '<?= base_url() ?>img/form/date.png',
        dateFormat: 'dd/mm/yy'
    });
}
function limparEspacos(vlr) {

    while(vlr.indexOf(" ") != -1){
        vlr = vlr.replace(" ", "");
    }
    return vlr;
}


$(function () {
          $("#txtCICPrimariolabelAtestado").autocomplete({

          source: "<?= base_url() ?>index.php?c=autocomplete&m=cid1",
                  minLength: 3,
                  focus: function (event, ui) {
                  $("#txtCICPrimariolabelAtestado").val(ui.item.label);
                  return false;
                  },
                  select: function (event, ui) {
                  $("#txtCICPrimariolabelAtestado").val(ui.item.value);
                  $("#txtCICPrimarioAtestado").val(ui.item.id);
                  return false;
                  }
          });
  });
  
  function imprimirAtestado(){
        var voltaemseguidatrabalho_check = document.getElementById("voltaemseguidatrabalho");
        if (voltaemseguidatrabalho_check.checked) { 
          var voltaemseguidatrabalho = "on";
        }else{   
          var voltaemseguidatrabalho = "off";
        }
         
        var afastadohoje_check = document.getElementById("afastadohoje");
        if (afastadohoje_check.checked) { 
          var afastadohoje = "on";
        }else{  
          var afastadohoje = "off";
        } 
        
         
         
            $.ajax({  
                type: "POST", 
                data: {  
                agenda_exames_id: $("#agenda_exames_id").val(),
                voltaemseguidatrabalho: voltaemseguidatrabalho,
                diaafastado:$("#diaafastado").val(),
                afastadohoje:afastadohoje,
                qtd_dias_afastado: $("#qtd_dias_afastado").val(),
                data_afastamento:$("#data_afastamento").val(), 
                txtCICPrimarioAtestado: $("#txtCICPrimarioAtestado").val(), 
                paciente_id:<?= $paciente_id; ?>,
                empresa_id:$("#empresa_id").val()
            
            }, 
                url: "<?= base_url(); ?>ambulatorio/laudo/gravarimpressaoatestado",
                success: function (data, textStatus, jqXHR) {  
              
                  console.log(data);
                  window.open('<?= base_url() ?>ambulatorio/laudo/impressaoatestadomedico/'+data+'/', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=700'); 
            
                },
                error: function (data) {
                   alert('Ocorreu um erro, por favor, tente novamente');
                   console.log(data);
                }
            });
             
       
    }
    
   $(function () {
        $("#data_afastamento").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });  
    
    
    function abrirImpressaoTudoAssinar(){
        var inputs = jQuery('input[name^="impressanavegador"]:checked:enabled');
        var impressoesGerais = [];
        for(var i = 0; i < inputs.length; i++){
            impressoesGerais.push($(inputs[i]).val());
        }

        if(impressoesGerais.length == 0){
            alert('Selecione alguma Impressão, para que possa Assinar e Imprimir');
        }else{
            $.ajax({
                type: "GET",
                data: {
                    impressoesGerais: impressoesGerais
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                      window.open('<?= base_url() ?>ambulatorio/laudo/assinareenviaronline/<?= $ambulatorio_laudo_id ?>/<?=@$obj->_medico_parecer1?>/'+impressoesGerais);
                    //  alert('deu certo');
                    //console.log(data);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
        }
    }
    
    
    function abrirImpressaoTudo(){
        var inputs = jQuery('input[name^="impressanavegador"]:checked:enabled');
        var impressoes = jQuery('input[class^="quantidade_de_impressoes"]');
        var values = [];
        var impressao_repetir = [];
        for(var i = 0; i < inputs.length; i++){
            values.push($(inputs[i]).val());
            impressao_repetir.push([$(inputs[i]).val(), $(impressoes[i]).val()]);
        }

        var receitas = [];
        var receitaespecial = [];
        var relatorios = [];
        var solicitacaoexames = [];
        var terapeuticas = [];
        
        values.forEach(Arrayimpressao);

        function Arrayimpressao(item, index) {
            var textonovo = item.replace(".pdf", "");
            var retorno = textonovo.split("_");

            if(retorno[0] == 'receita'){
                receitas.push(retorno[1]);
            }else if(retorno[0] == 'receituarioEspecial'){
                receitaespecial.push(retorno[1]);
            }else if(retorno[0] == 'relatorios'){
                relatorios.push(retorno[1]);
            }else if(retorno[0] == 'solicitacaoexames'){
                solicitacaoexames.push(retorno[1]);
            }else if(retorno[0] == 'terapeuticas'){
                terapeuticas.push(retorno[1]);
            }
        }


        if(solicitacaoexames.length == 0){
            solicitacaoexames = [0];
        }
        if(relatorios.length == 0){
            relatorios = [0];
        }
        if(terapeuticas.length == 0){
            terapeuticas = [0];
        }
        if(receitaespecial.length == 0){
            receitaespecial = [0];
        }
        if(receitas.length == 0){
            receitas = [0];
        }

            if(receitas[0] == 0 && receitaespecial[0] == 0 && relatorios[0] == 0 && solicitacaoexames[0] == 0 && terapeuticas[0] == 0){
                alert('Selecione alguma Impressão, para que possa Imprimir');
            }else{
                $.ajax({
                type: "POST",
                data: {
                    receitas: receitas,
                    receitaespecial: receitaespecial,
                    relatorios: relatorios,
                    solicitacaoexames: solicitacaoexames,
                    terapeuticas: terapeuticas,
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudotudonovo/<?= $ambulatorio_laudo_id ?>/'+solicitacaoexames+'/'+relatorios+'/'+terapeuticas+'/'+impressao_repetir);
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitatodosnovo/<?= $ambulatorio_laudo_id?>/'+receitas+'/'+impressao_repetir);
                    window.open('<?= base_url() ?>ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/<?= $ambulatorio_laudo_id ?>/'+receitaespecial+'/'+impressao_repetir);
                    // alert('deu certo');
                    //console.log(data);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
            }
    }
    
            $("#mostrarDadosReceitas").click(function () {
                var botao = $("#mostrarDadosReceitas").text();

                if (botao == '+') {
                    $("#mostrarDadosReceitas").text('-');
                } else {
                    $("#mostrarDadosReceitas").text('+');
                }

                $(".mostrartodasreceitas").toggle();
            });
            
            $("#mostrarDadosReceitasEspecial").click(function () {
                var botao = $("#mostrarDadosReceitasEspecial").text();

                if (botao == '+') {
                    $("#mostrarDadosReceitasEspecial").text('-');
                } else {
                    $("#mostrarDadosReceitasEspecial").text('+');
                }

                $(".mostratodasreceitasespecial").toggle();
            });
            
            $("#mostrarDadosRelatorio").click(function () {
            var botao = $("#mostrarDadosRelatorio").text();
            
            if (botao == '+') {
                $("#mostrarDadosRelatorio").text('-');
            } else {
                $("#mostrarDadosRelatorio").text('+');
            }

            $(".mostrartodosrelatorios").toggle();
            });
            
            function mostrarDadosExtrasreceita(ambulatorio_receituario_id){
            var botao = $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('-');
                $('#receita_destaque'+ambulatorio_receituario_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('+');
                $('#receita_destaque'+ambulatorio_receituario_id).removeClass('destaque');
            }                                       
            $("#tr_receita_"+ambulatorio_receituario_id).toggle();

    }


 function SalvarReceita(ambulatorio_receituario_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('receita_'+ambulatorio_receituario_id).getContent();
        console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarreceita_rapido', {ambulatorio_receituario_id: ambulatorio_receituario_id, texto: texto, ajax: true}, function (j) {
            console.log(j)

            var botao = $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('-');
                $('#receita_destaque'+ambulatorio_receituario_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('+');
                $('#receita_destaque'+ambulatorio_receituario_id).removeClass('destaque');
            } 
            $("#tr_receita_"+ambulatorio_receituario_id).toggle();

        });

    }
    
     $("#mostrarDadosExames").click(function () {
            var botao = $("#mostrarDadosExames").text();
            
            if (botao == '+') {
                $("#mostrarDadosExames").text('-');
            } else {
                $("#mostrarDadosExames").text('+');
            }

            $(".mostrartodosexames").toggle();
            });
            
 function mostrarDadosExtrasrelatorio(ambulatorio_relatorio_id){
            var botao = $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('-');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('+');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).removeClass('destaque');
            }                                       
            $("#tr_relatorios_"+ambulatorio_relatorio_id).toggle();

    }
    
    function SalvarRelatorios(ambulatorio_relatorio_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('relatorios_'+ambulatorio_relatorio_id).getContent();
        console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarrelatorios_rapido', {ambulatorio_relatorio_id: ambulatorio_relatorio_id, texto: texto, ajax: true}, function (j) {
            console.log(j)

            var botao = $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('-');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('+');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).removeClass('destaque');
            } 
            $("#tr_relatorios_"+ambulatorio_relatorio_id).toggle();

        });

    }
    function mostrarDadosExtrasexames(ambulatorio_exame_id){
            var botao = $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('-');
                $('#exames_destaque'+ambulatorio_exame_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('+');
                $('#exames_destaque'+ambulatorio_exame_id).removeClass('destaque');
            }                                       
            $("#tr_exames_"+ambulatorio_exame_id).toggle();

    }
    function SalvarExames(ambulatorio_exame_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('exames_'+ambulatorio_exame_id).getContent();
        console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarexames_rapido', {ambulatorio_exame_id: ambulatorio_exame_id, texto: texto, ajax: true}, function (j) {
            console.log(j)

            var botao = $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('-');
                $('#exames_destaque'+ambulatorio_exame_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('+');
                $('#exames_destaque'+ambulatorio_exame_id).removeClass('destaque');
            } 
            $("#tr_exames_"+ambulatorio_exame_id).toggle();

        });

    }
     
    // NOVO TINYMCE
    tinymce.init({
        selector: "#receita",
        setup : function(ed)
        {
            ed.on('init', function() 
            {
                this.getDoc().body.style.fontSize = '12pt';
                this.getDoc().body.style.fontFamily = 'Arial';
            });
        },
        // theme: "modern",
        // skin: "custom",
        language: 'pt_BR',
        
        // forced_root_block : '',
        <?if(@$empresa[0]->impressao_laudo == 33){?>
            forced_root_block : '',
        <?}?>
    //                                                            browser_spellcheck : true,
    //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
    //                                                            nanospell_server: "php",
    //                                                            nanospell_dictionary: "pt_br" ,
        height: 450,
        
        plugins: [
            "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
        ],
        toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough | link unlink anchor image | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help", 
        menubar: false,
        toolbar_items_size: 'small',

        style_formats: [{
                title: 'Bold text',
                inline: 'b'
            }, {
                title: 'Red text',
                inline: 'span',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Red header',
                block: 'h1',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Example 1',
                inline: 'span',
                classes: 'example1'
            }, {
                title: 'Example 2',
                inline: 'span',
                classes: 'example2'
            }, {
                title: 'Table styles'
            }, {
                title: 'Table row 1',
                selector: 'tr',
                classes: 'tablerow1'
            }],
            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',    

            templates: [{
                    title: 'Test template 1',
                    content: 'Test 1'
                }, {
                    title: 'Test template 2',
                    content: 'Test 2'
                }],

        init_instance_callback: function () {
            window.setTimeout(function () {
                $("#div").show();
            }, 1000);
        }
    });
    
    
    $(document).ready(function(){
        $('#txtNome').on('input', function(){
            $('#btnSalvarModelo').prop('disabled', $(this).val().length < 3);
        });
    });
    
    
</script>


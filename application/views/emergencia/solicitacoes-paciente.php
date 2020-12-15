<link href="<?= base_url() ?>css/solicitacoes-paciente.css" rel="stylesheet"/>
<!--<div class="content ficha_ceatox">-->
<!--    <div class="bt_link_voltar">-->
<!--        <a href="--><?//= base_url() ?><!--cadastros/pacientes">-->
<!--            Voltar-->
<!--        </a>-->
<!--    </div>-->
    <?
    $args['paciente'] = $paciente_id;
    $empresaPermissoes = $this->guia->listarempresapermissoes();
    
    $perfil_id = $this->session->userdata('perfil_id');
    $internacao = $empresaPermissoes[0]->internacao;
    $imagem = $empresaPermissoes[0]->imagem;
    $consulta = $empresaPermissoes[0]->consulta;
    $especialidade = $empresaPermissoes[0]->especialidade;
    $geral = $empresaPermissoes[0]->geral;
    $faturamento = $empresaPermissoes[0]->faturamento;
    $estoque = $empresaPermissoes[0]->estoque;
    $financeiro = $empresaPermissoes[0]->financeiro;
    $marketing = $empresaPermissoes[0]->marketing;
    $laboratorio = $empresaPermissoes[0]->laboratorio;
    $ponto = $empresaPermissoes[0]->ponto;
    $calendario = $empresaPermissoes[0]->calendario;
    $credito = $this->guia->creditoempresa();

    $financeiro_cadastro = $empresaPermissoes[0]->financeiro_cadastro;
    $medicinadotrabalho = $empresaPermissoes[0]->medicinadotrabalho;
    $inadimplencia = $empresaPermissoes[0]->inadimplencia;
    $perfil_marketing_p = $empresaPermissoes[0]->perfil_marketing_p;
    $operador_id = $this->session->userdata('operador_id');
    $valores_recepcao = @$empresaPermissoes[0]->valores_recepcao;
//    $creditoativo = $this->exametemp->creditopaciente;
//    
//    echo '<pre>';
//    var_dump($creditoativo);
//    die;
    ?>


    <div class=" alert alert-info ">
        <a href="<?= base_url() ?>cadastros/pacientes" class="fa fa-arrow-left" aria-hidden="true"></a><b>&nbsp;&nbsp; <label class="title">Solicita&ccedil;&otilde;es do Paciente
            </label></b>
    </div>

    <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm card-link" data-toggle="collapse" data-target="#atend">
            Atendimento
        </button>

        <div id="atend" class="dropdown-menu">

                <? if ($imagem == 't' && $perfil_id != 24) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/novo/<?= $paciente_id ?>">Novo exame</a>
                <? } ?>
                <? if ($consulta == 't' && $perfil_id != 24) { ?>
                 <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/novoconsulta/<?= $paciente_id ?>">Nova consulta</a>
                <? } ?>
                <? if ($especialidade == 't' && $perfil_id != 24) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/novofisioterapia/<?= $paciente_id ?>">Nova Especialidade</a>
                <? } ?>
                <? if ($geral == 't' && $perfil_id != 24) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/novoatendimento/<?= $paciente_id ?>">Novo Atendimento</a>
                <? } ?>
                <? if ($imagem == 't' && $perfil_id != 24 ) { ?>
                    <a href="<?= base_url() ?>ambulatorio/exametemp/carregarpacientetemp/<?= $paciente_id ?>">Exames</a>
                <? } ?>
                <? if ($consulta == 't'  && $perfil_id != 24) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/exametemp/carregarpacienteconsultatemp/<?= $paciente_id ?>">Consultas</a>
                <? } ?>
                <? if ($imagem != 't' && $perfil_id != 24) { ?>
                    <a class="dropdown-item" class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/acompanhamento/<?= $paciente_id ?>">Acompanhamento</a>
                <? } ?>
                <?php if ( $perfil_id != 24) { ?>
                    <a class="dropdown-item" class="dropdown-item" target="_blank" href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaocalendariopaciente/<?= $paciente_id ?>">Agendamento</a>
                <?php }?>
                <?php if ( $perfil_id != 24) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/cancelamento/<?= $paciente_id ?>" target="_blank">Cancelamentos</a>
                <?php }?>
                <? if (($consulta == 't' || $geral == 't') && $perfil_id != 24) { ?>
                    <? if ($perfil_id != 2 && $perfil_id != 11 && $perfil_id != 12 && $perfil_id != 15) { ?>
                        <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/laudo/carregarlaudohistorico/<?= $paciente_id ?>">Hist. Consulta</a>
                    <? } ?>
                <? } ?>

                    <a class="dropdown-item" onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/carregarocorrencias/" . $paciente_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1280,height=650');"  >
                        Ocorrência
                    </a>
        </div>
    </div>
    <div class="dropdown">

        <button class="btn btn-outline-primary btn-sm card-link" data-toggle="collapse" data-target="#guia">
            Guia
        </button>

        <div id="guia" class="dropdown-menu">

            <?php if ( $perfil_id != 24) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/pesquisar/<?= $args['paciente'] ?>">Guias</a>
            <?php }?>
            <? if ($empresaPermissoes[0]->orcamento_multiplo == 'f') { ?>
                <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/orcamento/<?= $paciente_id ?>">Or&ccedil;amento</a>
            <? } else { ?>
                <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/guia/orcamentomultiplo/<?= $paciente_id ?>">Or&ccedil;amento</a>
            <? } ?>
            <? if ($imagem == 't' && $perfil_id != 24) { ?>
                <a href="<?= base_url() ?>ambulatorio/guia/acompanhamento/<?= $paciente_id ?>">Acompanhamento</a>
            <? } ?>
            <? if ($internacao == 't' && $perfil_id != 24) { ?>
                <a href="<?= base_url() ?>internacao/internacao/novointernacao/<?= $paciente_id ?>">Internação</a>

            <? } ?>
            <? if ($especialidade == 't' && $perfil_id != 24) { ?>
                <a href="<?= base_url() ?>ambulatorio/exame/autorizarsessaofisioterapia/<?= $paciente_id ?>">Sessao Especialidade</a>
            <? } ?>


        </div>
    </div>
    <div class="dropdown">
        <btn class="btn btn-outline-primary btn-sm card-link" data-toggle="collapse" data-target="#auto">
            Autorizações
        </btn>

        <div id="auto" class="dropdown-menu">
            <? if ($imagem == 't' && $perfil_id != 24) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/procedimentoautorizar/<?= $paciente_id ?>">Autorizar exame</a>
            <? } ?>
            <? if ($consulta == 't' && $perfil_id != 24) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/procedimentoautorizarconsulta/<?= $paciente_id ?>">Autorizar consulta</a>
            <? } ?>
            <? if ($especialidade == 't' && $perfil_id != 24) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/procedimentoautorizarfisioterapia/<?= $paciente_id ?>">Autorizar Especialidade</a>
            <? } ?>
            <? if ($geral == 't' && $perfil_id != 24) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/procedimentoautorizaratendimento/<?= $paciente_id ?>">Autorizar Atendimento</a>
            <? } ?>

            <? if ($perfil_id == 1 || $perfil_id == 5 ||   $perfil_id == 24 ||  $perfil_id == 11) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/exametemp/unificar/<?= $paciente_id ?>"> Unificar</a>
            <? }
            ?>
        </div>

    </div>
    <div class="dropdown">
        <btn class="btn btn-outline-primary btn-sm card-link" data-toggle="collapse" data-target="#credi">
            Crediarios
        </btn>

        <div id="credi" class="dropdown-menu">
            <? if ($credito == 't' && ($perfil_id == 1 || $perfil_id == 5 || $perfil_id == 10 || ($perfil_id == 11 || $perfil_id == 21) || $perfil_id == 18 || $perfil_id == 20)  && $perfil_id != 24) { ?>
                <a class="dropdown-item"v href="<?= base_url() ?>ambulatorio/exametemp/listarcredito/<?= $paciente_id ?>">Credito</a>
            <? } ?>
            <div class="bt_link_new">
                <a class="dropdown-item" href="<?= base_url()?>ambulatorio/exametemp/listartcd/<?= $paciente_id ?>" > TCD </a>
            </div>
            <? if ($inadimplencia == 't' && ($perfil_id == 1 || $perfil_id == 5 || $perfil_id == 10 || ($perfil_id == 11 || $perfil_id == 21))  && $perfil_id != 24) { ?>
               <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/exametemp/listarinadimplencia/<?= $paciente_id ?>">Inadimplência</a>
            <? } ?>

        </div>

    </div>
    <div class="dropdown">

        <btn class="btn btn-outline-primary btn-sm card-link" data-toggle="collapse" data-target="#advanced">
            Avançado
        </btn>

        <div id="advanced" class="dropdown-menu">
            <? if ($medicinadotrabalho == 't' && $perfil_id != 24) { ?>
                <a class="dropdown-item" onclick="javascript:window.open('<?= base_url() ?>ambulatorio/guia/cadastroaso/<?= $paciente_id ?>/<?= @$obj->_medico_parecer1 ?>');" >
                        Cadastro ASO</a>
            <? } ?>
                <a class="dropdown-item" onclick="javascript:window.open('<?= base_url() ?>emergencia/filaacolhimento/log/<?= $paciente_id ?>', '', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);" >LOG</a>
            <? if ($perfil_id == 1) {
                ?>
                <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/exametemp/excluirpaciente/<?= $paciente_id ?>">Excluir</a>
            <? } ?>
            <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/anexarimagem/<?= $paciente_id ?>">Arquivos</a>
            <a class="dropdown-item" onclick="javascript: return confirm('Você realmente deseja desativar o paciente? Essa ação é irreversível!!');" href="<?= base_url() ?>cadastros/pacientes/desativarpaciente/<?= $paciente_id ?>">Desativar</a>
        </div>
    </div>
</div>
<!--                    --><?// if ($imagem == 't' && $perfil_id != 24) { ?>
<!--                        <div class="bt_link_new"><a href="--><?//= base_url() ?><!--ambulatorio/guia/novo/--><?//= $paciente_id ?><!--">Novo exame</a></div>-->
<!--                    --><?// } ?>
<!--                    --><?// if ($consulta == 't' && $perfil_id != 24) { ?>
<!--                        <td width="80px;"><div class="bt_link_new"><a href="--><?//= base_url() ?><!--ambulatorio/guia/novoconsulta/--><?//= $paciente_id ?><!--">Nova consulta</a></div></td>-->
<!--                    --><?// } ?>
<!--                    --><?// if ($especialidade == 't' && $perfil_id != 24) { ?>
<!--                        <td width="80px;"><div class="bt_link_new"><a href="--><?//= base_url() ?><!--ambulatorio/guia/novofisioterapia/--><?//= $paciente_id ?><!--">Nova Especialidade</a></div></td>-->
<!--                    --><?// } ?>
<!--                    --><?// if ($geral == 't' && $perfil_id != 24) { ?>
<!--                        <td width="80px;"><div class="bt_link_new" ><a href="--><?//= base_url() ?><!--ambulatorio/guia/novoatendimento/--><?//= $paciente_id ?><!--">Novo Atendimento</a></div></td>-->
<!--                    --><?// } ?>



                <tr>







<!--                    <td width="250px;"><div class="bt_link"><a href="<?= base_url() ?>emergencia/filaacolhimento/gravarsolicitacao/<?= $paciente_id ?>">Acolhimento</a></div></td>
                <td width="200px;"><div class="bt_link"><a href="<?= base_url() ?>cadastros/pacientes/carregar">Ficha</a></div></td>
                <td width="200px;"><div class="bt_link_new"><a href="<?= base_url() ?>internacao/internacao/novosolicitacaointernacao/<?= $paciente_id ?>">Sol. Interna&ccedil;&atilde;o</a></div></td>
                <td width="200px;"><div class="bt_link_new"><a href="<?= base_url() ?>internacao/internacao/novointernacao/<?= $paciente_id ?>">Internar</a></div></td></tr>-->
                <!--<td width="200px;"><div class="bt_link"><a href="<?= base_url() ?>cadastros/pacientes/carregar">Marcar consulta</a></div></td></tr>-->
                </tr>

                <tr>






 

                    <td width="100px;"></td>
                    <? if ($perfil_id == 1) {
                        ?>
                        <td width="100px;"></td>
                    <? } ?>
                </tr>

                <tr>
                    <? if ($internacao == 't' && $perfil_id != 24) { ?>

                        <td width="100px;"><div class="bt_link_new"><a href="<?= base_url() ?>internacao/internacao/novosolicitacaointernacao/<?= $paciente_id ?>">Sol.Internação</a></div></td>
                    <? } ?>

                    <? if (@$empresaPermissoes[0]->tarefa_medico == 't' && ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || ( $perfil_marketing_p == 't' && $perfil_id == 14) || $perfil_id == 5 || ($perfil_id == 18 || $perfil_id == 20) || $perfil_id == 6 || ($perfil_id == 11 || $perfil_id == 21) || $perfil_id == 12 || $perfil_id == 10 || $perfil_id == 15 || $perfil_id == 19 || ( $financeiro_cadastro == 't' && $perfil_id == 13) || ($perfil_id == 7 && @$empresaPermissoes[0]->tecnico_acesso_acesso == 't')) && $perfil_id != 24) { ?>
                        <td width="100px;"><div class="bt_link_new"><a  href="<?= base_url() ?>ambulatorio/exametemp/listartarefas/<?= $paciente_id ?>">Tarefas</a></div></td>    
                    <? } ?>
                </tr>
                
            </table>            
        </div>
</div>
    <div>
        <form name="form_paciente" id="form_paciente" action="<?= base_url() ?>cadastros/pacientes/gravar" method="post">
<!--            <div class="panel panel-default ">-->
        <div class="alert alert-info">
            <b>Dados do Paciente</b>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-3">
                    <div>
                        <label>Nome</label>
                        <input readonly type="text" id="txtNome" name="nome" class="form-control texto08" value="<?= $paciente['0']->nome; ?>" required="true"  placeholder="Nome do Paciente">
                        <input readonly type ="hidden" name ="paciente_id"  value ="<?= $paciente['0']->paciente_id; ?>" id ="txtPacienteId">

                    </div>
                    <div>
                        <label>Nome da M&atilde;e</label>
                        <input type="text" name="nome_mae" id="txtNomeMae" class="form-control texto08" value="<?= $paciente['0']->nome_mae; ?>" readonly/>
                    </div>
                    <div>
                        <label>Nome do Pai</label>
                        <input type="text"  name="nome_pai" id="txtNomePai" class="form-control texto08" value="<?= $paciente['0']->nome_pai; ?>" readonly/>
                    </div>

                </div>

                <div class="col-lg-2">
                    <div>
                        <label>Sexo</label>

                        <input readonly type="text"  name="sexo" id="txtSexo"  class="form-control texto03"  value="<?if ($paciente['0']->sexo == "M"){
                            echo 'Masculino';
                        }elseif($paciente['0']->sexo == "F"){
                            echo 'Feminino';
                        }else{
                            echo 'Não Informado';
                        } ?>"/>
                    </div>
                    <div >
                        <label>Data de Nascimento</label>
                        <input readonly type="text" name="nascimento" id="txtNascimento" required="true" alt="date" class="form-control texto03 date"
                               placeholder="Data de Nascimento"

                               value="<?php
                               if ($paciente['0']->nascimento != '') {
                                   echo substr($paciente['0']->nascimento, 8, 2) . '/' . substr($paciente['0']->nascimento, 5, 2) . '/' . substr($paciente['0']->nascimento, 0, 4);
                               }
                               ?>">
                    </div>

                </div>
                <div class="col-lg-3">
                    <div>
                        <label>Idade</label>
                        <?
                        if ($paciente['0']->nascimento != '') {
                            $data_atual = date('Y-m-d');
                            $data1 = new DateTime($data_atual);
                            $data2 = new DateTime($paciente[0]->nascimento);

                            $intervalo = $data1->diff($data2);
                            ?>
                            <input type="text" name="idade" id="idade" class="form-control texto02" readonly value="<?= $intervalo->y ?> ano(s)"/>
                        <? } else { ?>
                            <input type="text" name="nascimento" id="txtNascimento" class="form-control texto02" readonly/>
                        <? } ?>
                    </div>
                    <div>
                        <label>Email</label>
                        <input readonly  placeholder="Email" type="text" id="txtCns" name="cns"  class="form-control texto05" value="<?= $paciente['0']->cns; ?>" />
                    </div>


                </div>
                <div class="col-lg-3">

                    <div>
                        <?
                        if(file_exists("./upload/webcam/pacientes/" . $paciente['0']->paciente_id . ".jpg")){?>
                            <img class="img-thumbnail img-rounded" src="<?= base_url() ?>upload/webcam/pacientes/<?=  $paciente['0']->paciente_id ?>.jpg" alt="" style="width: 100pt; height: 110pt;" />
                        <? }else{?>
                            <img class="img-thumbnail img-rounded" src="" alt="Sem foto" style="width: 100pt; height: 110pt;" />
                        <?}
                        ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="alert alert-info">
            <b>Domicilio</b>
        </div>
        <div class="panel-body">
            <div class="row">
<!--                <div>-->
<!--                    <label>T.Logradouro</label>-->
<!--                    <input readonly type="text" id="txtCelular" class="form-control texto04" name="tipo" value="--><?//=$paciente['0']->descricao; ?><!--" />-->
<!---->
<!--                </div>-->

                <div class="col-lg-3">
                    <div>
                        <label>Endere&ccedil;o</label>
                        <input readonly type="text" id="txtendereco" class="form-control texto10" name="endereco" value="<?= $paciente['0']->logradouro; ?>" />
                    </div>


                    <div>
                        <label>Telefone</label>
                        <?
                        if ($paciente['0']->telefone != '' && strlen($paciente['0']->telefone) > 3) {

                            if (preg_match('/\(/', $paciente['0']->telefone)) {
                                $telefone = $paciente['0']->telefone;
                            } else {
                                $telefone = "(" . substr($paciente['0']->telefone, 0, 2) . ")" . substr($paciente['0']->telefone, 2, strlen($paciente['0']->telefone) - 2);
                            }
                        } else {
                            $telefone = '';
                        }
                        if ($paciente['0']->celular != '' && strlen($paciente['0']->celular) > 3) {
                            if (preg_match('/\(/', $paciente['0']->celular)) {
                                $celular = $paciente['0']->celular;
                            } else {
                                $celular = "(" . substr($paciente['0']->celular, 0, 2) . ")" . substr($paciente['0']->celular, 2, strlen($paciente['0']->celular) - 2);
                            }
                        } else {
                            $celular = '';
                        }
                        ?>

                        <input readonly type="text" id="txtTelefone" class="form-control texto04" name="telefone"  value="<?= @$telefone; ?>" />
                    </div>

                </div>


                <div class="col-lg-3">

                    <div>
                        <label>Complemento</label>
                        <input readonly type="text" id="txtComplemento" class="form-control texto08" name="complemento" value="<?= $paciente['0']->complemento; ?>" />
                    </div>
                    <div>
                        <label>Celular*</label>
                        <input readonly type="text" id="txtCelular" class="form-control texto04" name="celular" value="<?= @$celular; ?>" required=""/>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div>
                        <label>N&uacute;mero</label>
                        <input readonly type="text" id="txtNumero" class="form-control texto02" name="numero" value="<?= $paciente['0']->numero; ?>" />
                    </div>
                    <div>
                        <label>CEP</label>
                        <input readonly type="text" id="cep" class="form-control texto03" name="cep"  value="<?= $paciente['0']->cep; ?>" />
                    </div>
                </div>

                <div class="col-lg-3">
                    <div>
                        <label>Bairro</label>
                        <input readonly type="text" id="txtBairro" class="form-control texto05" name="bairro" value="<?= $paciente['0']->bairro; ?>" />
                    </div>



<!--                    <div>-->
<!--                        <label>Município</label>-->
<!--                        <input readonly type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="--><?//= $paciente['0']->cidade_cod; ?><!--" readonly="true" />-->
<!--                        <input readonly type="text" id="txtCidade" class="form-control texto04" name="txtCidade" value="--><?//= $paciente['0']->cidade_desc; ?><!--" />-->
<!--                    </div>-->
                </div>

            </div>
        </div>

            <div class="clear"></div>

    <link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>


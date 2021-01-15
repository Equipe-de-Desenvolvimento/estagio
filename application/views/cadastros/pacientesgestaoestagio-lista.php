<?
$empresa_id = $this->session->userdata('empresa_id');
$perfil_id = $this->session->userdata('perfil_id');
$operador_id = $this->session->userdata('operador_id');
$empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
$filtro_exame = @$empresapermissoes[0]->filtro_exame_cadastro;
$tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
$valores_recepcao = @$empresapermissoes[0]->valores_recepcao;
?>
<link href="<?= base_url() ?>css/cadastro/paciente-lista.css?v=1" rel="stylesheet"/>
        <div class="col-sm-12">
            <div class="">
                <div class="" id="pesquisar">
                    <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisarGestaoEstagio">
                        <div class="row">
                                    <div class="nome">
                                        <h6>Nome</h6>
                                        <div>
                                            <input type="text" name="nome" placeholder="Nome do estagiário" class="texto05" value="<?php echo @$_GET['nome']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>CPF</h6>
                                        <div>
                                            <input type="text" id="txtCpf" name="cpf" placeholder="CPF" class="texto05" value="<?php echo @$_GET['cpf']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Nascimento</h6>
                                        <div class="inasc">
                                            <input type="text" id="txtNascimento" name="nascimento" placeholder="00/00/0000" class="texto05" alt="data" value="<?php echo @$_GET['nascimento']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Telefone</h6>
                                        <div>
                                            <input type="text" id="txtTelefone" name="telefone" placeholder="(99)9999-9999" class="texto05" value="<?php echo @$_GET['telefone']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Email</h6>
                                        <div>
                                            <input type="text" name="Email" placeholder="Email" class="texto05" value="<?php echo @$_GET['email']; ?>" />
                                        </div>
                                    </div>

                                    <?php
                                    if (@$empresapermissoes[0]->pesquisar_responsavel == "t") { ?>
                                        <div class="nmae">
                                            <h6>Nome da Mãe</h6>
                                        </div>
                                        <div class="npai">
                                            <h6>Nome do Pai</h6>
                                        </div>
                                        <?php } ?>
                                    <? if ($filtro_exame == 't') { ?>
                                        <div class="exam">
                                            <h6>Exame</h6>
                                        </div>
                                    <? } ?>
                                       
                                    <?
                                    
                                    $data['retorno_permissao'] = $this->guia->listarpermissaopoltrona();
                                    // var_dump($data['retorno_permissao']);die;
                                    if ($data['retorno_permissao'][0]->prontuario_antigo == 't') { ?>

                                        <div class="oldpront" ></div>

                                        <? } else {

                                    }  ?>

                                    <?php

                                    if (@$empresapermissoes[0]->pesquisar_responsavel == "t") {
                                        ?>
                                        <div class="inmae">
                                            <input type="text" name="nome_mae" class="texto03" value="<?php echo @$_GET['nome_mae']; ?>" />
                                        </div>
                                        <div class="inpai">
                                            <input type="text" name="nome_pai" class="texto03" value="<?php echo @$_GET['nome_pai']; ?>" />
                                        </div>
                                        <?php
                                    }
                                    ?>


                                    <? if ($filtro_exame == 't') { ?>
                                        <div class="tabela_title">
                                            <input type="text" name="guia_id" class="texto02" value="<?php echo @$_GET['guia_id']; ?>" />
                                        </div>
                                    <? } ?>
                                   
                                    
                                    <?
                                    if ($data['retorno_permissao'][0]->prontuario_antigo == 't') {
                                        ?>
                                        <th class="tabela_title" >
                                            <input type="text" name="prontuario_antigo" class="texto01" value="<?php echo @$_GET['prontuario_antigo']; ?>" />
                                        </th>
                                        <?
                                    } else {

                                    }
                                    ?>
                                    <div class="btnenvio" >
                                        <button type="submit" class="btn btn-outline-default btn-round btn-sm btn-src" name="enviar">Pesquisar</button>
                                    </div>
                        </div>
                    </form>

                    <?
                    if (!(($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15) || $this->session->userdata('perfil_id') == 24 )) {
                        ?>
                    <br>
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/novo">
                            <i class="fa fa-plus fa-w"></i> Cadastrar
                        </a>
                        <?
                    }
                    ?>
                    
                    <div class="table-responsive">
                       <div class="panel-body">
                            <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    <th class="tabela_header">Nome</th>
                                    <th class="tabela_header">CPF</th>
                                    <th class="tabela_header">Nascimento</th>
                                    <th class="tabela_header">Telefone</th>
                                    <th class="tabela_header">Celular</th>
                                    <th class="tabela_header">Email</th>
                                    <th class="tabela_header">Email Alternativo</th>
                                    <th class="tabela_header">Município</th>
                                    <th class="tabela_header">
                                <center>A&ccedil;&otilde;es</center></th>
                                </tr>

                                <?php
                                $imagem = $empresapermissoes[0]->imagem;
                                $consulta = $empresapermissoes[0]->consulta;

                                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                $consulta = $this->paciente->listar($_GET);
                                @$total = $consulta->count_all_results();
                                $limit = 10;
                                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                if ($total > 0) {
                                    ?>
                                    <tbody>
                                        <?php
                                        $lista = $this->paciente->listar($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
                                        $estilo_linha = "tabela_content01";
                                        foreach ($lista as $item) {
                                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                            if ($item->celular == "") {
                                                $telefone = $item->telefone;
                                            } else {
                                                $telefone = $item->celular;
                                            }
                                            ?>
                                            <tr>

                                                <td class="<?php echo $estilo_linha; ?>"width="150px;"><?php echo $item->nome; ?></td>
                                                <? if ($filtro_exame == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"></td>
                                                <? } ?>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo substr($item->cpf, 8, 3) . '.' . substr($item->cpf, 6, 3) . '.' . substr($item->cpf, 0, 3). '-' . substr($item->cpf, 0, 2);?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo substr($item->nascimento, 8, 2) . '/' . substr($item->nascimento, 5, 2) . '/' . substr($item->nascimento, 0, 4); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo  "(" . substr($item->telefone, 0, 2) . ")" . substr($item->telefone, 2, strlen($item->telefone) - 7) .  "" . substr($item->telefone, 6, 1) . "-" . substr($item->telefone, 7 , strlen($item->telefone) - 2); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo  "(" . substr($item->celular, 0, 2) . ")" . substr($item->celular, 2, strlen($item->celular) - 7) .  "" . substr($item->celular, 6, 1) . "-" . substr($item->celular, 7 , strlen($item->celular) - 2); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo $item->email; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo $item->email_alternativo; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?php echo $item->municipio; ?></td>
                                                
                                                <?
                                                if (($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15)) {
                                                    ?>
                                                    <td class="<?php echo $estilo_linha; ?> "width="50px;" colspan="9" >

                                                    <? } else {
                                                        ?>
                                                    <td class="<?php echo $estilo_linha; ?> "width="50px;" colspan="9" >
                                                        <?
                                                    }
                                                    ?>
                                                    <?
                                                    if (($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15)) {
                                                        ?>
                                                        <div class="bt_link" >
                                                            <a href="<?= base_url() ?>cadastros/pacientes/visualizarcarregar/<?= $item->paciente_id ?>">
                                                                <b>Visualizar</b>
                                                            </a>
                                                        </div>

                                                        <?
                                                    } else {
                                                        ?>

                                                        <? if (($tecnico_recepcao_editar == 't' || $perfil_id != 15) && $perfil_id != 24) { ?>
                                                            <div style="width:700px;">
                                                                <a class="btn btn-outline-default btn-round btn-sm" href="">
                                                                    <b>Iniciar Estágio</b>
                                                                </a>
                                                                <a class="btn btn-outline-default btn-round btn-sm" href="">
                                                                    <b>Transferir Estágio</b>
                                                                </a>
                                                                <a class="btn btn-outline-default btn-round btn-sm" href="">
                                                                    <b>Encerrar Estágio</b>
                                                                </a>
                                                                
                                                            </div>
                                                        <? } else { ?>
                                                            <div class="bt_link">
                                                                <a href="<?= base_url() ?>cadastros/pacientes/novo/<?= $item->paciente_id ?>">
                                                                    <b>Visualizar</b>
                                                                </a>
                                                            </div>
                                                        <? } ?>
                                                        
                                                        

                                                    </td>
                                                    <!--<td class="<?php echo $estilo_linha; ?>" width="50px;">-->
                                                        <!--<div class="bt_link">-->
                                                            <!--<a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>emergencia/filaacolhimento/novo/<?= $item->paciente_id ?>">-->
                                                                <!--<b>Op&ccedil;&otilde;es</b>-->
                                                            <!--</a>-->
                                                        <!--</div>-->
                                                    <!--</td>-->


                                                    <?php
                                                    
                                                    if($valores_recepcao == 't' || $operador_id == 1 ){
                                                    if($this->session->userdata('perfil_id') != 24){?>
                                                    <!--<td class="<?php echo $estilo_linha; ?>" width="50px;">-->
                                                        <!--<div class="bt_link">-->
                                                            <!--<a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>ambulatorio/guia/orcamento/<?= $item->paciente_id ?>">-->
                                                                <!--<b>Or&ccedil;amento</b>-->
                                                            <!--</a>-->
                                                        <!--</div>-->
                                                    <!--</td>-->
                                                    
                                                    
                                                    
                                                                <?php }

                                                    }?>

                                                    <?
                                                }
                                                ?>

                                            </tr>
                                        </tbody>
                                        <?php
                                    }
                                }
                                ?>
                                <tfoot>
                                    <tr>
                                        <div class="pagination">
                                            <th class="tabela_footer" colspan="">
                                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                                Total de registros: <?php echo $total; ?>
                                            </th>
                                        </div>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>






<!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">

 function verificarCPF() {
        // txtCpf
        var cpf = $("#txtCpf").val();
        var paciente_id = $("#txtPacienteId").val();
        if($('#cpf_responsavel').prop('checked')){
            var cpf_responsavel = 'on';
        }else{
            var cpf_responsavel = '';
        }
        
        // alert(cpf_responsavel);
        $.getJSON('<?= base_url() ?>autocomplete/verificarcpfpaciente', {cpf: cpf, cpf_responsavel: cpf_responsavel, paciente_id: paciente_id,  ajax: true}, function (j) {
            if(j != ''){
                alert(j);
                $("#txtCpf").val('');
            }
        });
    }

    jQuery("#txtTelefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });


    $(function () {
        $("#accordion").accordion();
    });

</script>
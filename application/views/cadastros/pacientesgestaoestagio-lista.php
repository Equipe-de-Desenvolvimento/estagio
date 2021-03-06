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

                    <!-- <br>
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/novo">
                            <i class="fa fa-plus fa-w"></i> Cadastrar
                        </a> -->

                    
                    <div class="table-responsive">
                       <div class="panel-body">
                            <table  class="table table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    <th class="tabela_header">Nome</th>
                                    <th class="tabela_header">CPF</th>
                                    <th class="tabela_header">Telefone</th>
                                    <th class="tabela_header">Email</th>
                                    <th class="tabela_header">Instituição</th>
                                    <th class="tabela_header">Vaga</th>
                                    <th class="tabela_header">Data Associado</th>
                                    <th>Data Prevista Estagio</th>
                                    <th class="tabela_header" colspan="2"><center>A&ccedil;&otilde;es</center></th>
                                </tr>

                                <?php
                                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                $consulta = $this->paciente->listaralunosestagio($_GET);
                                @$total = $consulta->count_all_results();
                                $limit = 10;
                                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                if ($total > 0) {
                                    ?>
                                    <tbody>
                                        <?php
                                        $lista = $this->paciente->listaralunosestagio($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
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

                                                <td><?php echo $item->nome; ?></td>
                                                <td><?php echo substr($item->cpf, 0, 3) . '.' . substr($item->cpf, 3, 3) . '.' . substr($item->cpf, 6, 3). '-' . substr($item->cpf, 9, 2);?></td>
                                                <td ><?php echo  "(" . substr($item->telefone, 0, 2) . ")" . substr($item->telefone, 2, strlen($item->telefone) - 6) . "-" . substr($item->telefone, 8 , strlen($item->telefone) - 2); ?></td>
                                                <td> <?php echo $item->cns; ?></td>
                                                <td ><?php echo $item->nome_fantasia; ?></td>
                                                <td ><?php echo $item->nome_vaga; ?></td>
                                                <td ><?php echo substr($item->data_cadastro, 8, 2).'/'.substr($item->data_cadastro, 5, 2).'/'.substr($item->data_cadastro, 0, 4); ?></td>
                                                <td><?php echo substr($item->data_inicio, 8, 2).'/'.substr($item->data_inicio, 5, 2).'/'.substr($item->data_inicio, 0, 4); ?> - <?php echo substr($item->data_final, 8, 2).'/'.substr($item->data_final, 5, 2).'/'.substr($item->data_final, 0, 4); ?></td>

                                                <?if($item->status_estagio == 'ANALISE'){?>
                                                    <td colspan="2"> 
                                                        <a class="btn btn-outline-default btn-round btn-sm" target="_blank" href="<?=base_url() ?>cadastros/pacientes/iniciaestagio/<?= $item->aluno_estagio_id ?>" class="btn btn-outline-default btn-round btn-sm">
                                                            <b>Iniciar Estágio</b>
                                                        </a>
                                                    </td>
                                                <?}else{?>
                                                    <td>
                                                        <a class="btn btn-outline-default btn-round btn-sm" target="_blank"  href="<?=base_url() ?>cadastros/pacientes/efetivadostatus/<?= $item->aluno_estagio_id ?>" class="btn btn-outline-default btn-round btn-sm" href="">
                                                            <b>Efetivar</b>
                                                        </a>
                                                    </td>

                                                    <td>
                                                        <a class="btn btn-outline-default btn-round btn-sm" href="">
                                                            <b>Transferir Estágio</b>
                                                        </a>
                                                    <br><br>
                                                        <a class="btn btn-outline-default btn-round btn-sm" href="">
                                                            <b>Encerrar Estágio</b>
                                                        </a>
                                                    </td>
                                                <?}?>

                                                    <?
                                                }
                                                ?>

                                            </tr>
                                        </tbody>
                                        <?php
                                    }
                                ?>
                                <tfoot>
                                    <tr>
                                        <div class="pagination">
                                            <th class="tabela_footer" colspan="14">
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
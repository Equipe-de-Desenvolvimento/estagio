
<link href="<?= base_url() ?>css/cadastro/paciente-lista.css?v=1" rel="stylesheet"/>
    <div class="col-sm-12">
        <div class="">
            <div class="" id="pesquisar">
                <div class="alert alert-primary"><b>Minhas Solicitações</b></div>
                <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisarMapaGestao">

                </form>

                    <br>
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/cadastrosolicitacaodevagas/0">
                           Nova Solicitação
                        </a>

                    
                    <div class="table-responsive">
                       <div class="panel-body">
                            <table  class="table table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    <th class="tabela_header">Área</th>
                                    <?if($this->session->userdata('instituicao_id') == ''){?>
                                    <th class="tabela_header">Instituição</th>
                                    <?}?>
                                    <th class="tabela_header">Tipo</th>
                                    <th class="tabela_header">Qtd de Vagas</th>
                                    <th class="tabela_header">Status</th>
                                    <th class="tabela_header" colspan="2"><center>A&ccedil;&otilde;es</center></th> 
                                </tr>
                                     <?php
                                         $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                         $consulta = $this->paciente->listarsolicitacaovagasestagio($_GET);
                                         $total = $consulta->count_all_results();
                                        
                                         $limit = 10;
                                         isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                         ?>
                                            
                                         <body>
                                                <?php
                                                    $lista = $this->paciente->listarsolicitacaovagasestagio($_GET)->limit($limit, $pagina)->orderby("nome_vaga")->orderby("status_vaga", "desc")->get()->result();
                                                    $estilo_linha = "tabela_content01";
                                                     foreach ($lista as $item) {
                                                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content02";
                                                        ?>
                                                        <tr>
                                                            <td ><?php echo $item->nome_vaga; ?></td>
                                                            <?if($this->session->userdata('instituicao_id') == ''){?>
                                                            <td ><?php echo $item->nome_fantasia; ?></td> 
                                                            <?}?>
                                                            <td ><?php echo $item->tipo_vaga; ?></td>
                                                            <td ><?php echo $item->qtde_vagas; ?></td> 
                                                            <td ><?php echo $item->status_vaga; ?></td>
                                                            <?if($item->status_vaga != 'NEGADO' && $item->status_vaga != 'AUTORIZADO'){?>
                                                            <?if($this->session->userdata('instituicao_id') > 0){?>
                                                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/cadastrosolicitacaodevagas/<?=$item->solicitacao_vaga_id?>">Editar</a></td>
                                                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/excluirsolicitacaodevagas/<?=$item->solicitacao_vaga_id?>">Excluir</a></td>
                                                            <?}else{?>
                                                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/autorizarsolicitacaodevagas/<?=$item->solicitacao_vaga_id?>">Autorizar</a></td>
                                                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/negarsolicitacaodevagas/<?=$item->solicitacao_vaga_id?>">Negar</a></td>
                                                            <?}?>
                                                            <?}else{?>
                                                                <td></td>
                                                                <td></td>
                                                            <?}?>
                                                        </tr> 

                                                    <?}?>
            
                                        </body>
                                <tfoot>
                                     <tr>
                                        <th class="tabela_footer" colspan="10">
                                            <div class="pagination">
                                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                                Total de registros: <?php echo $total; ?>
                                            </div>
                                        </th>
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


    // $(function () {
        $("#accordion").accordion();
    });

</script>
<?
 $empresa_id = $this->session->userdata('empresa_id');
 $perfil_id = $this->session->userdata('perfil_id');
 $operador_id = $this->session->userdata('operador_id');
 $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
 $filtro_exame = @$empresapermissoes[0]->filtro_exame_cadastro;
 $tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
 $valores_recepcao = @$empresapermissoes[0]->valores_recepcao;
 $instituicao = $this->paciente->listarinstituicao_vagas();
?>
<link href="<?= base_url() ?>css/cadastro/paciente-lista.css?v=1" rel="stylesheet"/>
    <div class="col-sm-12">
        <div class="">
            <div class="" id="pesquisar">
                <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisarMapaGestao">
                    <div class="row">
                                    <div class="nome">
                                        <h6>Área</h6>
                                        <div>
                                            <input type="text" name="nome_vaga" class="texto05" value="<?php echo @$_GET['nome_vaga']; ?>" />
                                        </div>
                                    </div>
    
                                    <?if($this->session->userdata('instituicao_id') == ''){?>
                                    <div class="nome">
                                        <h6>Instituição</h6>
                                        <div>
                                        <select name="instituicao_id" id="instituicao_id"> 
                                            <option value="">Selecione</option>
                                            <?foreach($instituicao as $item){?>
                                                <option value="<?=$item->instituicao_id?>" <?=(@$_GET['instituicao_id'] == $item->instituicao_id)? 'selected': ''?>><?=$item->nome_fantasia?></option>
                                            <?}?>
                                        </select>
                                        </div>
                                    </div>
                                        <?}?>
                                    <div>
                                        <h6>Tipo</h6>
                                        <div>
                                            <input type="text" id="txtEmpresa" name="tipo_vaga" class="texto05" value="<?php echo @$_GET['tipo_vaga']; ?>" />
                                        </div>
                                    </div>  
                                    <div class="btnenvio" >
                                        <button type="submit" class="btn btn-outline-default btn-round btn-sm btn-src" name="enviar">Pesquisar</button>
                                    </div>
                    </div>
                </form>

                    <br>
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/cadastrodevagas/0">
                            <i></i> Cadastrar
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
                                    <th class="tabela_header" colspan="2"><center>A&ccedil;&otilde;es</center></th>

                                </tr>
                                     <?php
                                         $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                         $consulta = $this->paciente->listarvagasestagio($_GET);
                                         $total = $consulta->count_all_results();
                                        
                                         $limit = 10;
                                         isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                         if ($total > 0) {
                                         }
                                         ?>
                                            
                                         <body>
                                                <?php
                                                    $lista = $this->paciente->listarvagasestagio($_GET)->limit($limit, $pagina)->orderby("qtde_vagas", 'desc')->orderby("nome_vaga")->get()->result();
                                                    $estilo_linha = "tabela_content01";
                                                    $qtd_total_vagas = 0;
                                                     foreach ($lista as $item) {

                                                        $qtd_total_vagas = $qtd_total_vagas + $item->qtde_vagas;
                                                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content02";
                                                        ?>
                                                        <tr>
                                                            <td ><?php echo $item->nome_vaga; ?></td>
                                                            <?if($this->session->userdata('instituicao_id') == ''){?>
                                                            <td ><?php echo $item->nome_fantasia; ?></td> 
                                                            <?}?>
                                                            <td ><?php echo $item->tipo_vaga; ?></td>
                                                            <td ><?php echo $item->qtde_vagas; ?></td>

                                                             <?if($this->session->userdata('instituicao_id') == ''){?>
                                                            <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/cadastrodevagas/<?=$item->vaga_id?>">Editar</a></td>
                                                            <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/excluircadastrodevagas/<?=$item->vaga_id?>">Excluir</a></td>
                                                            <?}elseif($item->qtde_vagas > 0){?>
                                                                <td colspan="2"> <a class="btn btn-outline-default btn-sm" onclick="javascript:window.open('<?= base_url() ?>cadastros/pacientes/associaralunoaestagio/<?= $item->vaga_id ?>/<?=$item->instituicao_id?>', '_blank', 'toolbar=no,Location=no,menubar=no, width=800,height=600');" href="#"> Associar Estagiario </a></td>
                                                            <?}else{?>
                                                                <td> </td>
                                                                <td> </td>
                                                            <?}?>
                                                        </tr> 

                                                    <?}?>
            
                                        </body>
                                <tfoot>
                                     <tr>
                                        <th class="tabela_footer" colspan="6">
                                            <div class="pagination">
                                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                                Total de Vagas: <?php echo $qtd_total_vagas; ?>
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
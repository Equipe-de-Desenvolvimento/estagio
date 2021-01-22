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
                <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisarMapaGestao">
                    <div class="row">
                                    <div class="nome">
                                        <h6>Situação</h6>
                                        <div>
                                            <input type="text" name="nome" class="texto05" value="<?php echo @$_GET['nome']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Instiuição</h6>
                                        <div>
                                            <input type="text" id="txtEmpresa" name="empresa" class="texto05" value="<?php echo @$_GET['empresa']; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Área</h6>
                                        <div class="inasc">
                                            <input type="text" id="txtArea" name="area" placeholder="" class="texto05" value="<?php echo @$_GET['area']; ?>" />
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <h6>Telefone</h6>
                                        <div>
                                            <input type="text" id="txtTelefone" name="telefone" placeholder="(99)9999-9999" class="texto05" value="<?php echo @$_GET['telefone']; ?>" />
                                        </div>
                                    </div>
                                    <div> --> 
                                    <div>
                                        <h6>Vagas</h6>
                                        <div>
                                            <input type="text" name="vagas" placeholder="" class="texto05" value="<?php echo @$_GET['vagas']; ?>" />
                                        </div>
                                    </div>  
                                    <div class="btnenvio" >
                                        <button type="submit" class="btn btn-outline-default btn-round btn-sm btn-src" name="enviar">Pesquisar</button>
                                    </div>
                    </div>
                </form>

                    <?
                    if (!(($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15) || $this->session->userdata('perfil_id') == 24 )) {
                        ?>
                    <br>
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/forma/">
                            <i></i> Cadastrar
                        </a>
                        <?
                    }
                    ?>
                    
                    <div class="table-responsive">
                       <div class="panel-body">
                            <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    <th class="tabela_header">Situação</th>
                                    <th class="tabela_header">Instituição</th>
                                    <th class="tabela_header">Área</th>
                                    <th class="tabela_header">Vaga</th>
                                    <!-- <th class="tabela_header"><center>A&ccedil;&otilde;es</center></th> -->

                                    <? if ($filtro_exame == 't') { ?>
                                        <th class="tabela_header"></th>
                                        
                                    <? } ?>
                                </tr>
                                     <?php
                                         $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                         $consulta = $this->paciente->listarMapaVagas($_GET);
                                         $total = $consulta->count_all_results();
                                        
                                         $limit = 10;
                                         isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                         if ($total > 0) {
                                         }
                                         ?>
                                            
                                         <body>
                                                <?php
                                                    $lista = $this->paciente->listarMapaVagas($_GET)->limit($limit, $pagina)->orderby("c.situacao")->get()->result();
                                                    // var_dump($lista); die;
                                                    $estilo_linha = "tabela_content01";
                                                     foreach ($lista as $item) {
                                                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content02";
                                                    // var_dump($item->situacao);
                                                        ?>
                                                        <tr>
                                                            <td class="<?php echo $estilo_linha; ?>"width="200px"><?php echo $item->situacao; ?></td>
                                                            <td class="<?php echo $estilo_linha; ?>"width="200px"><?php echo $item->instituicao; ?></td>
                                                            <td class="<?php echo $estilo_linha; ?>"width="200px"><?php echo $item->area; ?></td> 
                                                            <td class="<?php echo $estilo_linha; ?>"width="200px"><?php echo $item->vaga; ?></td> 
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
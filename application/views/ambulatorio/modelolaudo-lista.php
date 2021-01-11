<?
$empresa_id = $this->session->userdata('empresa_id');
$perfil_id = $this->session->userdata('perfil_id');
$operador_id = $this->session->userdata('operador_id');
$empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
$filtro_exame = @$empresapermissoes[0]->filtro_exame_cadastro;
$tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
$valores_recepcao = @$empresapermissoes[0]->valores_recepcao;
$excluircadastro_id = $this->session->userdata('excluircadastro_id');
?>
<link href="<?= base_url() ?>css/cadastro/paciente-lista.css" rel="stylesheet"/>
<div id="wrapper"> <!-- Inicio da DIV content -->
        <div class="col-sm-12">
            <div class="panel panel-default">
                <form class="" id="pesquisar">
                    <div method="get" action="<?php echo base_url() ?>ambulatorio/modelolaudo/pesquisar">
                        <div class="row">
                        <div>
                            <h6>Razão Social</h6>
                            <input type="text" name="nome" class="texto04" placeholder="Nome da Instituição" value="<?php echo @$_GET['nome']; ?>" />
                        </div>
                            
                        <div>
                            <h6>Telefone</h6>
                            <input type="text" name="telefone" class="text" id="txtTelefone" class="form-control" placeholder="(99)9999-9999" value="<?php echo @$_GET['telefone']; ?>" />
                        </div>
                            
                        <div>
                            <h6>Email</h6>
                            <input type="text" name="email" placeholder="email ou email alternativo" class="text" class="form-control"  value="<?php echo @$_GET['email']; ?>" />
                        </div>
                            
                        <div>
                            <h6>CNPJ/ CPF</h6>
                            <input type="text" name="cnpj" placeholder="CNPJ ou CPF" class="text" class="form-control"  value="<?php echo @$_GET['cnpj']; ?>" />
                        </div>

                        <div class="btnenvio" >
                            <button type="submit"  class="btn btn-outline-default btn-round btn-sm btn-src" name="enviar">Pesquisar</button>
                        </div> 
                        </div>
                     </div>
                </form>   
                    <?
                    if (!(($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('pPesquisarerfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15) || $this->session->userdata('perfil_id') == 24 )) {
                    ?>
                    <br>
             
                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/novainstituicao">
                            
                            <i class=""></i> Cadastrar
                        </a>
                        <?
                    }
                    ?>
                        <div class="table-responsive">
                            
                              <div class="panel-body">
                            <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    
                                    <th class="tabela_header">Razão Social</th>
                                    <th class="tabela_header">CNPJ</th>
                                    <th class="tabela_header">CPF</th>
                                    <th class="tabela_header">Email</th>
                                    <th class="tabela_header">Email Alternativo</th>
                                    <th class="tabela_header">Telefone</th>
                                    <th class="tabela_header">Telefone 2</th>
                                    <th class="tabela_header">Whatsapp</th>
                                    <th class="tabela_header">Endereço</th>
                                    <th class="tabela_header">Município</th>
                                    <th style="text-align: center;">Ações</th>
                                          
                                        
                                    <? if ($filtro_exame == 't') { ?>
                                        <th class="tabela_header"></th>
                                        
                                    <? } ?>
                                 
                                </tr>

                                <?php
                                $imagem = $empresapermissoes[0]->imagem;
                                $consulta = $empresapermissoes[0]->consulta;

                                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                $consulta = $this->paciente->listarinstituicao($_GET);
                                $total = $consulta->count_all_results();
                                $limit = 10;
                                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                if ($total > 0) {
                                    ?>
                                    <tbody>
    
                                        <?php
                                        $lista = $this->paciente->listarinstituicao($_GET)->orderby('i.nome')->limit($limit, $pagina)->get()->result();
                                        $estilo_linha = "tabela_content01";
                                        foreach ($lista as $item) {
                                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                            if ($item->telefone != '' && strlen($item->telefone) > 3) {

                                                if (preg_match('/\(/', $item->telefone)) {
                                                    $telefone = $item->telefone;
                                                } else {
                                                    $telefone = "(" . substr($item->telefone, 0, 2) . ")" . substr($item->telefone, 2, strlen($item->telefone) - 7) .  "" . substr($item->whatsapp, 6, 1) . "-" . substr($item->whatsapp, 7 , strlen($item->whatsapp) - 2);
                                                }
                                            } else {
                                                $telefone = '';
                                            }
                                        
                                            
                                             if ($item->telefone2 != '' && strlen($item->telefone2) > 3) {

                                                if (preg_match('/\(/', $item->telefone2)) {
                                                    $telefone2 = $item->telefone2;
                                                } else {
                                                    $telefone2 = "(" . substr($item->telefone2, 0, 2) . ")" . substr($item->telefone2, 2, strlen($item->telefone2) - 7) .  "" . substr($item->telefone2, 6, 1) . "-" . substr($item->telefone2, 7 , strlen($item->telefone2) - 2);
                                                }
                                            } else {
                                                $telefone2 = '';
                                            }
                                            
                                            if ($item->whatsapp != '' && strlen($item->whatsapp) > 3) {

                                                if (preg_match('/\(/', $item->whatsapp)) {
                                                    $whatsapp = $item->whatsapp;
                                                } else {
                                                    //(99)99999-9999
                                                    $whatsapp = "(" . substr($item->whatsapp, 0, 2) . ")" . substr($item->whatsapp, 2, strlen($item->whatsapp) - 7) .  "" . substr($item->whatsapp, 6, 1) . "-" . substr($item->whatsapp, 7 , strlen($item->whatsapp) - 2);
                                                }
                                            } else {
                                                $whatsapp = '';
                                            }
                                            
                                           
                                            
                                            ?>
                                        
                                                <td class="<?php echo $estilo_linha; ?>"width="200px"><?php echo $item->nome; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $item->cnpj; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo substr($item->cpf, 8, 3) . '.' . substr($item->cpf, 6, 3) . '.' . substr($item->cpf, 0, 3). '-' . substr($item->cpf, 0, 2); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $item->email; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $item->email_alternativo; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $telefone; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $telefone2; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $whatsapp; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $item->endereco; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"width="150px;" ><?php echo $item->municipio; ?></td>
                                                
                                                <? if ($filtro_exame == 't') { ?>
                                                    <td class="<?php echo $estilo_linha; ?>"></td>
                                                <? } ?>

                                                <?
                                                if (($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15)) {
                                                    ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan="2" >

                                                    <? } else {
                                                        ?>
                                                    <td class="<?php echo $estilo_linha; ?>" width="50px;" colspan=" >
                                                        <?
                                                        
                                                    }
                                                    ?>
                                                        
                                                        
                                                    <?
                                                    if (($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 7) || ($empresapermissoes[0]->tecnico_acesso_acesso == 't' && $this->session->userdata('perfil_id') == 15)) {
                                                        ?>
                                                            <a href="<?= base_url() ?>cadastros/pacientes/visualizarcarregar/<?= $item->paciente_id ?>">
                                                                <b>Visualizar</b>
                                                            </a>

                                                        <?
                                                    } else {
                                                        ?>

                                                        <? if (($tecnico_recepcao_editar == 't' || $perfil_id != 15) && $perfil_id != 24) { ?>
                                                            <div class="bt_link">
                                                                     <a class="btn btn-outline-default btn-round btn-sm" href="<?=base_url() ?>cadastros/pacientes/novainstituicao/<?= $item->instituicao_id ?>">
                                                                  <b>Editar</b>
                                                                </a> 
                                                            </div>
                                                       <? } else { ?>
                                                           <div class="bt_link">
                                                               <a href="<?= base_url() ?>cadastros/pacientes/visualizarcarregar/<?= $item->paciente_id ?>">
                                                                    <b>Visualizar</b>
                                                                </a>
                                                            </div>
                                                        <? } ?>
                                                    </td>

                                                    <?php
                                                    
                                                    if($this->session->userdata('perfil_id') != 24){?>
                                                    <td class="<?php echo $estilo_linha; ?>">
                                                        <div class="bt_link">
                                                            <a class="btn btn-outline-default btn-round btn-sm" href="<?=base_url() ?>cadastros/pacientes/excluircadastro/<?= $item->instituicao_id ?>">       
                                                                <b>Excluir</b>
                                                            </a></div>
                                                    </td>
                                                                <?php }
                                                                
                                                }
                                                ?>



                                        </tbody>
                                        <?php
                                    }
                                }
                                ?>
                                <tfoot>
                                    <tr>
                                        <th class="tabela_footer" colspan="9">
                                            <div class="pagination">
                                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                                Total de registros: <?php echo $total; ?>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
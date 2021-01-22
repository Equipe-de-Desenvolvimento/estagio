<link href="<?= base_url() ?>css/cadastro/fornecedor-lista.css" rel="stylesheet"/>
<div class="panel-body"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/fornecedor/carregarfornecedor/">
           Novo credor/devedor
        </a>
    </div>    

<br>
        <div class="alert alert-primary"><b>Manter Credor/Devedor</b></div>
            
            <form method="get" action="<?= base_url() ?>cadastros/fornecedor/fornecedor/">
                <fieldset><br>
                    <div class="row">
                        <div class="nome">
                            <label>Razão Social</label>
                            <input type="text" name="nome"  class="control" placeholder="Nome da Instituição" value="<?php echo @$_GET['nome']; ?>" />
                        </div>
                    <div class="row"></div>    
                        <div>
                            <label>CPF/CNPJ</label>
                            <input type="text" name="cpf"  placeholder="000.000.000-00" id="txtCpf" value="<?php echo @$_GET['cpf']; ?>" />
                        </div>
                        <div>
                            <label>Telefone</label>
                            <input type="text" name="telefone"  placeholder="(00)00000-0000" id="txtTelefone" value="<?php echo @$_GET['telefone']; ?>" />
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="text" name="email"  placeholder="" id="txtEmail" value="<?php echo @$_GET['email']; ?>" />
                        </div>
                        
                        <div class="status">
                            <label colspan="2" class="tabela_title">Status</label>
                            <select name="ativo" id="" class="texto04">
                                <option value="t" <? if(@$_GET['ativo'] != 'f') echo 'selected'; ?>>Ativo</option>
                                <option value="f" <? if(@$_GET['ativo'] == 'f') echo 'selected'; ?>>Inativo</option>
                            </select>
                        </div>
                        
                        <div>
                            <button class="btn btn-outline-default btn-sm" type="submit" id="enviar">Pesquisar</button>
                        </div>

                    </div>

                </fieldset>
                <br/>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
<!--                        <tr>
                            <th colspan="2" class="tabela_title">Nome</th>
                            <th colspan="2" class="tabela_title">Status</th>
                            <th colspan="0" class="tabela_title"></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="tabela_title">
                                <input type="text" name="nome" class="texto10" value="<?php //echo @$_GET['nome']; ?>" />
                            </th>
                            <th colspan="" class="tabela_title">
                                <select name="ativo" id="">
                                    <option value="t" <?// if(@$_GET['ativo'] != 'f') echo 'selected'; ?>  >Ativo</option>
                                    <option value="f" <?// if(@$_GET['ativo'] == 'f') echo 'selected'; ?> >Inativo</option>
                                </select>
                            </th>
                            <th colspan="" class="tabela_title">
                                <button type="submit" id="enviar">Pesquisar</button>
                            </th>
                        </tr>-->
                        <tr>
                            <th class="tabela_header">Razão Social</th>
                            <th class="tabela_header">Email</th>
                            <th class="tabela_header">CNPJ</th>
                            <th class="tabela_header">CPF</th>
                            <th class="tabela_header">Telefone</th>
                            <th class="tabela_header" width="70px;" colspan="4"><center>Detalhes</center></th>
                        </tr>
                    </thead>
                    <?php
                        $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                        $consulta = $this->fornecedor->listar($_GET);
                        $total    = $consulta->count_all_results();
                        $limit    = $limite_paginacao;
                        isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                        if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                            if ($limit != "todos") {
                                $lista = $this->fornecedor->listar($_GET)->orderby('razao_social')->limit($limit, $pagina)->get()->result();
                            } else {
                                $lista = $this->fornecedor->listar($_GET)->get()->result();
                            }
                            $estilo_linha = "tabela_content01";
                            foreach ($lista as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                         ?>
                         
                            <tr>
                                    <td class="<?php echo $estilo_linha; ?>"width="270px;"><?= $item->razao_social; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"width="200px;"><?= $item->email; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"width="200px;"><?= $item->cnpj; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"width="200px;"><?= $item->cpf; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"width="200px;"><?= $item->telefone; ?></td>
                                
                                    
                                    <? if($item->ativo == 't') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                            <a class="btn btn-outline-default btn-sm" href="<?= base_url() ?>cadastros/fornecedor/carregarfornecedor/<?= $item->financeiro_credor_devedor_id ?>" target="_blank">Editar</a>
                                        </td>
                                        
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                            <a class="btn btn-outline-default btn-sm" href="<?= base_url() ?>cadastros/fornecedor/gravarunificarcredor<?= $item->financeiro_credor_devedor_id ?>" target="_blank">Unificar</a>
                                        </td>
                                        
                                        <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                            <a class="btn btn-outline-default btn-sm" href="<?= base_url() ?>cadastros/fornecedor/excluir/<?= $item->financeiro_credor_devedor_id ?>">Excluir</a>
                                        </td>
                                    <? } else { ?>
                                        <td colspan="2" class="//<?php echo $estilo_linha; ?>" width="70px;">                                  
                                            <a onclick="javascript: return confirm('Deseja realmente reativar esse Fornecedor?');" href="//<?= base_url() ?>cadastros/fornecedor/reativar/<?= $item->financeiro_credor_devedor_id ?>">Reativar</a>
                                        </td>                                        
                                    <? } ?>
                            </tr>

                            </tbody>
                            <?php
                                    }
                                }
                            ?>
                            <tfoot>
                                <tr>
                                    <th class="tabela_footer" colspan="8">
                                       <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                       Total de registros: <?php echo $total; ?>
                                        <div style="display: inline">
                                            <span style="margin-left: 20px; color: white; font-weight: bolder;"> Limite: </span>
                                            <select style="width: 50px">
                                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>cadastros/fornecedor/pesquisar/10');" <? if ($limit == 10) { echo "selected"; } ?>> 10 </option>
                                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>cadastros/fornecedor/pesquisar/50');" <? if ($limit == 50) { echo "selected"; } ?>> 50 </option>
                                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>cadastros/fornecedor/pesquisar/100');" <? if ($limit == 100) { echo "selected"; } ?>> 100 </option>
                                                <option onclick="javascript:window.location.href = ('<?= base_url() ?>cadastros/fornecedor/pesquisar/todos');" <? if ($limit == "todos") { echo "selected"; } ?>> Todos </option>
                                            </select>
                                        </div>
                                    </th>
                        </tr>
                    </tfoot>
                </table>

            </form>


</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function() {
        $( "#accordion" ).accordion();
    });
    
    function verificaDependenciasFornecedor(credorDevedor){
        jQuery(function () {
            jQuery.ajax({
                type: "GET",
                url: "<?= base_url(); ?>cadastros/fornecedor/verificadependenciasexclusao/"+credorDevedor,
                dataType: "json",
                success: function (retorno) {
                    var conv = retorno.convenios;
                    var forn = retorno.fornecedores;
                    var oper = retorno.operadores;
                    if (conv == 0 && forn == 0 && oper == 0){
                        var resposta = confirm("Deseja realmente excluir o fornecedor?");
                        
                        if(resposta){
                            window.open('<?= base_url() ?>cadastros/fornecedor/excluir/' + credorDevedor, '_self');
                        }
                    }
                    else {
                        var msg = "";
                        if(conv != 0) msg += conv + " convenio(s), ";
                        if(forn != 0) msg += forn + " forncedor(es), ";
                        if(oper != 0) msg += oper + " operador(es), ";
                        msg = msg.substring(0, msg.length - 2) + " associado(s).";
                        
                        var resposta = confirm("Este fornecedor possui "+msg+" Deseja prosseguir?");
                        
                        if(resposta){
                            window.open('<?= base_url() ?>cadastros/fornecedor/excluir/' + credorDevedor, '_self');
                        }
                    }
                }
            });
        });
    }
//onclick="javascript: return confirm('Deseja realmente exlcuir esse Fornecedor?');" href="<?= base_url() ?>cadastros/fornecedor/excluir/<?= $item->financeiro_credor_devedor_id ?>"
</script>

<?
$empresa_id = $this->session->userdata('empresa_id');
$empresapermissao = $this->guia->listarempresasaladepermissao();
?>
<div class="panel-body"> <!-- Inicio da DIV content -->
    <? $perfil_id = $this->session->userdata('perfil_id'); ?>
    <div class="bt_link_new">
        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>seguranca/operador/novo">
            Novo Profissional
        </a>
    </div>
        <div class="alert alert-primary"><b >Manter Profissionais</b></div>
            <table>
                <thead>
                <form method="get" action="<?= base_url() ?>seguranca/operador/pesquisar">
                </thead>
                    <tr>
                        <!-- <th class="tabela_title">
                            <select name="ativo" id="empresa" class="size1">
                                <option value="t" <?= ((@$_GET['ativo'] == 'f') ? '' : 'selected="selected"') ?>>Ativo</option>
                                <option value="f" <?= ((@$_GET['ativo'] == 'f') ? 'selected="selected"' : '') ?>>Não-ativo</option>

                            </select>

                        </th> -->
                        <th class="tabela_title" style="text-align: left; width: 56%">
                            <select name="ativo" id="empresa" class="size1">
                                <option value="t" <?= ((@$_GET['ativo'] == 'f') ? '' : 'selected="selected"') ?>>Ativo</option>
                                <option value="f" <?= ((@$_GET['ativo'] == 'f') ? 'selected="selected"' : '') ?>>Não-ativo</option>

                            </select>            
                            <input type="text" name="nome" size="40" value="<?php echo @$_GET['nome']; ?>" />
                            <button type="submit" id="enviar">Pesquisar</button>  
                        </th> 

                    </tr>
                </form>

            </table>
            <table style="width: 100%" class="table table-striped table-bordered table-hover"  id="dataTables-example">
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Usu&aacute;rio</th>
                        <th class="tabela_header">Perfil</th>
                        <th class="tabela_header">Ativo</th>
                        <th class="tabela_header" colspan="30" ><center>A&ccedil;&otilde;es</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->operador_m->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = $limite_paginacao;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>

                    <?php
                    if ($limit != "todos") {
                        $lista = $this->operador_m->listar($_GET)->orderby('ativo desc')->orderby('nomeperfil')->orderby('nome')->limit($limit, $pagina)->get()->result();
                    } else {
                        $lista = $this->operador_m->listar($_GET)->orderby('ativo desc')->orderby('nomeperfil')->orderby('nome')->get()->result();
                    }
                    $estilo_linha = "tabela_content01";
                    foreach ($lista as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->usuario; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->nomeperfil; ?></td>
                            <? if ($item->ativo == 't') { ?>
                                <td class="<?php echo $estilo_linha; ?>">Ativo</td>
                            <? } else { ?>
                                <td class="<?php echo $estilo_linha; ?>">Não Ativo</td>
                            <? } ?>
                            <? if ($item->ativo == 't') { ?>
                                <? if ($perfil_id == 1) { ?>
                                    <td class="<?php echo $estilo_linha; ?>" ><div class="bt_link">
                                            
                                        </div>
                <!--                                    href="<?= base_url() ?>seguranca/operador/excluirOperador/<?= $item->operador_id; ?>"-->
                                    </td>
                                    <?
                                }
                                if ($perfil_id == 1) {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>" >
                                    <div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" style="cursor: pointer;" onclick="javascript: return confirm('Deseja realmente excluir o operador <?= $item->usuario; ?>');" href="<?= base_url() . "seguranca/operador/excluirOperador/$item->operador_id"; ?>"
                                            >Excluir
                                        </a>
                                        <a class="btn btn-outline-default btn-round btn-sm" style="cursor: pointer;" onclick="javascript:window.open('<?= base_url() . "seguranca/operador/alterar/$item->operador_id"; ?> ', '_blank');">Editar
                                        </a>
                                        <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript:window.open('<?= base_url() ?>seguranca/operador/anexararquivo/<?= $item->operador_id ?>');">
                                            Documentação  
                                        </a>
                                    </div>
                <!--                                        href="<?= base_url() ?>seguranca/operador/alterar/<?= $item->operador_id ?>"-->
                                    </td>

                                <? } ?>

                                <? if ($perfil_id != 5) { ?>
                                    
                                <? } ?>
                               

                                <? if (@$empresapermissao[0]->desativar_personalizacao_impressao == 'f') { ?>
                                   
                                <? } ?>

                                <? if ($perfil_id != 5) { ?>
                                    
                                <? } ?>
                            <? } else { ?>
                                <td class="<?php echo $estilo_linha; ?>" ><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" style="cursor: pointer;" onclick="javascript: return confirm('Deseja realmente reativar o operador <?= $item->usuario; ?>');" href="<?= base_url() . "seguranca/operador/reativaroperador/$item->operador_id"; ?>"
                                           >Reativar
                                        </a>
                                    </div>
            <!--                                    href="<?= base_url() ?>seguranca/operador/excluirOperador/<?= $item->operador_id; ?>"-->
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" ></td>
                                <td class="<?php echo $estilo_linha; ?>" ></td>
                                <td class="<?php echo $estilo_linha; ?>" ></td>
                                <td class="<?php echo $estilo_linha; ?>" ></td>
                                <td class="<?php echo $estilo_linha; ?>" ></td>
                            <? } ?>
 
                            <?
                            if ($item->perfil_id == 4 || $item->perfil_id == 19 || $item->perfil_id == 22 || $item->perfil_id == 1) {
                                ?>
                                <td class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        
                                    </div>
                                </td>
                            <? }else{
                                ?>
                                <td class="<?php echo $estilo_linha; ?>"> 
                                
                                </td>
                                <?
                            } ?>
                        

                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="30">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                            <div style="display: inline">
                                <span style="margin-left: 15px; color: white; font-weight: bolder;"> Limite: </span>
                                <select style="width: 50px">
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>seguranca/operador/pesquisar/50');" <?
                            if ($limit == 50) {
                                echo "selected";
                            }
                            ?>> 50 </option>
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>seguranca/operador/pesquisar/100');" <?
                                            if ($limit == 100) {
                                                echo "selected";
                                            }
                            ?>> 100 </option>
                                    <option onclick="javascript:window.location.href = ('<?= base_url() ?>seguranca/operador/pesquisar/todos');" <?
                                            if ($limit == "todos") {
                                                echo "selected";
                                            }
                            ?>> Todos </option>
                                </select>
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

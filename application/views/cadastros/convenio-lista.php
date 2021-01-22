<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <td>
                <div class="bt_link_new">
                    <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/carregar/0">
                        Novo Convênio
                    </a>
                </div>
            </td>
            <td>
                <div class="bt_link_new">
                    <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/pesquisarlogs">
                        Convênio Logs
                    </a>
                </div>
            </td>
<!--            <td>
                <div class="bt_link_new">
                    <a href="<?php echo base_url() ?>ambulatorio/procedimentoplano/carregarprocedimentoplanoexcluirgrupo">
                        Excluir Proc. Grupo
                    </a>
                </div>
            </td>-->
        </tr>
    </table>
    <?
    $empresa = $this->convenio->listarempresa();
    
    ?>
    <div id="">
    <div class="alert alert-primary"><a>Manter Convênio</a></div>
        <div>
                <thead>
                    <tr>
                        <th colspan="7" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>cadastros/convenio/pesquisar">
                                <input type="text" name="nome" class="texto10 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                                <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                        
                    </tr>
                </thead>
            <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                <tr>
                    <th class="tabela_header">Nome</th>
                    <th colspan="10" class="tabela_header"><center>Detalhes</center></th>
                </tr>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->convenio->listar($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    
                    ?>
                    <tbody>
                        <?php
                        
                        $lista = $this->convenio->listar($_GET)->limit($limit, $pagina)->orderby('nome')->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/carregar/<?= $item->convenio_id ?>">
                                            Editar
                                        </a>
                                    </div>
                                </td>
                                <? if($item->convenio_id == @$empresa[0]->convenio_padrao_id){ ?>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/grupopadrao/<?= $item->convenio_id ?>">
                                            Grupo Padrão
                                        </a>
                                    </div>
                                </td>
                                <? } ?>
                                <? if($item->associado == "f"){ ?>
                                <div>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/copiar/<?= $item->convenio_id ?>">
                                                Copiar
                                            </a>
                                        </div>
                                    </td>
                                </div>    
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="width: 85px;">
                                            <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/desconto/<?= $item->convenio_id ?>">
                                                Ajuste (%)
                                            </a>
                                        </div>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="80px;"><div class="bt_link" style="width: 110px;">
                                            <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>ambulatorio/procedimentoplano/carregarprocedimentoplanoexcluirgrupo/<?= $item->convenio_id ?>">
                                                Excluir Proc.
                                            </a>
                                        </div>
                                    </td>
                                <? } else { ?>
                                    <!-- <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="width: 100px;">
                                            <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/ajustargrupoeditar/<?= $item->convenio_id ?>">
                                                Ajuste Grupo
                                            </a>
                                        </div>
                                    </td>                                     -->
                                <? } ?>
                                
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="width: 100px;">
                                        <a class="btn btn-outline-default btn-round btn-sm" target="_blank" href="<?php echo base_url() ?>ambulatorio/procedimentoplano/carregarprocedimentoplanoformapagamento/<?= $item->convenio_id ?>">
                                            Pagamento
                                        </a>
                                    </div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="width: 30px;">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/anexararquivoconvenio/<?= $item->convenio_id ?>">
                                            Logo
                                        </a>
                                    </div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link" style="width: 70px;">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/convenio/empresaconvenio/<?= $item->convenio_id ?>">
                                            Empresa
                                        </a>
                                    </div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript: return confirm('Deseja realmente excluir o convenio?\n\nObs: Irá excluir também os procedimentos associados ao convenio  ');" href="<?= base_url() ?>cadastros/convenio/excluir/<?= $item->convenio_id ?>">
                                            
                                            Excluir
                                        </a>
                                    </div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/setores/<?= $item->convenio_id ?>">                                            
                                            Setores 
                                        </a>
                                    </div>
                                </td>

                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link" style="">
                                <a class="btn btn-outline-default btn-round btn-sm"  onclick="javascript:window.open('<?= base_url() ?>cadastros/convenio/log/<?= @$item->convenio_id ?>', '', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);" >LOG</a>
                                </td>


                                <td class="<?php echo $estilo_linha; ?>" colspan="10"></td>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="12">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
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

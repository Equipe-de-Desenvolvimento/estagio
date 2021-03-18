 
<? $operador_id = $this->session->userdata('operador_id'); ?>

<div class="col-sm-12">
    <div class="">
        <div class="" id="pesquisar">
            <div class="table-responsive">
                <div class="panel-body">
                    <table class="table table-bordered table-hover" id="dataTables-example"> 
                        <tr>
                            <th class="tabela_header">Razão Social</th>
                            <th class="tabela_header">CNPJ</th>
                            <th class="tabela_header">Endereço</th>
                            <th class="tabela_header">Telefone para contato</th>
                            <th class="tabela_header">Email institucional</th>
                            <th class="tabela_header">Representante da Unidade Concedente</th>
                            <th class="tabela_header">Cargo do Representante da Unidade</th>
                            <th class="tabela_header">Ações</th>
                        </tr>

                        <?php
                        $url = $this->utilitario->build_query_params(current_url(), $_GET);
                        $consulta = $this->empresa->listar($_GET);
                        $total = $consulta->count_all_results();
                        $limit = 10;
                        isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                        if ($total > 0) {
                            ?>
                            <tbody>
                                <?php
                                $lista = $this->empresa->listar($_GET)->limit($limit, $pagina)->orderby("nome")->get()->result();
                                $estilo_linha = "tabela_content01";
                                foreach ($lista as $item) {
                                    ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                    ?>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->razao_social; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->cnpj; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->endereco; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->telefone; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->email_institucional; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->representante_unidade; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->cbo_nome; ?></td> 
                                        <td class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                                <a href="<?= base_url() ?>ambulatorio/empresa/carregarempresa/<?= $item->empresa_id ?>">Editar</a></div>
                                        </td> 
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
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

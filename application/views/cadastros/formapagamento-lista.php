
<div class="panel-body"> <!-- Inicio da DIV content -->

        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/formapagamento/carregarformapagamento/0">
            Nova Forma de Pagamento
        </a>
        <br><br>
        <div class="alert alert-primary"><b >Manter Forma de Pagamento</b></div>

        <form method="get" action="<?= base_url() ?>cadastros/formapagamento/pesquisar">
            <div class="row">
                <div class="col-lg-4">
                    <label for="">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" />
                </div>
                <div class="col-lg-4"><br>
                    <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                </div>
            </div>
        </form>
                
            <br>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header" width="70px;" colspan="5"><center>Detalhes</center></th>
                    </tr>
                </thead>
                <?php
                    $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->formapagamento->listar($_GET);
                    $total    = $consulta->count_all_results();
                    $limit    = 10;
                    isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?php
                        $lista = $this->formapagamento->listar($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01"; ?>
                            <tr >
<!--                                Caso esteja usando o netbeans e essa linha fique vermelha. Ignore-->
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>

                                <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/formapagamento/carregarformapagamento/<?= $item->forma_pagamento_id ?>">Editar</a>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript: return confirm('Deseja realmente exlcuir esse Forma?');" href="<?= base_url() ?>cadastros/formapagamento/excluir/<?= $item->forma_pagamento_id ?>">Excluir</a>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/formapagamento/formapagamentoparcelas/<?= $item->forma_pagamento_id ?>">Parcelas</a>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/formapagamento/formapagamentocontaempresa/<?= $item->forma_pagamento_id ?>">Conta Empresa</a>
                                </td>
                        </tr>

                        </tbody>
                        <?php
                        }
                    }
                        ?>
                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="5">
                                   <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                                </th>
                    </tr>
                </tfoot>
            </table>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function() {
        $( "#accordion" ).accordion();
    });

</script>


<script type='text/javascript'>setTimeout(function () {  alert('Sessão Expirada');  window.location = '<?= base_url()?>login/sair';  }, 1800*4000);</script>
<div class="panel-body"> <!-- Inicio da DIV content -->
        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/forma/carregarforma/0">
            Nova Conta
        </a>
    <br><br>

        <div class="alert alert-primary"><b>Manter Conta</b></div>

        <form method="get" action="<?= base_url() ?>cadastros/forma/pesquisar">
            <div class="row">
                <div class="col-lg-4">
                    <label for="">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" />
                </div>
                <div class="col-lg-4"> <br>
                    <button class="btn btn-outline-default btn-round btn-sm" type="submit" id="enviar">Pesquisar</button>
                </div>
            </div>

        </form>
        <br>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Agência</th>
                        <th class="tabela_header">Conta</th>
                        <th class="tabela_header">Empresa</th>
                        <th class="tabela_header" width="70px;" colspan="2"><center>Detalhes</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->forma->listarconta($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->forma->listarconta($_GET)->orderby('c.empresa_id, e.nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) { 
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
//                             $dados = $this->forma->listar2($item->empresa_id);
//                             echo'<pre>';
//                             var_dump($dados);die;
//                             foreach ($dados as $dado) {
                            ?> 
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->descricao; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->agencia; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->conta; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->empresa; ?></td>

                                <?
                                $perfil_id = $this->session->userdata('perfil_id');
                                ?>
                                <? if ($perfil_id != 10) { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" href="<?= base_url() ?>cadastros/forma/carregarforma/<?= $item->forma_entradas_saida_id ?>">Editar</a>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" onclick="javascript: return confirm('Deseja realmente exlcuir esse Forma?');" href="<?= base_url() ?>cadastros/forma/excluir/<?= $item->forma_entradas_saida_id ?>">Excluir</a>
                                    </td>
                                    
                                    <?}else{?>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" href="#">Editar</a>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                        <a class="btn btn-outline-default btn-round btn-sm" href="#">Excluir</a>
                                    </td>
                                    
                                <?}?>
                                </tr>

                            </tbody>
                            <?php
//                            }
                        }
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="7">
                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                                Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

<!--<link href="<?= base_url() ?>bootstrap/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>bootstrap/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>bootstrap/vendor/datatables/js/jquery.dataTables.min.js"></script> 
<script src="<?= base_url() ?>bootstrap/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script> 
<script src="<?= base_url() ?>bootstrap/vendor/datatables-responsive/dataTables.responsive.js"></script> -->
<link href="<?= base_url() ?>css/paciente-lista.css" rel="stylesheet"/>
<div id="page-wrapper"> 
    <!--Inicio da DIV PAGE WRAPPER--> 

    <div class="row">
        <div class="col-sm-12">
<!--            <a class="btn btn-outline btn-info" href="<?php echo base_url() ?>cadastros/pacientes/novo">
                <i class="fa fa-plus fa-w"></i> Adicionar
            </a>-->
            <div class="panel panel-default">
                <!--<div class="panel-body">-->

                <div class="table-responsive" id="pesquisar">
                    <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisar">
                        <table class="table" >
                                <tr class="info">
                                    <th>Prontuário</th>
                                    <th>Nome / Nome da Mae / Telefone / CPF</th>
                                    <th>Data de Nasc</th>
                                    <th style="text-align: center;">Ações</th>
                                </tr> 
                            <div class="input-group input-group-sm mb-3">
                                <tr class="inputs">
                                    <td>
                                        <input type="text" name="prontuario" class="form-control input-sm" value="<?php echo @$_GET['prontuario']; ?>" />
                                    </td>
                                    <td><input type="text" name="nome" class="form-control" value="<?php echo @$_GET['nome']; ?>" /></td>
                                    <td><input type="text" name="nascimento" id="txtNascimento" class="form-control" alt="date" value="<?php echo @$_GET['nascimento']; ?>" /></td>
                                    <td style="text-align: center;"><button type="submit" class="btn btn-default" name="enviar"><i class="fa fa-search fa-1x"></i></button></td>
                                </tr> 
                            </div>
                        </table>
                    </form>


                </div>
                <div class="panel-body">
                    <a class="btn btn-outline-success my-2 my-sm-0 btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/novo">
                        <i class="fa fa-plus fa-w"></i> Novo Cadastro
                    </a>
                    <div class="table-responsive">
                        <table  class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Prontuário</th>
                                    <th>Paciente</th>
                                    <th>Nome da Mãe</th>
                                    <th>Nascimento</th>
                                    <th>Telefone</th>
                                    <th style="text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
    <!--                            <tr >
                                    <td>Trident</td>
                                    <td>Internet Explorer 4.0</td>
                                    <td>Win 95+</td>
                                    <td class="center">4</td>
                                    <td class="center">X</td>
                                </tr>-->
                                <?php
                                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                                $consulta = $this->paciente->listar($_GET);
                                $total = $consulta->count_all_results();
                                $limit = 10;
                                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                                if ($total > 0) {
                                    ?>
                                                                <!--<tbody>-->
                                    <?php
                                    $lista = $this->paciente->listar($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
//                                echo '<pre>';
//                                var_dump($lista); die;
                                    $estilo_linha = "tabela_content01";
                                    foreach ($lista as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                        if ($item->celular == "") {
                                            $telefone = $item->telefone;
                                        } else {
                                            $telefone = $item->celular;
                                        }
                                        ?>


                                        <tr>
                                            <td class="text-center"><small><?php echo $item->paciente_id; ?></small></td>
                                            <td><small><?php echo $item->nome; ?></small></td>
                                            <td><small><?php echo $item->nome_mae; ?></small></td>
                                            <td ><small><?php echo substr($item->nascimento, 8, 2) . '/' . substr($item->nascimento, 5, 2) . '/' . substr($item->nascimento, 0, 4); ?></small></td>
                                            <td ><small><?php echo $telefone; ?></small></td>
                                            <td style="width: 110pt;" class="tabela_acoes">
                                                <a class="btn btn-outline-primary btn-round btn-sm" href="<?= base_url() ?>cadastros/pacientes/carregar/<?= $item->paciente_id ?>">
                                                    Editar
                                                </a> 

                                                <a class="btn btn-outline btn-success btn-sm" href="<?= base_url() ?>emergencia/filaacolhimento/novo/<?= $item->paciente_id ?>">
                                                    Opções
                                                </a>
                                                <!--                                            <button type="button" class="btn btn-outline btn-primary btn-sm">Default</button>
                                                                                            <button type="button" class="btn btn-outline btn-primary btn-sm">Default</button>-->

                                            </td>
                                        </tr>
                                        <!--</tbody>-->
                                        <?php
                                    }
                                }
                                ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="tabela_footer  btn-info" colspan="9">
                                        <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>

                                    </th>
                                </tr>
                                <tr>
                                    <th class="tabela_footer btn-outline btn-info" colspan="9">

                                        Total de registros: <?php echo $total; ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- /.table-responsive -->
                    </div>
                </div>

                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>

</div>
</div>


</div> 


<script>

//    $("#data").mask("99/99/9999");
    
    $("#data").maskMoney();



</script>
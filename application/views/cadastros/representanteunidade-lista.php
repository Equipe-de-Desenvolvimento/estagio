<?
 $empresa_id = $this->session->userdata('empresa_id');
 $perfil_id = $this->session->userdata('perfil_id');
 $operador_id = $this->session->userdata('operador_id');
 $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
?>
<div class="col-sm-12">
     <div class="">
            <div class="" id="pesquisar">
    <form action="<?=base_url()?>cadastros/pacientes/representanteunidade" method="GET">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Nome</label>
                <input type="text" name="nome" value="<?=@$_GET['nome']?>" class="form-control">
            </div>
            <div class="col-lg-3">
                <br>
                <button type="submit" class="btn btn-outline-success btn-sm"> Pesquisar </button>
            </div>
        </div>
    </form>

    <br>
    <div>
        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/cadastrorepresentanteunidade/0">
            <i></i> Novo Representante da Unidade
        </a>
    </div>
    <br> 

     <div class="table-responsive">
                <table  class="table table-bordered table-hover" id="dataTables-example">
            
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Instituição de Origem</th>
                    <th>Curso</th>
                    <th colspan="2" align="center">Ações</th>
                </tr> 
    <tbody>
                <?
                    $url = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->paciente->listarrepresentanteunidade($_GET);
                    $total = $consulta->count_all_results();
                
                    $limit = 10;
                    isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
             
                    <?
                    $lista = $this->paciente->listarrepresentanteunidade($_GET)->limit($limit, $pagina)->orderby("nome, cargo")->get()->result();
                   
                    foreach ($lista as $item) {
                    ?>
                            <tr>
                                <td><?=$item->nome; ?></td>
                                <td><?=$item->email; ?></td>
                                <td><?=$item->cargo; ?></td>
                                <td><?=$item->intituicao; ?></td>
                                <td><?=$item->origem; ?></td>
                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/cadastrorepresentanteunidade/<?=$item->representante_unidade_id?>">Editar</a>
                                <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/excluirrepresentanteunidade/<?=$item->representante_unidade_id?>">Excluir</a></td>
                            </tr>
                    <?
                        }
                    ?>

                 
                    <?}?>
            </tbody>
            <tfoot>
                    <tr>
                    <th colspan="6">
                        <div class="pagination">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de Registros: <?= $total; ?>
                        </div>
                    </th>
                </tr> 
            </tfoot> 
        </table>
     </div>
</div>
    </div>
</div>

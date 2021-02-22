<?
 $empresa_id = $this->session->userdata('empresa_id');
 $perfil_id = $this->session->userdata('perfil_id');
 $operador_id = $this->session->userdata('operador_id');
 $empresapermissoes = $this->guia->listarempresapermissoes($empresa_id);
?>
<div class="panel-body">
    <form action="">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Sem campos</label>
                <input type="text" name="sem" class="form-control">
            </div>
        </div>
    </form>

    <br>
    <div>
        <a class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/pacientes/cadastrodeinfomacaovagas/0">
            <i></i> Nova Descrição
        </a>
    </div>
    <br>


     <div class="table-responsive">
        <table  class="table table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th colspan="2" align="center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?
                    $url = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->paciente->listarinformacaovaga($_GET);
                    $total = $consulta->count_all_results();
                
                    $limit = 10;
                    isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?
                    $lista = $this->paciente->listarinformacaovaga($_GET)->limit($limit, $pagina)->orderby("descricao, tipo")->get()->result();
                        foreach ($lista as $item) {
                    ?>
                            <tr>
                                <td><?=$item->descricao?></td>
                                <td><?=$item->tipo?></td>
                                <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/cadastrodeinfomacaovagas/<?=$item->informacaovaga_id?>">Editar</a>
                                <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/excluirinfomacaovagas/<?=$item->informacaovaga_id?>">Excluir</a></td>
                            </tr>
                    <?
                        }
                    ?>

                </tbody>
                    <?}?>
            </tbody>
            <tfoot>
                    <tr>
                    <th colspan="4">
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
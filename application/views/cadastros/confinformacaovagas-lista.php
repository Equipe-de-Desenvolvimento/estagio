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
                <label for="">Descrição</label>
                <input type="text" name="nome" class="form-control" value="<?= @$_GET['nome']?>">
            </div>
            <div class="col-lg-3">
                <label for="">Tipo</label>
               <select name="tipo" class="form-control"  >
                    <option value="">Selecione</option>
                    <option value="escolaridade" <?=(@$_GET['tipo'] == 'escolaridade')?'selected': ''?>>Nível de Formação</option>
                    <option value="curso" <?=(@$_GET['tipo'] == 'curso')?'selected': ''?>>Curso</option>
                    <option value="disciplina" <?=(@$_GET['tipo'] == 'disciplina')?'selected': ''?>> Disciplina</option>
                    <option value="setor" <?=(@$_GET['tipo'] == 'setor')?'selected': ''?> >Setor</option>
                    <option value="periodo" <?=(@$_GET['tipo'] == 'periodo')?'selected': ''?>>Periodo</option>
                    <option value="periodicidade" <?=(@$_GET['tipo'] == 'periodicidade')?'selected': ''?>>Periodicidade</option>
                    <option value="tipovaga" <?=(@$_GET['tipo'] == 'tipovaga')?'selected': ''?>>Tipo Vaga</option>
                </select>
            </div>
              <div class="col-lg-2">
                  <label for="">&nbsp;</label>
                <button type="submit"  class="form-control">Pesquisar</button>
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
                                <td><? 
                                    switch ($item->tipo){
                                        case "escolaridade":
                                            echo "Nível de Formação";
                                            break; 
                                        case "curso":
                                            echo "Curso";
                                            break; 
                                        case "disciplina":
                                            echo "Disciplina";
                                            break; 
                                        case "setor":
                                            echo "Setor";
                                            break; 
                                        case "periodo":
                                            echo "Periodo";
                                            break; 
                                        case "periodicidade":
                                            echo "Periodicidade";
                                            break; 
                                        case "tipovaga":
                                            echo "Tipo Vaga";
                                            break; 
                                        default:
                                           echo "";
                                    }  
                                ?></td>
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
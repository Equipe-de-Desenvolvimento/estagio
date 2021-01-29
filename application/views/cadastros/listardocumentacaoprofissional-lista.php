<div class="panel-body">
    <div class="alert alert-primary"><b>Documentação Estagiarios</b></div>
    
    <a class="btn btn-outline-default btn-round btn-sm" href="<?=base_url()?>cadastros/pacientes/novadocumentacao/0" type="submit">Nova Documentação</a>

    <br>

    <form action="">
    
    </form>

    <br>

    <table class="table table-bordered table-hover">
        <tr>
            <th>Tipo</th>
            <th colspan="2" align="center">Ações</th>
        </tr>

        <?php
            $url = $this->utilitario->build_query_params(current_url(), $_GET);
            $consulta = $this->paciente->listardocumentacaoestagio($_GET);
            $total = $consulta->count_all_results();

            $limit = 10;
            isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
        ?>
        <tbody>
            <? $lista = $this->paciente->listardocumentacaoestagio($_GET)->limit($limit, $pagina)->orderby("nome")->get()->result(); 
                foreach($lista as $item){
                    ?>
                        <tr>
                            <td><?=$item->nome?></td>
                            <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/novadocumentacao/<?=$item->documentacao_profissional_id?>">Editar</a></td>
                            <td> <a class="btn btn-outline-default btn-sm" href="<?=base_url()?>cadastros/pacientes/excluirdocumentacaoestagio/<?=$item->documentacao_profissional_id?>">Excluir</a></td>
                        </tr>

                    <?
                }
            ?>
        </tbody>

    </table>
</div>
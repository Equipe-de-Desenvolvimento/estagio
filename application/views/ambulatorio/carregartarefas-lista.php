<?
$perfil_id = $this->session->userdata('perfil_id');
?>
<div class="content  " > <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/exametemp/carregartarefa/<?= $paciente_id ?>">
            Nova Tarefa
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Tarefas</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>ambulatorio/exametemp/listartarefas/<?= $paciente_id ?>">
                                <tr>
                                    <th class="tabela_title">Nome</th>
                                    <th class="tabela_title">Data</th>
                                    <th class="tabela_title">Médico</th>
                                    <th class="tabela_title">Status</th>
                                    <th class="tabela_title"> </th>
                                </tr>
                                <tr>
                                    <th class="tabela_title">
                                        <input type="text" name="nome" value="<?php echo @$_GET['nome']; ?>" />
                                    </th>
                                    <th class="tabela_title">
                                        <input type="text"  id="data" alt="date" name="data" class="size1"  value="<?php echo @$_GET['data']; ?>" />
                                    </th>
                                    <th class="tabela_title">

                                        <select name="medico">
                                            <option value=""> Selecione</option>
                                            <?php
                                            foreach ($medicos as $item) {
                                                ?>
                                                <option value="<?= $item->operador_id; ?>" <?php (@$_GET['medico'] == $item->operador_id) ? "Selected" : "" ?> ><?= $item->nome; ?></option>
                                                <?
                                            }
                                            ?>

                                        </select>

                                    </th>
                                    <th class="tabela_title">
                                        <select name="status">
                                            <option value="">Selecione</option>
                                            <option value="ATENDENDO" <?php (@$_GET['status'] == "ATENDENDO") ? "Selected" : "" ?>>ATENDENDO</option>
                                            <option value="AGUARDANDO" <?php (@$_GET['status'] == "AGUARDANDO") ? "Selected" : "" ?>>AGUARDANDO</option>
                                            <option value="FINALIZADO" <?php (@$_GET['status'] == "FINALIZADO") ? "Selected" : "" ?>>FINALIZADO</option>
                                        </select>
                                    </th> 

                                    <th class="tabela_title">
                                        <button type="submit" id="enviar">Pesquisar</button>
                                    </th>
                                </tr>
                            </form>
            </table>
            <table>
                <tr>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Médico Responsável </th>
                    <th class="tabela_header">Data</th>
                    <th class="tabela_header">Status</th>
                    <th class="tabela_header" colspan="3"  style="text-align: center;">Ações</th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exametemp->listartarefascadastro($paciente_id);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->exametemp->listartarefascadastro($paciente_id)->orderby('tm.ordenador')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            if ($item->status == "ATENDENDO") {
                                @$cor = "green";
                            } elseif ($item->status == "AGUARDANDO") {
                                @$cor = "red";
                            } elseif ($item->status == "FINALIZADO") {
                                @$cor = "blue";
                            } else {
                                @$cor = "";
                            }
                            
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"> <?= @$item->nome_tarefa; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= @$item->medico_responsavel; ?> </td>
                                <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime(@$item->data)); ?> </td>
                                <td class="<?php echo $estilo_linha; ?>"> <b style="color:<?= $cor; ?>;"><?= @$item->status; ?> </b></td>                                 
                                <td class="<?php echo $estilo_linha; ?>">
                                    <div class="bt_link">
                                    <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exametemp/visualizartarefarecep/<?= @$item->tarefa_medico_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=500,height=400');">Visualizar</a>
                                    </div> 
                                </td>
                               <?php
                                if (@$this->session->userdata('perfil_id') == 1 && $item->status == "FINALIZADO") {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>"> 
                                       <div class="bt_link">
                                           <a href="<?= base_url() ?>ambulatorio/exametemp/excluirtarefa/<?= @$item->tarefa_medico_id; ?>/<?= @$item->paciente_id; ?>" onclick="return confirm('Deseja realmente excluir?');" target="_blank"> Excluir</a>
                                       </div>
                                    </td>
                                <?php }elseif($item->status == "FINALIZADO"){
                                    ?>
                                     <td class="<?php echo $estilo_linha; ?>"> 
                                       
                                    </td>
                                    <?
                                }else{
                                    ?>
                                     <td class="<?php echo $estilo_linha; ?>"> 
                                       <div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exametemp/excluirtarefa/<?= @$item->tarefa_medico_id; ?>/<?= @$item->paciente_id; ?>" onclick="return confirm('Deseja realmente excluir?');" target="_blank"> Excluir</a>
                                       </div>
                                    </td>
                                    <?
                                } ?>

                            </tr>
                        <? } ?>

                    </tbody>
                <? } ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="10">
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
    $(function () {
        $("#data").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

</script>
<style>
    #spantotal{

        color: black;
        font-weight: bolder;
        font-size: 18px;
    }
    #textovalortotal{
        text-align: right;
    }
    #tot td{
        background-color: #bdc3c7;
    }

    #form_solicitacaoitens div{
        margin: 3pt;
    }
</style>
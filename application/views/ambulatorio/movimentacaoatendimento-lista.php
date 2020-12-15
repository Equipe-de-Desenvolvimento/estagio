<?
 $grupos = $this->procedimento->listargrupos();
 $convenio = $this->convenio->listardados();
 $operadores = $this->operador_m->listaroperadores();
// echo '<pre>';
//  print_r($_GET);
?>
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Movimentações de Atendimentos</a></h3>
        <div>
        <table>
        <form method="get" action="<?= base_url() ?>ambulatorio/guia/movimentacaoatendimento">
                    <tr>
                        <th class="tabela_title">Paciente</th>
                        <th class="tabela_title">ID</th>
                        <th class="tabela_title">Status</th>
                        <th class="tabela_title">Especialidade</th>
                    </tr>

                    <tr>
                        <td>
                                <input type="text" name="nome" class="size8 bestupper" value="<?php echo @$_GET['nome']; ?>" />
                        </td>

                        <td>
                                <input type="text" name="agenda_exames_id"  class="size1" value="<?php echo @$_GET['agenda_exames_id']; ?>" />
                        </td>
                        
                        <td>
                            <select name="status">
                                <option value="0" <?if(@$_GET['status'] == '0') echo 'selected'?>>TODOS</option>
                                <option value="1" <?if(@$_GET['status'] == '1') echo 'selected'?>>INSERIDO</option>
                                <option value="2" <?if(@$_GET['status'] == '2') echo 'selected'?>>ALTERADO</option>
                                <option value="3" <?if(@$_GET['status'] == '3') echo 'selected'?>>CANCELADO</option>
                            </select>
                        </td>

                        <td>
                            <select name="grupos">
                                <option value="">TODOS</option>
                                <?foreach ($grupos as $value){ ?>
                                    <option value="<?=$value->nome?>" <?if(@$_GET['grupos'] == $value->nome) echo 'selected'?>><?=$value->nome?></option>
                                <?}?>
                            </select>
                        </td>

                        </tr>

                        <tr>
                        <th class="tabela_title">Convenio</th>
                        <th class="tabela_title">Data Inicial</th>
                        <th class="tabela_title">Data Final</th>
                        </tr>   
                        
                        <tr>
                        <td>
                            <select name="convenio">
                                <option value="">TODOS</option>
                                <?foreach ($convenio as $value){ ?>
                                    <option value="<?=$value->convenio_id?>" <?if(@$_GET['convenio'] == $value->convenio_id) echo 'selected'?>><?=$value->nome?></option>
                                <?}?>
                            </select>
                        </td>

                        <td>
                                <input type="text" name="data_inicial" id="data_inicial" class="size1" value="<?php echo @$_GET['data_inicial']; ?>" />
                        </td>
                        <td>
                                <input type="text" name="data_final" id="data_final" class="size1" value="<?php echo @$_GET['data_final']; ?>" />
                        </td>

                        <td>
                        <button type="submit" id="enviar">Pesquisar</button>
                        </td>
                    </tr>
                </form>
        </table>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header">ID</th>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Data</th>
                        <th class="tabela_header">Status</th>
                        <th class="tabela_header">Medico</th>
                        <th class="tabela_header">Procedimento</th>
                        <th class="tabela_header">Quantidade</th>
                        <th class="tabela_header">Convenio</th>
                        <th class="tabela_header">Valor</th>
                        <th class="tabela_header">Especialidade</th>
                        <th class="tabela_header">Operador</th>
                    </tr>
                </thead>
                <?php
                 $perfil_id = $this->session->userdata('perfil_id');
                    $url      = $this->utilitario->build_query_params(current_url(), $_GET);
                    $consulta = $this->guia->listarmovintacaoatendimento($_GET);
                    $total    = $consulta->count_all_results();
                    $limit    = 10;
                    isset ($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                    if ($total > 0) {
                ?>
                <tbody>
                    <?php
                        $lista = $this->guia->listarmovintacaoatendimento($_GET)->limit($limit, $pagina)->orderby("ma.data_cadastro, paciente")->get()->result();
                        $estilo_linha = "tabela_content01";
                        // echo '<pre>';
                        // print_r($lista);
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        if($item->status == 'ALTERADO'){
                            $cor = 'blue';
                        }else if($item->status == 'CANCELADO'){
                            $cor = 'red';
                        }else{
                            $cor = '';
                        }

                        $data = date("d/m/Y H:i:s", strtotime(str_replace('-', '/', $item->data_cadastro)));
                        ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->agenda_exames_id; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->paciente; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $data; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><b><?= $item->status; ?></b><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->medico; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->procedimento; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->quantidade; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->convenio; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->valor; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->especialidade; ?><font></td>
                            <td class="<?php echo $estilo_linha; ?>"><font color='<?=$cor?>'><?= $item->operador; ?><font></td>
                        </tr>
                        </tbody>
                        <?php
                                }
                            }
                        ?>
                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="20">
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

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(function () {
        $("#data_inicial").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function () {
        $("#data_final").datepicker({
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

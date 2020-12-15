
<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Tela de Autorização</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_title">Nome</th> 
                        <th class="tabela_title">Data inicio</th> 
                        <th class="tabela_title">Data fim</th> 
                        <th class="tabela_title">Situação</th> 
                    </tr>
                    <tr>

                <form method="get" action="<?= base_url() ?>ambulatorio/guia/listarsolicitacoesmedico">

                    <th colspan="1" class="tabela_title">
                        <input type="text" name="nome" class="texto04 bestupper" value="<?php echo @$_GET['nome']; ?>" />

                    </th>

                    <th colspan="1" class="tabela_title">
                        <input type="text"  id="datainicio" alt="date" name="datainicio" class="size1"  value="<?php echo @$_GET['datainicio']; ?>" />

                    </th>

                    <th colspan="1" class="tabela_title">
                        <input type="text"  id="datafim" alt="date" name="datafim" class="size1"  value="<?php echo @$_GET['datafim']; ?>" />

                    </th>
                    <th colspan="1" class="tabela_title">
                        <select name="situacao">
                            <option value="">Selecione</option>
                            <option value="1">Emergência</option>
                            <option value="2">Eletiva</option>
                        </select>
                    </th>

                    <th class="tabela_title">
                        <button type="submit" id="enviar">Pesquisar</button>
                    </th>
                </form>
                </th>
                </tr>
                <tr>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header"> Procedimento</center></th>
                    <th class="tabela_header"> Data </th>
                    <th class="tabela_header"> Justificativa </th>
                    <th class="tabela_header"> Médico Solicitante </th>
                    <th class="tabela_header" colspan="4"><center>Ações</center></th>
                </tr>
                </thead>

                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $teste = $this->guia->listarexamesguisadt($_GET)->get()->result();
                $total = count($teste);
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;


                $lista = $this->guia->listarexamesguisadt($_GET)->limit($limit, $pagina)->get()->result();


                if ($total > 0) {
                    ?>

                    <tbody>
                        <?php
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            if (@$item->emergencia == "t" && @$this->session->userdata('perfil_id') != "1") {
                                continue;
                            }
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                                <!--<form method="post" action="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadtemergencia" target="_blank">-->
                        <input type="hidden" value="<?= @$item->solicitacao_sadt_id ?>" name="solicitacao_sadt_id">
                        <input type="hidden" value="<?= @$item->paciente_id ?>" name="paciente_id">
                        <input type="hidden" value="<?= @$item->convenio_id ?>" name="convenio_id">
                        <input type="hidden" value="<?= $item->solicitacao_sadt_procedimento_id; ?>" name="solicitacao_sadt_procedimento_id">
                        <tr>
                            <td class="<?php echo @$estilo_linha; ?>"><a  href="<?= base_url() ?>cadastros/pacientes/carregar/<?= $item->paciente_id; ?>" style="text-decoration: none;" target="_blank"><?= $item->paciente ?></a></td>


                            <td class="<?php echo @$estilo_linha; ?>"><?
                                if ($item->procedimento != "") {
                                    echo $item->procedimento;
                                } else {
                                    echo $item->procedimento_escrito;
                                }
                                ?></td>


                            <td class="<?php echo @$estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_solicitacao)) ?></td>
                            <td class="<?php echo @$estilo_linha; ?>"><?= @$item->justificativa ?></td>
                            <td class="<?php echo @$estilo_linha; ?>"><?= @$item->operador_cadastro ?></td>
                            <td class="<?php echo @$estilo_linha; ?>">
                                <select name="direcao"  onchange="window.open(this.value, '_blank');"  >                             
                                    <!--<option value="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadtemergencia/nao">Selecione</option>-->
                               <!--<option value="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadtemergencia/<?= @$item->solicitacao_sadt_id ?>/<?= @$item->paciente_id ?>/<?= @$item->convenio_id ?>/<?= @$item->solicitacao_sadt_id ?>/externo/<?= $item->solicitacao_sadt_procedimento_id; ?>">Emergência</option>-->


                                    <option value="<?= base_url() ?>ambulatorio/guia/atualizarprocedimentosadt/<?= $item->solicitacao_sadt_procedimento_id; ?>/emergencia" <?php
                                    if (@$item->emergencia == "t") {
                                        echo "selected";
                                    }
                                    ?> >Emergência  </option>



                                    <option value="<?= base_url() ?>ambulatorio/guia/atualizarprocedimentosadt/<?= $item->solicitacao_sadt_procedimento_id; ?>/eletivo" <?php
                                    if (@$item->eletivo == "t") {
                                        echo "selected";
                                    }
                                    ?>>Eletivo </option>


                                </select>
                            </td>

                            <?php
                            if ($item->emergencia == "t") {
                                ?>
                                <td class="<?php echo @$estilo_linha; ?>">  <a  onclick="javascript: return confirm('Deseja realmente aprovar esse Procedimento?');" href="<?= base_url() ?>ambulatorio/guia/gravaratendimentosadtemergencia/<?= @$item->solicitacao_sadt_id ?>/<?= @$item->paciente_id ?>/<?= @$item->convenio_id ?>/<?= @$item->solicitacao_sadt_id ?>/externo/<?= $item->solicitacao_sadt_procedimento_id; ?>" target="_blank"><button type="submit" >APROVAR</button></a>  </td>

                                <?
                            }
                            ?>

                            <?php
                            if ($item->eletivo == "t") {
                                ?>
                                <td class="<?php echo @$estilo_linha; ?>"> <a  href="<?= base_url() ?>ambulatorio/exametemp/carregarpacientetempgeralsadt/<?= @$item->paciente_id ?>/<?= @$item->solicitacao_sadt_id ?>/<?= $item->solicitacao_sadt_procedimento_id; ?>" target="_blank"><button type="submit" >AGENDAR</button></a>  </td>
                            <?php } ?>

                                <!--<td class="<?php echo @$estilo_linha; ?>">--> 

                            <td class="<?php echo @$estilo_linha; ?>"><a onclick="javascript: return confirm('Deseja realmente Excluir esse Procedimento?');"  href="<?= base_url() ?>ambulatorio/guia/excluirsolicitacaoprocedimentodirecao/<?= $item->solicitacao_sadt_procedimento_id; ?>" class="delete" target="_blank">
                                </a></td>

                        </tr>
                        <!--</form>-->
                        </tbody>

                        <?
                    }
                }
                ?>


                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="8">
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
        $("#datainicio").datepicker({
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
        $("#datafim").datepicker({
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

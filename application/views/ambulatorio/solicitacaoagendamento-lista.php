    
<div class="content"> <!-- Inicio da DIV content -->
                
                <td width="20px;">
                    <div class="bt_link" style="width: 110pt">
                        <a href="<?php echo base_url() ?>ambulatorio/empresa/novasolicitacao/0" style="width: 100pt">
                            Nova Solicitação
                        </a>
                    </div>
                </td>
    <div id="accordion">
        <h3 class="singular"><a href="#">Solicitacação de Agendamento</a></h3>
        <div>
            <form method="get" action="<?= base_url() ?>ambulatorio/empresa/solicitacaoagendamento">
            <table>
                <thead>
                    <tr>

                            <th class="tabela_title">Data</th>
                            <th class="tabela_title">Medico</th>
                            <th class="tabela_title">Paciente</th>

                    </tr>

                    <tr>
                    <td><input type="text" name="data_inicio" id="data_inicio" class="texto" value="<?php echo @$_GET['data_inicio']; ?>" /></td>
                            <td>
                                    <select name="operador">
                                    <option value="">Selecione</option>
                                    <? foreach ($medicos as $operador){?>
                                        <option value="<?=$operador->operador_id?>"
                                        <?if(@$_GET['operador'] == $operador->operador_id) echo 'selected'?>> <?=$operador->nome?> </option>
                                    <? } ?>
                                    </select>
                            </td>
                            <td> <input type="text" name="paciente" id="paciente" class="texto08" value="<?=@$_GET['paciente']?>"></td>

                            <td><button type="submit" id="enviar">Pesquisar</button></td>
                    </tr>

                </form>

            
           
                    <tr>
    <table>
                        
                    </div>  
                        <th class="tabela_header">Prontuário</th>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Data</th>
                        <th class="tabela_header">Turno</th>
                        <th class="tabela_header">Convênio</th>
                        <th class="tabela_header">Procedimento</th>
                        <th class="tabela_header">Médico</th>
                        <th class="tabela_header">Observação</th>
                        <th class="tabela_header" colspan="5"><center>Detalhes</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->empresa->listarsolicitacaoagendamento($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->empresa->listarsolicitacaoagendamento($_GET)->limit($limit, $pagina)->orderby("pp.data_cadastro")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->paciente_id;?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->paciente; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data)); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?
                                if($item->turno == 'manha'){
                                echo 'Manhã';
                                
                                }elseif ($item->turno == 'tarde') {
                                    echo 'Tarde';
                                }elseif ($item->turno == 'noite') {
                                    echo 'Noite';
                                } else {
                                    echo '';
                                }
                                 ?></td> 
                                
                                <?if($item->convenio != ''){?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                <?}else{?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio_text; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento_text; ?></td>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                <?}?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= wordwrap($item->observacao, 20, "<br />\n", true); ;?></td>
                               
                                
                                <td style="width:40px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a  href="<?= base_url() ?>ambulatorio/empresa/novasolicitacao/<?= $item->paciente_solicitar_agendamento_id; ?>">Editar</a></div>
                                </td>
                                <td style="width:40px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a onclick="javascript: return confirm('Deseja confirmar a solicitação de agendamento?')" href="<?= base_url() ?>ambulatorio/empresa/confirmarsolicitacaoagendamento/<?= $item->paciente_solicitar_agendamento_id; ?>">Confirmar</a></div>
                                </td>
                                <td style="width:40px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a onclick="javascript: return confirm('Deseja remover a solicitação de agendamento?')" href="<?= base_url() ?>ambulatorio/empresa/excluirsolicitacaoagendamento/<?= $item->paciente_solicitar_agendamento_id; ?>">Excluir</a></div>
                                </td>
                                
                                <?
                                $perfil_id = $this->session->userdata('perfil_id');
                                $operador_id = $this->session->userdata('operador_id');
                                if ($perfil_id == 1):
                                    ?>
                                    
                               <? endif; ?>
                                <?
//                                $perfil_id = $this->session->userdata('perfil_id');
                                if ($operador_id == 1):
                                    ?>
                                    
                               <? endif; ?>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="11">
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
        $("#data_inicio").datepicker({
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

<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Lista de Cirurgias</a></h3>
        <div>
            <table style="margin-bottom: 5pt;">
                <thead>
                <form method="get" action="<?php echo base_url() ?>centrocirurgico/centrocirurgico/pesquisarcirurgia">   
                    <tr>
                        <th class="tabela_title"><label for="nome">Nome</label></th>
                        <th class="tabela_title"><label for="txtdata_solicitacao">Data solicitação</label></th>
                        <th class="tabela_title"><label for="txtdata_cirurgia">Data previsão</label></th>
                        <th class="tabela_title"><label for="txtdata_realizacao">Data realização</label></th>
                        <th class="tabela_title"><label for="situacao">Situação</label></th>
                        <th class="tabela_title"></th>
                    </tr>
                    <tr>
                        <th><input type="text" name="nome" id="nome" value="<?= @$_GET['nome']; ?>"/></th>
                        <th><input type="text" name="txtdata_solicitacao" id="txtdata_solicitacao" alt="date" value="<?= @$_GET['txtdata_solicitacao']; ?>"/></th>
                        <th><input type="text" name="txtdata_cirurgia" id="txtdata_cirurgia" alt="date" value="<?= @$_GET['txtdata_cirurgia']; ?>"/></th>
                        <th><input type="text" name="txtdata_realizacao" id="txtdata_realizacao" alt="date" value="<?= @$_GET['txtdata_realizacao']; ?>"/></th>
                        <th><select name="situacao" id="situacao" >
                                <option value="">Selecione</option>
                                <option value="faturamento" <?= (@$_GET['situacao'] == "faturamento" ) ? "selected": ""?> >Faturamento</option>
                                <option value="aguardando"  <?= (@$_GET['situacao'] == "aguardando" ) ? "selected": ""?>>Aguardando</option>
                                <option value="realizada"  <?= (@$_GET['situacao'] == "realizada" ) ? "selected": ""?>>Realizada</option>
                            </select></th>
                        <th><button type="submit" name="enviar">Pesquisar</button></th>
                    </tr>
                </form>
                </thead>
            </table>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Situação</th>
                        <th class="tabela_header">Data Solicitação</th>
                        <th class="tabela_header">Data Prevista</th>
                        <th class="tabela_header">Data Realização</th>
                        <th style="text-align: center;" colspan="7" class="tabela_header">Detalhes</th> 
                    </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->centrocirurgico_m->listarcirurgia($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->centrocirurgico_m->listarcirurgia($_GET)->orderby('p.nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                         
                        foreach ($lista as $item) {
                            $recibo = false;
                            $situacao = '';
                            if ($item->situacao == 'FATURAMENTO_PENDENTE') {
                                $situacao = "<span style='color:red'>Faturamento</span>";
                            } elseif ($item->situacao == 'AGUARDANDO') {
                                $situacao = "<span style='color:#ff8400'>Aguardando</span>";
                                $recibo = true;
                            } elseif ($item->situacao == 'EQUIPE_FATURADA') {
                                $situacao = "<span style='color:green'>Aguardando</span>";
                            } elseif ($item->situacao == 'REALIZADA') {
                                $situacao = "<span style='color:green'>Realizada</span>";
                                $recibo = true;
                            }
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td  class="<?php echo $estilo_linha; ?>"><?php echo $item->nome; ?></td>                              
                                <td class="<?php echo $estilo_linha; ?>"><?php echo $situacao; ?></td>                              
                                <td class="<?php echo $estilo_linha; ?>"><?php echo date("d/m/Y H:i:s", strtotime($item->data_cadastro)); ?></td>                              
                                <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime($item->data_prevista)) ?> </td>
                                <td class="<?php echo $estilo_linha; ?>"><?  
                                        if($item->situacao == 'REALIZADA' && $item->data_atualizacao != "") {
                                            echo   date('d/m/Y H:i:s', strtotime($item->data_atualizacao)) ;
                                        }  
                                        ?> </td>
                                        <?php 
                                            if ($recibo) {
                                               ?>
                                     <td class="<?php echo $estilo_linha; ?>" width="135px;"><div  class="bt_link">
                                            <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/recibocirurgia/<?= $item->solicitacao_cirurgia_id; ?>" target="_blank">Recibo</a></div>
                                     </td>
                                        <?
                                            }
                                        ?>
                                    <? if ($item->situacao != 'REALIZADA') { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="135px;"><div  class="bt_link">
                                            <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/listarfichaanestesia/<?= $item->solicitacao_cirurgia_id; ?>/<?= $item->guia_id; ?>" target="_blank">FICHA ANESTESIA</a></div>
                                    </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;"><div  class="bt_link">
                                             <a onclick="javascript:window.open('<?= base_url(); ?>centrocirurgico/centrocirurgico/faturarprocedimentos/<?= $item->solicitacao_cirurgia_id; ?>/<?= $item->guia_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');">Faturar</a></div>
                                    </td>  
                                    <td class="<?php echo $estilo_linha; ?>" style="width: 100px;"><div style="width: 100px;" class="bt_link">
                                             <a onclick="javascript:window.open('<?= base_url(); ?>centrocirurgico/centrocirurgico/faturarequipe/<?= $item->solicitacao_cirurgia_id; ?>/<?= $item->guia_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');">Faturar Eq.</a></div>
                                    </td>
                                    <? if ($item->situacao == 'AGUARDANDO') { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="30px;"><div class="bt_link"> 
                                             <a  onclick="javascript: if(confirm('Deseja confirmar a execução da cirurgia?  ')){ window.open('<?= base_url(); ?>centrocirurgico/centrocirurgico/confirmarcirurgia/<?= $item->solicitacao_cirurgia_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600')   };"  >Confirmar</a></div>
                                        </td>   
                                    <? } else { ?>
                                        <td class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                                <!--<a  onclick="javascript: return confirm('Deseja realmente excluir o convenio?\n\nObs: Irá excluir também os procedimentos associados ao convenio  ');" href="<?= base_url() ?>centrocirurgico/centrocirurgico/confirmarcirurgia/<?= $item->solicitacao_cirurgia_id ?>">Confirmar</a></div>-->
                                        </td> 
                                    <? }
                                    ?>

                                    <td class="<?php echo $estilo_linha; ?>" width="30px;"><div class="bt_link">
                                            <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/editarcirurgia/<?= $item->solicitacao_cirurgia_id; ?>">Editar</a></div>
                                    </td>
                          
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">
                                   <?  if ($this->session->userdata('perfil_id') == 1 || $this->session->userdata('operador_id')== 1) {  ?>
                                        <div class="bt_link">  
                                           <a href="<?= base_url() ?>centrocirurgico/centrocirurgico/cirurgicoexclusao/<?= $item->solicitacao_cirurgia_id; ?>" target="_blank"  >Excluir</a></div>
                                   <?  }?>
                                    <? if($this->session->userdata('perfil_id') == 25){?>
                                         <div class="bt_link">  
                                           <a href="<?= base_url() ?>centrocirurgico/centrocirurgico/cirurgicocancelar/<?= $item->solicitacao_cirurgia_id; ?>" target="_blank"  >Cancelar</a>
                                         </div>
                                    <? } ?>    
                                        
                               </td>
                               
<!--                                    <td class="<?php echo $estilo_linha; ?>" width="30px;"><div class="bt_link">
                                            <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/impressaoorcamento/<?= $item->solicitacao_cirurgia_id; ?>">Imprimir</a></div>
                                    </td>-->
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">

                                    </td>  
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">

                                    </td>  
                                    
                             
                                   <td class="<?php echo $estilo_linha; ?>" width="30px;"> 
                                           <?  if ($this->session->userdata('perfil_id') == 1 || $this->session->userdata('operador_id')== 1) {  ?>
                                       <div class="bt_link" target="_blank">
                                           <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/cirurgicoexclusao/<?= $item->solicitacao_cirurgia_id; ?>" target="_blank" >Excluir</a>
                                       </div> 
                                       <?  }?>
                                   </td> 
                                     
                                   
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">

                                    </td>  
                                   <td class="<?php echo $estilo_linha; ?>" width="30px;"> 
                                        
                                   </td>
                                    <td class="<?php echo $estilo_linha; ?>" width="30px;">

                                    </td>  
                                   
                                <? }
                                ?>
                            </tr>
                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="12">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $("#txtdata_cirurgia").datepicker({
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
        $("#txtdata_solicitacao").datepicker({
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
        $("#txtdata_realizacao").datepicker({
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

<div class="content"> <!-- Inicio da DIV content -->
    <? 
    $empresapermissoes = $this->guia->listarempresapermissoes($this->session->userdata('empresa_id')); 
    ?>
    <table>
        <tr> 
            <td width="20px;">
                <div class="bt_link" style="width: 110pt">
                    <a href="<?php echo base_url() ?>ambulatorio/exame/carregaraparelho/0" style="width: 100pt">
                        Novo aparelho
                    </a>
                </div>
            </td>  
    </table>

    <div id="accordion">
        <h3><a href="#">Fila de aparelhos</a></h3>
        <div> 
            <table >
                <thead>
                    <tr>  
                        <!--<th class="tabela_title" >CPF</th>--> 
                        <th class="tabela_title" colspan ="3" ></th> 
                    </tr>
                    <tr>
                    <td> 
                        <form method="get" action="<?php echo base_url() ?>cadastros/pacientes/pesquisar"> 
<!--                            <input type="text" name="cpf" class="texto02" value="<?php echo @$_GET['cpf']; ?>" />-->
                            <!--<button type="submit" name="enviar">Pesquisar</button>--> 
                        </form> 
                      </td>
                  </tr>

                <tr> 
                    <th class="tabela_header" colspan="0">Paciente</th>
                    <th class="tabela_header" colspan="0">Aparelho</th>
                    <th class="tabela_header" colspan="0">Data associação</th>
                    <th class="tabela_header" colspan="0">Data troca</th>
                    <th class="tabela_header" colspan="4"><center>Ações</center></th> 
                </tr>
                
                </thead>
                <?php 

                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listaraparelhosassociados($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                       
                        <?php
                        $lista = $this->exame->listaraparelhosassociados($_GET)->orderby('p.nome')->limit($limit, $pagina)->get()->result();
                         
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            
                            ?> 
                         <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->paciente; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->aparelho; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?
                                    if ($item->data_associacao != "") {
                                        echo   date('d/m/Y H:i:s', strtotime($item->data_associacao));
                                    } 
                                ?></td>
                                <td class="<?php echo $estilo_linha; ?>"> <?
                                    if ($item->data_troca != "") {
                                        echo   date('d/m/Y H:i:s', strtotime($item->data_troca));
                                    } 
                                ?></td>
                              
                                    <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                            <a onclick="javascript: return confirm('Deseja realmente Finalizar?');" href="<?= base_url() ?>ambulatorio/exame/finalizaraparelho/<?= $item->aparelho_gasto_sala_id?> "  >
                                                <b>Finalizar</b>
                                            </a></div>
                                    </td> 
                                    
                                   <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                           <a onclick="javascript:window.open('<?= base_url()?>ambulatorio/exame/trocarparelho/<?= $item->aparelho_gasto_sala_id?>/<?= $item->fila_aparelhos_id?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');" >
                                                <b>Trocar</b>
                                            </a></div>
                                    </td> 
                                     <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                            <a onclick="javascript:window.open('<?= base_url()?>ambulatorio/exame/descricaoaparelho/<?= $item->fila_aparelhos_id?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');" >
                                                <b>Descrição</b>
                                            </a></div>
                                    </td> 
                                    <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                            <a  onclick="javascript:window.open('<?= base_url()?>ambulatorio/exame/historicoaparelhogastosala/<?= $item->aparelho_gasto_sala_id?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=700,height=600');">
                                                <b>Histórico</b>
                                            </a></div>
                                    </td> 
                                </tr>   
                        <?php
                    } 
                    ?>
                             
                         </tbody>            
                                    <?
                }
                ?>
           
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="9">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


</div>





<!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>
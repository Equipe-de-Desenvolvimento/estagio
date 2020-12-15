
<div class="content"> <!-- Inicio da DIV content -->
<!--    <table>
        <tr>
            <td style="width: 200px;">
                <div class="bt_link_new">
                    <a href="<?php echo base_url() ?>ambulatorio/exame/importarproducaomedica">
                        Importar Arquivo
                    </a>                     
                </div> 
            </td> 
        </tr>
    </table>-->
    
    
    <div id="accordion">
        <h3 class="singular"><a href="#">Excluir Importações</a></h3>
        <div>
           
            <table>
                <tr>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Operador</th>
                    <th class="tabela_header">Data</th>
                    <th class="tabela_header" colspan="3"><center>Ações</center></th>
              
                </tr>
                
                    <tbody>                        
                          <?                             
                               $url = $this->utilitario->build_query_params(current_url(), $_GET);                             
                               $estilo_linha = "tabela_content01";
                               $limit = 10;
                               $total = count($this->exame->listararquivosimportados()->get()->result());                               
                               isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                                
                               $arquivo_pasta =  $this->exame->listararquivosimportados()->limit($limit, $pagina)->get()->result();
                                
                        foreach ($arquivo_pasta as $value) {
                              ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";                              
                                $data_inicial = 0;
                                $data_final = 0;                            
                            foreach(json_decode($value->arquivo) as $item){                            
                               $res =  $this->exame->listarprocedimentoimportado($item,"true");                             
                               $data = date("Ymd",strtotime(str_replace('/','-', $res[0]->data_producao)));
                               if ($data < $data_inicial || $data_inicial == 0) {
                                   $data_inicial = $data;
                               }                               
                               if ($data > $data_final || $data_final == 0) {
                                   $data_final = $data;
                               }                    
                            }     
                            ?>
                        <tr class="<?= $estilo_linha ?>">
                                <?  if ($data_inicial == 0 && $data_final == 0) { ?>
                            <td> &nbsp; Sem informação</td>
                                <?    
                                    }else{
                                ?>                           
                                <td> <?= date('d/m/Y', strtotime($data_inicial)); ?> - <?= date('d/m/Y', strtotime($data_final)); ?></td>
                                   <?}?>
                            <td><?= $value->nome; ?></td>
                            <td><?=  date('d/m/Y H:i:s', strtotime($value->data_cadastro))?></td>
                            <td  width="10px"><div class="bt_link"><a  onclick="javascript: return confirm('Deseja realmente excluir');" href="<?= base_url()?>ambulatorio/exame/excluirprocedimentoimportadogeral/<?= $value->procedimento_importacao_arquivo_id; ?>">Excluir</a></div></td>
                            <td width="10px"><div class="bt_link"> <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/visualizarimportacao/".$value->procedimento_importacao_arquivo_id  ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"> Visualizar   </a></div></td>
                            <td width="10px"><div class="bt_link"> <a  onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/visualizarimportacaodupla/".$value->procedimento_importacao_arquivo_id  ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');"> Duplos   </a></div></td>
                        </tr>
                      <?
                       
                    }
                    
              
                ?>  
                
                  </tbody>
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

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>

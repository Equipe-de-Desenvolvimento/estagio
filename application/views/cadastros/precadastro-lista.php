
<div class="content"> <!-- Inicio da DIV content -->
    <!--    <div class="bt_link_new">
            <a href="<?php echo base_url() ?>cadastros/nivel1/carregarnivel1/0">
                Novo Nivel 1
            </a>
        </div>-->
    <div id="accordion">
        <h3 class="singular"><a href="#">Pré-cadastro</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5" class="tabela_title">
                            <form method="get" action="<?= base_url() ?>cadastros/pacientes/listarprecadastros">
                                <input type="text" name="nome" class="texto10" value="<?php echo @$_GET['nome']; ?>" />
                                <button type="submit" id="enviar">Pesquisar</button>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th class="tabela_header">Nome</th>                   
                        <th class="tabela_header">Status</th>        
                        <th class="tabela_header" width="70px;" colspan="4"><center>Detalhes</center></th>
                </tr>
                </thead>
                <?php
                $perfil_id = $this->session->userdata('perfil_id');
                $documentos = $this->operador_m->listardocumentosprofissional();
                 
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->paciente->listarprecadastro($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->paciente->listarprecadastro($_GET)->orderby('nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        
                        foreach ($lista as $item) {
                            $cor="black";
                            $status=" ";
                            $voperador{$item->operador_id} = 0;
                          if ($item->operador_id != "") {
                             
                            foreach ($documentos as $itemd) {
                            $arquivo_pasta = directory_map("./upload/arquivosoperador/$item->operador_id/$itemd->documentacao_profissional_id");
                            if ($arquivo_pasta != false) {
                                sort($arquivo_pasta);
                            }
                            $i = 0;
                            if ($arquivo_pasta != false) {
                                foreach ($arquivo_pasta as $value) {                                    
                                     if (@$jk{$itemd->documentacao_profissional_id.$item->operador_id} < 1) {
                                          @$voperador{$item->operador_id}++;
                                          @$jk{$itemd->documentacao_profissional_id.$item->operador_id}++;
                                     }
                                     
                                }
                            }
                          }
                        }     
                            if ($item->status != "") {
                               $status = "EMAIL ENVIADO"; 
                               $cor= "red";
                            }
                            if ($item->cadastrado == "t" && @$voperador{$item->operador_id} == 0) {
                                 $status = "AGUARDANDO";
                                 $cor= "green";
                            }
                            if (@$voperador{$item->operador_id} > 0) {
                               $status = "FALTA DOCUMENTAÇÃO";
                               $cor= "#f09600"; 
                            }
                            if (count($documentos) == @$voperador{$item->operador_id} ) {
                                 $status = "FINALIZADO";
                                 $cor= "blue"; 
                            }
                            
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><b style="color:<?= $cor; ?>;"><?= $status; ?></b></td>
                                 <td class="<?php echo $estilo_linha; ?>" width="120px;">    
                                    <div class="bt_link" width="120px;">                              
                                        <a href="<?= base_url()?>cadastros/pacientes/impressaoescolaridade/<?= $item->pacientes_precadastro_id; ?>"  >Impressão</a>
                                    </div> 
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="120px;">    
                                    <div class="bt_link" width="120px;">                              
                                        <a title=" " style=" cursor: pointer;" onclick="javascript:window.open('<?= base_url() ?>cadastros/pacientes/precadastroinfo/<?= $item->pacientes_precadastro_id ?>', '_blank', 'toolbar=no,Location=no,menubar=no, width=800,height=600');">Visualizar</a>
                                    </div> 
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="120px;">                                  
                                   <div class="bt_link" width="120px;">
                                        <a title=" " style=" cursor: pointer;" onclick="javascript: return confirm('Deseja realmente enviar o email de confirmação?');" href="<?= base_url() ?>cadastros/pacientes/emaildeconfirmacao/<?= $item->pacientes_precadastro_id ?>">Enviar Email</a>
                                   </div> 
                                </td>
                                <?php if($perfil_id != 18 && $perfil_id != 20){ ?>
                                <td class="<?php echo $estilo_linha; ?>" width="120px;">
                                    <div class="bt_link" width="120px;">                                
                                        <a style=" cursor: pointer;" onclick="javascript: return confirm('Deseja realmente excluir esse Pré-cadastro?');" href="<?= base_url() ?>cadastros/pacientes/excluirprecadastro/<?= $item->pacientes_precadastro_id ?>">Excluir</a> 
                                    </div> 
                                </td>
                                <?php }?>
                            </tr>
                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="6">
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


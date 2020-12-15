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
        <h3><a href="#">Listar Aparelhos </a></h3>
        <div> 
             <table> 
                <form method="get" action="<?= base_url() ?>ambulatorio/exame/listarfilaaparelho">
                    <tr>
                         
                        <th colspan="1" class="tabela_title" style="width: 150px;">Nome aparelho</th>
                        <th colspan="1" class="tabela_title" style="width: 190px;">Número de série</th> 
                    </tr>
                    <tr> 
                        <th class="tabela_title">
                            <input type="text" name="nome" class="texto03" value="<?php echo @$_GET['nome']; ?>" />
                        </th> 
                        <th class="tabela_title">
                            <input type="text" name="serie" class="texto03" value="<?php echo @$_GET['serie']; ?>" />
                        </th> 
                        <th class="tabela_title">
                            <button type="submit" id="enviar">Pesquisar</button>
                        </th> 
                    </tr>
            </table>
            <table >
                <thead> 
                <tr> 
                    <th class="tabela_header" colspan="0">Nome do aparelho</th> 
                    <th class="tabela_header" colspan="0">Número de série</th> 
                    <th class="tabela_header" colspan="4"><center>Ações</center></th> 
                </tr>
                
                </thead>
                <?php 
                $perfil_id = $this->session->userdata('perfil_id');
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listarfilaaparelhos($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                       
                        <?php
                        $lista = $this->exame->listarfilaaparelhos($_GET)->orderby('p.nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            
                            ?> 
                         <tr> 
                             <td class="<?php echo $estilo_linha; ?>"  onclick="javascript:window.open('<?= base_url(); ?>ambulatorio/exame/historicoaparelho/<?= $item->fila_aparelhos_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=230');"><?= $item->aparelho; ?></td>
                                 <td class="<?php echo $estilo_linha; ?>"><?= $item->num_serie; ?></td>
                                 <td class="<?php echo $estilo_linha; ?>" width="30px">
                                     <div class="bt_link">
                                         <a style="cursor: pointer;" href="<?= base_url(); ?>ambulatorio/exame/carregaraparelho/<?= $item->fila_aparelhos_id; ?>" >Editar</a>
                                     </div>
                                 </td>
                                 <?php if($perfil_id != 18 && $perfil_id != 20){ ?>
                                  <td class="<?php echo $estilo_linha; ?>"  width="30px">
                                     <div class="bt_link">
                                         <a style="cursor: pointer;"  onclick="javascript: return confirm('Deseja realmente excluir o aparelho?');" href="<?= base_url()?>/ambulatorio/exame/excluiraparelho/<?= $item->fila_aparelhos_id; ?>" target="_blank">Excluir</a>
                                     </div>
                                 </td>
                                 <?php }?>
                                 
                                   
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
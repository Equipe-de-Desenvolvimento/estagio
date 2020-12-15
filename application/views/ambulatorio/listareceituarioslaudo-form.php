<meta charset='UTF-8'>
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Receituarios</h3>
        <div>
 
        <?
if (count($receita) > 0) {
                        ?>
                        <table id="table_agente_toxico" border="1">
                            <thead>
                                <tr>
                                    <th class="tabela_header">Data</th>
                                    <!--<th class="tabela_header">Procedimento</th>-->
                                    <th class="tabela_header">Médico</th>
                                    <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                    <th class="tabela_header">Modelo</th>
                                    <th colspan="4" class="tabela_header">&nbsp;</th>
                                </tr>
                            </thead>
                            <?
$estilo_linha = "tabela_content01";
                            foreach ($receita as $item) {
                                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                    <!-- <td class="<?php echo $estilo_linha; ?>"><?= $item->procedimento; ?></td> -->
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                        <td class="<?php echo $estilo_linha; ?>"><b><?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></b></td>
                                        <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link"><u>
                                                <?php 
                                              if ($item->especial == 't') {
                                                  ?>
                                                 <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Imprimir
                                                   </a>
                                                <?
                                              }else{  
                                                ?>                     
                                                <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Imprimir
                                                </a>
                                              <?php }?>

                                            </u></div>
                                        </td>
 
                                    </tr>

                                </tbody>
                                <?
                            }
                        }else{

                            echo "<b>Sem Resultados de Busca</b>";
                        }

                        ?>

        </div>
    </div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<?php if (count($result) > 0) { ?>
    <div id="historicoconsulta" > 
        <?
    
        foreach ($result as $item) {
            // Verifica se há informação
            $json_evolucao = json_decode($item->obj_evolucao,true); 
            ?>
            <table>
                <tbody>
                    <tr>
                        <td width="300px" ><b>Data: </b><?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></td>
                        <td><div class="bt_link"><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exames_id ?>?ambulatorio_laudo_antigo_id=<?= @$item->ambulatorio_laudo_antigo_id; ?>');" >Imprimir</a></div></td>
                    </tr> 
                    <tr>
                        <td><b>Medico:</b>  <?= $item->medico; ?></td>
                    </tr>
                    <tr>
                        <td><b>Tipo:</b>  <?= $item->procedimento; ?></td>
                    </tr>
                    <tr>
                        <td><b>Queixa principal:</b>  <?= $item->texto; ?></td>
                    </tr>
                    <?php if(count($json_evolucao) > 0){?> 
                        <tr>
                            <td>
                                 <table>
                                <?php foreach($json_evolucao as $key => $itemevolucao){  ?> 
                                    <tr>
                                        <td>
                                            <?php if($key == "queixa_princialEvolucao"){?>
                                            <b>Queixa Principal :</b> <?= $itemevolucao; ?>  
                                            <?php }?> 
                                            <?php if($key == "historia_doenca_atualEvolucao"){?>
                                               <b>Historia da Doença Atual :</b> <?= $itemevolucao; ?>  
                                            <?php }?> 
                                            <?php if($key == "comorbidadesEvolucao"){?>
                                               <b> Comorbidades :</b> <?= $itemevolucao; ?>  
                                            <?php }?> 
                                            <?php if($key == "diagnosticoEvolucao"){?>
                                               <b>Diagnostico :</b> <?= $itemevolucao; ?>  
                                            <?php }?> 
                                            <?php if($key == "condutaEvolucao"){?>
                                               <b>Conduta :</b> <?= $itemevolucao; ?>  
                                            <?php }?>  
                                        </td>
                                    </tr>  
                                <?php }?>
                               </table>
                            </td>
                        </tr>
                     <?php }?>
                    
                    
                    <tr>
                        <td>Arquivos anexos:

                            <?
                            if (@$item->ambulatorio_laudo_antigo_id == "") {
                                $this->load->helper('directory');
                                $arquivo_pasta = directory_map("./upload/consulta/$item->ambulatorio_laudo_id/");

                                $w = 0;
                                if ($arquivo_pasta != false):
                                    foreach ($arquivo_pasta as $value) :
                                        $w++;
                                        ?>

                                        <a onclick="javascript:window.open('<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');"><img  width="50px" height="50px" src="<?= base_url() . "upload/consulta/" . $item->ambulatorio_laudo_id . "/" . $value ?>"></a>
                                        <?
                                        if ($w == 8) {
                                            
                                        }
                                    endforeach;
                                    $arquivo_pasta = "";
                                endif;
                            }
                            ?>

                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
        <? }
        ?>
    </div>

<div>

    
</div>
    <?php
} else {
    echo "";
}
?>

       <?
                  if (count($receita) > 0) {
                                        ?>
<h3>Receituarios</h3> 
<div id="historicoreceituario" ><?
                                       
                                            foreach ($receita as $item) {
                                               $receitaEspecial = false;
                                                if($item->especial == 't'){
                                                    $receitaEspecial = true;
//                                                   onclick="javascript:window.open('http://192.168.1.23/clinicas/ambulatorio/laudo/impressaoreceita/73');
                                                }
                                                ?> 
                                              <table>
                                                <tbody>
                                                    <tr>
                                                        <td  width="300px">Data: <?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                        <td>
                                                           <?php if($receitaEspecial){?>
                                                                <div class="bt_link">
                                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitaespecial/<?= $item->ambulatorio_receituario_id; ?>/true');">Imprimir</a>
                                                                </div>
                                                            <?php }else{?>
                                                                <div class="bt_link">
                                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceita/<?= $item->ambulatorio_receituario_id; ?>');">Imprimir</a>
                                                                </div> 
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td >Médico: <?= $item->medico; ?></td>
                                                    </tr>

                                                    <tr>
                                                         <td >Descri&ccedil;&atilde;o: <?= $item->texto; ?></td>
                                                    </tr>
                                                   <!-- <tr>
                                                         <td > Tipo: <?= ($item->especial == 'f') ? 'Normal': 'Especial' ; ?></td>
                                                    </tr>
                                                   -->
                                                </tbody>
                          </table> 
          <hr>
                                                <?
                                            }?>
                                   
                                            </div>
<?php 

 }else{
    echo "";
}
    ?>
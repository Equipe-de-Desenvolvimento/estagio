<?
$perfil_id = $this->session->userdata('perfil_id');
?>
<div class="content  " > <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/procedimentoplano/orcamento/0/">
            Novo TCD
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter TCD</a></h3>
        <div>
            <table>
                <thead>
                    <tr>
                    <th colspan="5" class="tabela_title">
<!--                            <form method="get" action="<?= base_url() ?>ambulatorio/exametemp/listarcredito/<?= $paciente_id ?>">
                                <tr>
                                   
                                    <th class="tabela_title"></th>
                                </tr>
                                <tr>
                                    <th class="tabela_title">
                                        <input type="text" name="procedimento" value="<?php echo @$_GET['procedimento']; ?>" />
                                    </th> 
                                    <th class="tabela_title">
                                        <button type="submit" id="enviar">Pesquisar</button>
                                    </th>
                                </tr>
                            </form>-->
                  </th>
            </table>
            <table>
                <tr>
                    <th class="tabela_header">Paciente</th>
                    <th class="tabela_header">Data</th>  
                    <th class="tabela_header">Valor (R$)</th> 
                    <th class="tabela_header">Observação</th>
                    <th class="tabela_header" width="70px;" colspan="4"><center>Detalhes</center></th>
                </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exametemp->listartcd($paciente_id);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $perfil_id = $this->session->userdata('perfil_id');
                        $lista = $this->exametemp->listartcd($paciente_id)->orderby('p.nome')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?=  $item->nome; ?> </td>  
                                <td class="<?php echo $estilo_linha; ?>"><?=  date('d/m/Y',strtotime($item->data)); ?> </td> 
                                <td class="<?php echo $estilo_linha; ?>"><?= number_format($item->valor,2,",","."); ?> </td>  
                                <td class="<?php echo $estilo_linha; ?>"><?=  $item->observacao; ?> </td>   
                                <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exametemp/imprimirtcd/" .  $item->orcamento_id . "/". $paciente_id."/".$item->paciente_tcd_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1000,height=650');"  >Imprimir</a></div>
                                </td> 
                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><div class="bt_link">
                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exametemp/imprimirtermotcd/".$paciente_id."/".$item->orcamento_id."/".$item->paciente_tcd_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1000,height=650');"  >Imprimir Termo</a></div>
                               </td> 
                                 <? if ($perfil_id == 1 || $perfil_id == 5) { ?>
                                      <!-- <td class="<?php echo $estilo_linha; ?>" width="50px;">
                                        <?php if($item->confirmado == 't'){?>
                                                 Confirmado
                                        <?php }else{?>
                                             <div class="bt_link">
                                                 <a onclick="return confirm('Deseja mesmo confirmar?')" href=<?= base_url() . "ambulatorio/exametemp/confirmartcd/" .  $item->paciente_tcd_id."/".  $item->paciente_id; ?> >Confirmar</a>
                                            </div>
                                        <?php }?>
                                    </td>  -->
                                 <?php }?>

                                 <td class="<?php echo $estilo_linha; ?>" width="50px;">
                                    <?php if($item->confirmado == 't'){?>
                                        Faturado
                                    <?php }else{?>
                                        <div class="bt_link">
                                                 <a onclick="javascript:window.open('<?=base_url()?>/ambulatorio/guia/faturarmodelo2tcd/<?=$item->paciente_tcd_id?>/<?=$item->paciente_id?>', '_blank', 'width=1000,height=800');" >Faturar</a>
                                        </div>
                                    <?php }?>
                                 </td>

                                  <? if ($perfil_id == 1 || $perfil_id == 5) { ?>
                                        <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                            <a onclick="confirmarEstorno(<?= $item->paciente_tcd_id ?>,<?= $item->paciente_id; ?>)" href="#">Estornar</a></div>
                                        </td> 
                                  <?php }?>
                            
                            </tr>
                        <? } ?>  
                    </tbody>
                <? } ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="10">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>

              <?php
            $creditosusados = $this->exametemp->listartcdusados($paciente_id)->orderby('p.nome')->get()->result();
            $totalusados = count($creditosusados);
            $estilo_linha2 = "tabela_content01"; 
            if ($totalusados > 0) {
                ?>
                <br>
                <hr>

                <fieldset style="border:1px solid silver;border-radius: 3px;" >
                    <h1 style="font-size:15px;">Histórico TCD Utilizados</h1> 
                    <table style="margin:7px;">
                        <tr>
                            <th class="tabela_header">Paciente</th> 
                            <th class="tabela_header">Valor</th>  
                            <th class="tabela_header">Data</th>
                            <th class="tabela_header">Operador</th>                    
                        </tr>

                        <?php
                        foreach ($creditosusados as $item) {
                            ($estilo_linha2 == "tabela_content01") ? $estilo_linha2 = "tabela_content02" : $estilo_linha2 = "tabela_content01";
                            @$totalusadocredito += ($item->valor * -1);
                            ?> 
                            <tr>
                                <td class="<?php echo @$estilo_linha2; ?>"> <?= @$item->nome; ?></td> 
                                <td class="<?php echo @$estilo_linha2; ?>"><?= number_format($item->valor * -1, 2, ",", ""); ?></td>
                                <td class="<?php echo @$estilo_linha2; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                <td class="<?php echo @$estilo_linha2; ?>"><?= $item->operador_cadastro; ?></td> 
                            </tr>

                            <?
                        }
                        ?>

                        <tr id="tot">
                            <td class="<?php echo @$estilo_linha2; ?>" id="textovalortotal" colspan="2">
                                <span id="spantotal"> Total de TCD utilizado.:</span> 
                            </td>
                            <td class="<?php echo @$estilo_linha2; ?>" colspan="3">
                                <span id="spantotal">
                                    R$ <?= number_format(@$totalusadocredito, 2, ',', '') ?>
                                </span>
                            </td> 
                        </tr>

                        <tfoot>
                            <tr>
                                <th class="tabela_footer" colspan="10">
                                    <?php 
//                                    $this->utilitario->paginacao($url, $totalusados, $pagina, $limit); 
                                    ?>
                                    Total de registros: <?php echo $totalusados; ?>
                                </th>
                            </tr>
                        </tfoot>  
                    </table>
                </fieldset>
                <?php
            }
            ?>
        </div>



    </div>

</div> <!-- Final da DIV content -->


<script type="text/javascript">
    
$(function () {
        $("#accordion").accordion();
    });
    function confirmarEstorno(paciente_tcd_id, paciente_id) {
//        alert('<?= base_url() ?>ambulatorio/exametemp/excluircredito/'+credito_id+'/'+paciente_id+'?justificativa=');
        var resposta = prompt("Informe o motivo");
        if (resposta == null || resposta == "") {
            return false;
        } else {
            window.open('<?= base_url() ?>ambulatorio/exametemp/excluirtcd/' + paciente_tcd_id + '/' + paciente_id + '?justificativa=' + resposta, '_self');
//            alert(resposta);
        }
    }
</script>
<style>
    #spantotal{

        color: black;
        font-weight: bolder;
        font-size: 18px;
    }
    #textovalortotal{
        text-align: right;
    }
    #tot td{
        background-color: #bdc3c7;
    }

    #form_solicitacaoitens div{
        margin: 3pt;
    }
</style>

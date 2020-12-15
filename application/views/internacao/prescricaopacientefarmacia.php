<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <h3 class="h3_title">Prescri&ccedil;&atilde;o</h3>
    <form name="form_prescricao" id="form_prescricao" action="<?= base_url() ?>internacao/internacao/gravarprescricaofarmacia/<?= $internacao_id ?>" method="post">
        <fieldset>
            <legend>Prescricao</legend>
            <div>
                <label>Medicamento</label>
                <input type="hidden" id="txtMedicamentoID" class="texto_id" name="txtMedicamentoID"/>
                <input type="text" id="txtMedicamento" class="texto06" name="txtMedicamento" required/>
            </div>

            <div>
                <label>Aprazamento</label>
                <input type="number" name="aprasamento" id="aprasamento" min="1" alt="numeromask" class="size1" required/>

            </div>

            <div>
                <label>Dias</label>                      
                <input type="number" name="dias" min="1" id="dias" alt="numeromask" class="size1" value="0" required/>
            </div>

            <div>
                <label>Observação</label>                      
                <textarea type="text" name="observacao"  id="observacao"   class="size2"   /></textarea>
            </div>
         
      

            <div style="display: block; width: 100%; margin-top: 5pt;">
                <button type="submit" name="btnEnviar">Adicionar Item</button>
                <button type="reset" name="btnLimpar">Limpar</button>
            </div>




        </fieldset>

    </form>
 <fieldset>
     <form name="form_anotacoes" id="form_anotacoes" action="<?= base_url() ?>internacao/internacao/gravaranotacoesfarmacia/<?= $internacao_id ?>" method="post" target="_blank">
             <div >
                <label>Anotações importantes</label>                      
                <textarea type="text" name="anotacoes"  id="anotacoes"   class="size3"   /></textarea>
            </div>
            <div style="display: block; width: 100%; margin-top: 5pt;">
                <button type="submit" name="btnEnviar">Adicionar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
            </div>


</form>
 </fieldset>
    

    <table>
        <thead>
            <tr>
                <th class="tabela_header">Medicamento</th>
                <th class="tabela_header">Status</th>
                <th class="tabela_header">Aprazamento</th>
                <th class="tabela_header">Dias</th>
                <th class="tabela_header">Observação</th>
                <th class="tabela_header">Quantidade Ministrada</th>
                <th class="tabela_header">Leito Solicitado</th>
                <th class="tabela_header">Operador</th>
                <th class="tabela_header">Data / Hora</th>
                <th class="tabela_header" style="text-align: left;" colspan="2">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $perfil_id = $this->session->userdata('perfil_id');

            $estilo_linha = "tabela_content01";
            foreach ($medicamentos as $item) {
                $sumministradas = $this->internacao_m->somarquantiadadeministrada($item->internacao_prescricao_id);
                $retorno_proc = $this->internacao_m->listaconveniomedicamento($item->convenio_id, $item->procedimento_farmacia);
                if (count($retorno_proc) > 0) {
                    $cor = 'green';
                } else {
                    $cor = 'blue';
                }
                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                ?>
                <tr>
                    <td style="color:<?= $cor ?>" class="<?php echo $estilo_linha; ?>"><?php echo $item->descricao; ?></td>
                    <td class="<?php echo $estilo_linha; ?>">
                        <? if ($item->confirmado == 't' && $item->qtde_volta == 0) { ?>
                            <span style="color: green;">Confirmado</span>
                        <? } else { ?>
                            <span  style="color: red;"> Não Confirmado </span>
                        <? }
                        ?>   


                    </td>
                    <td class="<?php echo $estilo_linha; ?>"><?php echo $item->aprasamento; ?>/<?php echo $item->aprasamento; ?></td>

                    <td class="<?php echo $estilo_linha; ?>"><?php echo $item->dias; ?></td>
                    <td class="<?php echo $estilo_linha; ?>"> 
                    
                    <a onclick="javascript:window.open('<?= base_url() ?>internacao/internacao/editarobservacaomedicamento/<?= $item->internacao_prescricao_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=500,height=400');" >
                              =>   <?php echo $item->observacao; ?>
                                </a>
                    </td>
                    <td class="<?php echo $estilo_linha; ?>"><?php
                        if (@$sumministradas[0]->medicamentosministrados == "") {
                            echo "0";
                        } else {

                            echo @$sumministradas[0]->medicamentosministrados;
                        }
                        ?>



                    </td>

                    <td class="<?php echo $estilo_linha; ?>"><?php echo $item->leito."<br>".$item->enf; ?></td>

                    <td class="<?php echo $estilo_linha; ?>"><?php echo $item->operador; ?></td>

                    <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data_cadastro, 8, 2) . '/' . substr($item->data_cadastro, 5, 2) . '/' . substr($item->data_cadastro, 0, 4). '<br>'.substr($item->data_cadastro, 10, 18) ; ?></td>



                        <? if ($item->confirmado != 't' || $item->qtde_volta != 0) { ?>

                            <td class="<?php echo $estilo_linha; ?>">
                            <div class="bt_link">
                                <a href="<?= base_url() ?>internacao/internacao/carregarprescricaopaciente/<?= $item->internacao_prescricao_id ?>/<?= $internacao_id ?>">
                                    <b>Ministrar</b>
                                </a>
                            </div>
                            </td>
                            <td class="<?php echo $estilo_linha; ?>">
                            <div class="bt_link">
                                <a href="<?= base_url() ?>internacao/internacao/cancelarprescricaopaciente/<?= $item->internacao_prescricao_id ?>/<?= $internacao_id ?>">
                                    <b>Cancelar</b>
                                </a>
                            </div>    
                            </td>

                        <? } else { ?>

                            <td class="<?php echo $estilo_linha; ?>">
                            <div class="bt_link">
                                <a href="<?= base_url() ?>internacao/internacao/carregarprescricaopaciente/<?= $item->internacao_prescricao_id ?>/<?= $internacao_id ?>">
                                    <b>Ministrar</b>
                                </a>
                            </div>
                            </td>

                            <td class="<?php echo $estilo_linha; ?>">
                            <? if ($perfil_id == 1) { ?>

                                <div class="bt_link">
                                    <a href="<?= base_url() ?>internacao/internacao/cancelarprescricaopaciente/<?= $item->internacao_prescricao_id ?>/<?= $internacao_id ?>">
                                        <b>Cancelar</b>
                                    </a>
                                </div> 
                                </td> 
                            <? } ?>
                        <? }
                        ?>

                    </td>

                        <!--                    <td class="<?php echo $estilo_linha; ?>">
                            
                        </td>-->
                </tr>
            <? } ?>
        </tbody>

    </table>
    <br>
    
      <table>
        <thead>
            <tr>
                <th class="tabela_header">Anotações</th>
                
                <th class="tabela_header"  colspan="1">Ações</th>
                
                
            </tr>
        </thead>
        <tbody>
            
            <?php
              $estilo_linha = "tabela_content01";     
            foreach($anotacoes as $value){
            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
            ?>
            <tr>
                <td class="<?php echo $estilo_linha; ?>"  > <a onclick="javascript:window.open('<?= base_url() ?>internacao/internacao/editaranotacoes/<?= $value->internacao_anotacoes_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=500,height=400');" >
                        => <?= substr($value->anotacoes, 0, 100) ?> </a> </td>
                               
                <td class="<?php echo $estilo_linha; ?>"  width="60px"   > 
                        <?php if($this->session->userdata('perfil_id') != 25){?>
                      <div class="bt_link" >
                           <a  onclick="javascript: return confirm('Deseja realmente excluir essa Anotação?');" href="<?= base_url() ?>internacao/internacao/excluiranotacaointernacao/<?= $value->internacao_anotacoes_id ?>" target="_blank">
                                   <b >  Excluir</b>
                           </a>
                        <?php }?>
                      </div>
                </td>
            </tr>
            <?php  }?>
        
        </tbody>
        
        </table>
 <br> 
    <style>
        .bold{
            font-weight: bolder;
        }
        .grey{
            background-color: grey;
        }
        .circulo {
            height: 50px;
            width: 25px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
        }
        .tabelaPagamentos{
            /* font-size: 5pt; */
            /* width: 100px; */
            border-collapse: collapse;
            border-spacing: 5px;
        }

        .tabelaPagamentos td{
            font-size: 12px;
            padding: 7px;
            vertical-align: top;

        }
        .ficha_ceatox div{
            float: left;
            margin-right: 30px;
            min-width: 50px;
        }
        tr{
            /* vertical-align: top; */
        }
    </style>
    <div style="width:80px">
        <table border="1" class="tabelaPagamentos">
            <tr class="grey">
                <th colspan="3">Legenda</th>

            </tr>
            <tr >
                <td >Associado Convênio</td>
                <!--<td >Azul</td>-->
                <td>
                    <div class="circulo" style="color: blue; background-color: green;">

                    </div>
                </td>
            </tr>
            <tr >
                <td >Não Associado</td>
                <!--<td >Verde</td>-->
                <td>
                    <div class="circulo" style="color: blue; background-color: blue;">

                    </div>
                </td>
            </tr>
            <tbody>

            </tbody>
        </table>
    </div>


</div> 
 <br> <br> <br>
  <br>
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>

<script>
window.onload = function(e){ 
  $("#anotacoes").val("");
}
    $(function () {
        $("#txtMedicamento").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=prescricaomedicamento",
            minLength: 2,
            focus: function (event, ui) {
                $("#txtMedicamento").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtMedicamento").val(ui.item.value);
                $("#txtMedicamentoID").val(ui.item.id);
                return false;
            }
        });
    });

    $(document).ready(function () {
        jQuery('#form_prescricao').validate({
            rules: {
                txtMedicamento: {
                    required: true
                },
                dias: {
                    required: true
                },
                aprasamento: {
                    required: true
                }
            },
            messages: {
                txtMedicamento: {
                    required: "*"
                },
                dias: {
                    required: "*"
                },
                aprasamento: {
                    required: "*"
                }
            }
        });
    });
</script>

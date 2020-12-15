    

<html>
    <head>
        <title>Ficha ocorrência</title>
         
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>  
<script type="text/javascript" src="<?= base_url() ?>js/tinymce2/js/tinymce/tinymce.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/plugins/spellchecker/plugin.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script> 
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css"> 
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>


<?php 
$excluir = 'false'; 
if (($ocorrencia[0]->situacao == "AGUARDANDO" && $this->session->userdata('perfil_id') == 1) || ($this->session->userdata('operador_id') == 1)) {
   $excluir = 'true';  
} 
?>

<style> 
     .row{
        display: flex;
         /*border:1px solid red;*/
        }
        .col{
            flex: 1;
            align-self: center;
            /*border:1px solid blue;*/
        }.descricaoOcorrencia{
            border:1px solid silver;
            padding: 5px;
        }
        
        .responderOcorrencia,#msg{
              border:1px solid silver;
                 padding: 5px;
        } 
         
    textarea
    {
      width:100%;
    }
    #respostaTxt{
        font-size: 14px;
    }
    b{
       font-size: 14px; 
    }
    td{
        padding: 5px;
    }
        
</style>
    </head>
    <body>
        <div>
            <div class="row">
                <div class="col"  >
                    <table border="0" width="100%">
                        <tr>
                            <td width="60%">Detalhes da ocorrência</td>
                            <td>
                                <?php if($ocorrencia[0]->situacao == "FINALIZADO"){?>
                                      Ocorrência finalizada  
                                <?php }else{
                                 ?>
                                 <button onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/descricaoocorrencia/" . $atendimento_ocorrencia_id; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=800,height=400');">Finalizar</button>
                                <?
                                }
                               ?>
                                
                                <?php if ($excluir == 'true') {  ?>
                                <a onclick="javascript: return confirm('Deseja realmente excluir essa ocorrencia?');" href="<?= base_url()?>ambulatorio/laudo/excluirocorrencia/<?= $atendimento_ocorrencia_id; ?>"><button >Excluir</button></a>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td   valign="top" rowspan="2"  > 
                                <? if ($ocorrencia[0]->descricao != "") {
                                    $dataocorrencia =  substr($ocorrencia[0]->data_finalizacao, 8, 2) . '/' . substr($ocorrencia[0]->data_finalizacao, 5, 2) . '/' . substr($ocorrencia[0]->data_finalizacao, 0, 4);
                                    $horaocorrencia = substr($ocorrencia[0]->data_finalizacao, 11, 2) . ':' . substr($ocorrencia[0]->data_finalizacao, 14, 2);  ?>
                                <div class="descricaoOcorrencia"> 
                                    <b><?= $ocorrencia[0]->responsavel; ?> - <?= $dataocorrencia .' '.$horaocorrencia; ?></b>
                                    <p id='respostaTxt'><?= $ocorrencia[0]->descricao; ?></p>
                                <? }?>
                                </div>
                                <div id="respostas">
                                    <?php 
                                    foreach($respostas as $item){
                                        $dataocorrencia =  substr($item->data_cadastro, 8, 2) . '/' . substr($item->data_cadastro, 5, 2) . '/' . substr($item->data_cadastro, 0, 4);
                                        $horaocorrencia = substr($item->data_cadastro, 11, 2) . ':' . substr($item->data_cadastro, 14, 2);
                                        ?>
                                    <div id='msg'><b><?= $item->responsavel; ?> - <?= $dataocorrencia .' '.$horaocorrencia; ?></b> <p id='respostaTxt'><?= $item->mensagem; ?> </p></div>
                                    <?
                                    } 
                                    ?>
                                </div>

                                <div id="respostas">
                                <?
                                $dataocorrencia =  substr($ocorrencia[0]->data_cadastro, 8, 2) . '/' . substr($ocorrencia[0]->data_cadastro, 5, 2) . '/' . substr($ocorrencia[0]->data_cadastro, 0, 4);
                                $horaocorrencia = substr($ocorrencia[0]->data_cadastro, 11, 2) . ':' . substr($ocorrencia[0]->data_cadastro, 14, 2);
                                ?>
                                    <div id='msg'><b><?= $ocorrencia[0]->responsavel; ?> - <?= $dataocorrencia .' '.$horaocorrencia; ?></b> <p id='respostaTxt'><?= $ocorrencia[0]->mensagem; ?> </p></div>
                                </div>

                                <div class="responderOcorrencia">
                                    <textarea rows="4" name="resposta" id="resposta" ></textarea><br>
                                    <button onclick="responderOcorrencia()">Responder</button>
                                </div>
                            </td>
                            <td valign="top" >
                                <table   cellspacing=0 cellpadding=2  style="border:1px solid silver;" width="100%">
                                    <tr>
                                        <td style="border:1px solid silver;">Informações da Ocorrência</td>
                                    </tr>
                                     <tr>
                                         <td>
                                             <table border="0" > 
                                                 <tr>
                                                     <td  style="text-align: right;" valign="top" >Assunto:</td>
                                                     <td  style="text-align: right;" valign="top"> </td>
                                                     <td style="text-align: left;" valign="top"> <?= wordwrap( $ocorrencia[0]->assunto, 20, "<br />\n",true)?> </td>
                                                 </tr>  
                                             <?php 
                                                 $json = json_decode($ocorrencia[0]->template); 
                                                 foreach($json as $value){
                                                  if ($value->tipo == "checkbox") {
                                                      $check = ""; 
                                                      if ($value->required == 1) {
                                                         $check = "Confirmado";  
                                                      }else{
                                                          $check = "Não confimado";  
                                                      }
                                                      $value->value = $check;
                                                  }  
                                                  if ($value->tipo == "select") {
                                                      if ($value->value == "") {
                                                          $value->value = $value->opcoes[0];
                                                      }
                                                  }
                                                  ?>
                                                 <tr>
                                                     <td style="text-align: right;" valign="top"><?= $value->nome; ?> :<td> 
                                                     <td style="text-align: left;" valign="top"><?=  wordwrap($value->value, 20, "<br />\n",true) ?><td>
                                                 </tr>
                                                 <? 
                                                 } 
                                             ?>  
                                         </table>
                                             <?php if(count($arquivo_pasta) > 0){?>
                                             <div >
                                                <table width="100%">
                                                    <tr>
                                                        <td>Arquivo anexado:</td>
                                                    </tr>
                                                    <tr>
                                                    <?
                                                    $i=0;
                                                    if ($arquivo_pasta != false):
                                                        foreach ($arquivo_pasta as $value) :
                                                        $i++;
                                                            ?> 
                                                    <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/ocorrencias/" . $atendimento_ocorrencia_id . "/" . $value ?>','_blank','toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/ocorrencias/" . $atendimento_ocorrencia_id . "/" . $value ?>"><br><? echo substr($value, 0, 10)?>
                                                        </td>
                                                        <?
                                                        if($i == 8){
                                                            ?>
                                                            </tr><tr>
                                                            <?
                                                        }
                                                        endforeach;
                                                    endif
                                                    ?>
                                                </table>
                                            </div> 
                                             <?php }?>
                                             
                                         </td>
                                    </tr>
                                </table>
                                    
                            </td>  
                        </tr>
 
                       
                        
                    </table>
                </div>
                  
            </div>
        </div>
        <input type="hidden" name="atendimento_ocorrencia_id" id="atendimento_ocorrencia_id" value="<?= $atendimento_ocorrencia_id; ?>">
        <input type="hidden" name="operador" id="operador" value="<?= $operador[0]->nome; ?>"> 
    </body>
</html>

<script type="text/javascript" >
    function responderOcorrencia(){
        var atendimento_ocorrencia_id = $("#atendimento_ocorrencia_id").val();
        var operador = $("#operador").val();
        var msg = $("#resposta").val();
        var resposta = "<div id='msg'><b>"+operador+"</b><p id='respostaTxt'>"+msg+"</p></div>";  
               var datat = new FormData();  
                datat.append('txtmensagem', msg);
                datat.append('txtatendimento_ocorrencia_id', atendimento_ocorrencia_id);   
                    $.ajax({
                        type: "POST", 
                        data:datat,
                        enctype: 'multipart/form-data',
                        processData: false,
                        contentType: false,
                        url: "<?= base_url() ?>ambulatorio/laudo/gravarespostaocorrencia",
                        success: function (data, textStatus, jqXHR) {  
                            $("#respostas").append(resposta); 
                            $("#resposta").val("");
                        },
                        error: function (data) {
                           alert('Ocorreu um erro ao responder ocorrência, por favor, tente novamente');
                            console.log(data);
                        }
                    });
         
    }
    
</script>


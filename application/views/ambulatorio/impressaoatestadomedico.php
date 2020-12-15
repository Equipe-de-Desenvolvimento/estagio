<html>
    <head>
        <title>Atestado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
         <style>
             
             @media print {
                body * {
                  visibility: hidden;
                }
 
  
                .linhasAlternadas {
                  width: 100%;
                  height:100%;  
                }
                /*  body {
                    margin: 0;
                    color: #000;
                    background-color: #fff;
                  }*/


                    #fontatestado{
                        font-size: 17px;
                        padding: 7px;
                    }
                    .checkboxatestado{
                           -ms-transform: scale(1.6); /* IE */
                           -moz-transform: scale(1.6); /* FF */
                           -webkit-transform: scale(1.6); /* Safari and Chrome */
                           -o-transform: scale(1.6); /* Opera */
                           transform: scale(1.6);
                           padding: 10px;
                    }
                    
                   
             } 
          </style>
    </head>
    <body>
        <?php                   
        $array = json_decode($lista[0]->texto,true); 
          switch (date("m")) {
                                case "01":    $mes = 'Janeiro';     break;
                                case "02":    $mes = 'Fevereiro';   break;
                                case "03":    $mes = 'Março';       break;
                                case "04":    $mes = 'Abril';       break;
                                case "05":    $mes = 'Maio';        break;
                                case "06":    $mes = 'Junho';       break;
                                case "07":    $mes = 'Julho';       break;
                                case "08":    $mes = 'Agosto';      break;
                                case "09":    $mes = 'Setembro';    break;
                                case "10":    $mes = 'Outubro';     break;
                                case "11":    $mes = 'Novembro';    break;
                                case "12":    $mes = 'Dezembro';    break; 
                         }     
        ?>
        <table  class="linhasAlternadas"    >
                                        <tr>
                                            <td id="fontatestado" style="width: 70px;text-align: center;border-right:1px solid #eb4d4b;"> 
                                                <div style="margin: 15px;">
                                                     <img  width="100px" height="100px"   src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta_logo[0]}" ?>">
                                                     <input type="hidden" id="empresa_id" name="empresa_id" value="<?= (@$empresa_id == "") ? $this->session->userdata('empresa_id') : $empresa_id; ?>" >
                                                 </div>
                                            </td>
                                            <td colspan="2" id="fontatestado" style="text-align: center;">
                                                <b  >
                                             <?= $empresapermissao[0]->logradouro;?> <?= $empresapermissao[0]->numero;?> - <?= $empresapermissao[0]->municipio;?> <br>
                                             <?= $empresapermissao[0]->telefone; ?>   /   <?= $empresapermissao[0]->celular; ?> <br></b>
                                                <b style="font-family: arial;font-size: 14px; font-weight: normal;">
                                             <?= $empresapermissao[0]->site_empresa; ?> &nbsp;&nbsp;<img  style="width: 13px;  " src="<?= base_url(); ?>img/logo-facebook.png" > <?= $empresapermissao[0]->facebook_empresa; ?>  &nbsp;&nbsp;<img  style="width: 15px;" src="<?= base_url(); ?>img/logo-instagram.png" > <?= $empresapermissao[0]->instagram_empresa; ?>
                                            </b>
                                            </td>
                                        </tr>
                                        <tr>
                                             <td id="fontatestado" colspan="3" style="text-align: center; font-weight: bold;font-size: 20px;">ATESTADO</td> 
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="3"><b>O(a) Sr.(a)&nbsp;&nbsp;<u><?= @$lista[0]->paciente ?></u>&nbsp;&nbsp;compareceu a esta Clínica às&nbsp;&nbsp; <u><?= date('H:i:s',strtotime($lista[0]->data_autorizacao)); ?></u> &nbsp;&nbsp;horas para:</b> <b><?= @$lista[0]->procedimento; ?> </b></td>
                                        </tr>
                                        <tr>
                                            <td id="fontatestado" colspan="3" > </td> 
                                        </tr>
                                         <tr>
                                             <td id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                        <tr>
                                             <td id="fontatestado" colspan="3" ><b>Outrossim comunicamos que:</b></td> 
                                        </tr>                                      
                                        <?php if($array['voltaemseguidatrabalho'] == "on"){?>
                                            <tr>
                                                <td id="fontatestado" colspan="3" >
                                                    <table  style="width: 100%;" >
                                                        <tr>
                                                            <td  style="width: 100%;" id="fontatestado"><b>Pode voltar em seguida ao trabalho  <u><?= date('H:i:s',strtotime($lista[0]->data_autorizacao)); ?></u> </b></td>
                                                            
                                                            <td  id="fontatestado" style="width:100%; text-align: right;  padding: 0px;" > </td>
                                                        </tr>
                                                    </table>  
                                                </td>  
                                            </tr>
                                        <?php }?>
                                        
                                         <?php if($array['afastadohoje'] == "on"){?>
                                           <tr>
                                                 <td id="fontatestado" colspan="3" >
                                                     <table   style="width: 100%;">
                                                         <tr>
                                                             <td style="width: 100%;" id="fontatestado"><b>Deverá ficar afastado(a) no dia de hoje <u><?= date('d/m/Y'); ?></u></b>
                                                              <input type="hidden" id="diaafastado" value="<?= $array['diaafastado']; ?>" >
                                                             </td>
                                                              <td id="fontatestado" style="width:100%; text-align: right;  padding: 0px;"> </td>
                                                         </tr>
                                                     </table>  
                                                 </td>  
                                           </tr>
                                         <?php }?>
                                           
                                           <?php if($array['qtd_dias_afastado'] != ""){?> 
                                           
                                                <tr>
                                                    <td id="fontatestado" colspan="3" >
                                                           <table   style="width: 100%;">
                                                               <tr>
                                                                   <td id="fontatestado" style="width: 100%;"><b>Deverá ficar afastado(a) do trabalho &nbsp;&nbsp; ( <?= $array['qtd_dias_afastado']; ?> ) dias a contar desta data  <?= $array['data_afastamento']; ?></b></td>                                                                
                                                                   <td style="width:100%; text-align: right;  padding: 0px;">
                                                                     
                                                                </td>
                                                               </tr>
                                                           </table>
                                                           
                                                        
                                                       </td>
                                                     
                                                 </tr>
                                                 
                                                 
                                          <?php }?> 
                                        
                                           <?php if(isset($cid[0]->no_cid) && $cid[0]->no_cid != ""){?>
                                             <tr>
                                                  <td  id="fontatestado" colspan="3" > 
                                                      <table  style="width: 100%;" >
                                                          <tr>
                                                              <td  id="fontatestado"> <b>d. CID  
                                                          
                                                          <?php if(isset($cid[0]->no_cid) && $cid[0]->no_cid != ""){?>
                                                             <u><?= $cid[0]->co_cid."-".$cid[0]->no_cid; ?></u>
                                                          <?php }?>  
                                                          </b></td>
                                                        <td id="fontatestado" style="width:100%; text-align: right;  padding: 0px;"> </td>
                                                          </tr>
                                                      </table>
                                                    </td> 
                                                    
                                             </tr>
                                           <?php }?>
                                         
                                        <tr>
                                              <td id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                        <tr>
                                              <td  id="fontatestado" colspan="3" >&nbsp;</td> 
                                        </tr>
                                        
                                        
                                    </table> 

    </body>
</html>

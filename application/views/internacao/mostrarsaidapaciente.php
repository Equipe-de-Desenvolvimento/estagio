<div class="content ficha_ceatox">
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>internacao/internacao/pesquisarsaida/">
            Voltar
        </a>
    </div>

    <div>
        <form name="form_paciente" id="form_paciente">
            <fieldset>
                <legend>Dados do Paciente</legend>
                <div>
                    <label>Nome</label>                      
                    <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente[0]->paciente ?>" readonly/>
                    <input type="hidden" id="txtAtivoInternacao" name="ativo_internacao"  class="texto09" value="<?= $paciente[0]->ativo ?>" readonly/>
                </div>

                <div>
                    <label>Unidade</label>
                    <input type="text"  name="leito" id="leito" class="texto09" value="<?= @$paciente[0]->unidade_nome; ?>" readonly/>
                </div>
                <div>
                    <label>Enfermaria</label>
                    <input type="text"  name="leito" id="leito" class="texto09" value="<?= @$paciente[0]->enfermaria_nome; ?>" readonly/>
                </div>
                <div>
                    <label>Leito</label>
                    <input type="text"  name="leito" id="leito" class="texto09" value="<?= @$paciente[0]->leito_nome; ?>" readonly/>
                </div>



                <div>
                    <label>Data da Internacao</label>                      
                    <input type="text" id="data_internacao" name="data_internacao"  class="texto09" value="<?= date("d/m/Y H:i:s", strtotime(@$paciente[0]->data_internacao)); ?>" readonly/>
                </div>

                <div>
                    <label>Médico Responsável Internação</label>                      
                    <input type="text" id="data_internacao" name="medico_responsavel"  class="texto09" value="<?= @$paciente[0]->medico_internacao; ?>" readonly/>
                </div>
                <div>
                    <label>Data Saída</label>                      
                    <input type="text" id="data_internacao" name="data_internacao"  class="texto09" value="<?= date("d/m/Y H:i:s", strtotime(@$paciente[0]->data_saida)); ?>" readonly/>
                </div>

                <div>
                    <label>Médico Responsável Saída</label>                      
                    <input type="text" id="data_internacao" name="medico_responsavel"  class="texto09" value="<?= @$paciente[0]->medico_saida; ?>" readonly/>
                </div>

                <div>
                    <label>Data de Nascimento</label>                      
                    <input type="text" id="data_nascimento" name="data_nascimento"  class="texto09" value="<?= (@$paciente[0]->nascimento != '') ? date("d/m/Y", strtotime(@$paciente[0]->nascimento)) : ''; ?>" readonly/>
                </div>
                <div>
                    <label>Sexo</label>
                    <input type="text" id="sexo" name="sexo"  class="texto09" value="<?
                    if (@$paciente[0]->sexo == "M"):echo 'Masculino';
                    endif;
                    if (@$paciente[0]->sexo == "F"):echo 'Feminino';
                    endif;
                    if (@$paciente[0]->sexo == "O"):echo 'Outro';
                    endif;
                    ?>" readonly/>
                </div> 

                <div>

                    <input type="hidden" id="txtidpaciente" name="idpaciente"  class="texto09" value="<?= $paciente[0]->paciente_id ?>" readonly/>
                </div>


            </fieldset>

            <fieldset>
                <? if ($paciente[0]->motivo_saida == '') { ?>
                    <div>
                        <label>Motivo de Saida</label>
                        <input type="text"  name="leito" id="leito" class="texto09" value="Transferencia - <?= $paciente[0]->hospital_transferencia ?>" readonly/>
                    </div>  
                <? } else {
                    ?>
                    <div> 
                        <label>Motivo de Saida</label>

                        <input type="text"  name="leito" id="leito" class="texto09" value="<?= $paciente[0]->motivosaida; ?>" readonly/>
                    </div> 
                <? } ?>


                <div>
                    <label>Observações</label>

                    <input type="text"  name="leito" id="leito" class="texto09" value="<?= $paciente[0]->observacao_saida; ?>" readonly/>
                </div>
            </fieldset> 
            <?php if($empresapermissoes[0]->internacao == "t"){?>
            <fieldset style="     ">
               <legend>Histórico(Centro Cirurgico)</legend>
            <?php 
            $procedimentos = $this->solicitacirurgia_m->listarcirurgialancadas($paciente[0]->internacao_id);
//            echo "<pre>";
//            print_r($procedimentos);
            ?>
                
               <table > 
                   <tr>
                       <td style="font-size: 10px;"> <div class="bt_link" ><a onclick="javascript:window.open('<?= base_url(); ?>centrocirurgico/centrocirurgico/internacaoclaracao/<?= $paciente[0]->internacao_id; ?> ', '_blank', 'width=800,height=700');">Declaração</a></div></td>
                   </tr>
                   <tr>
                        <th>Nome</th>
                        <th>Situação</th>
                        <th>Data Solicitação</th>
                        <th>Data Prevista</th>
                        <th>Data Realização</th> 
                   </tr>
                   <? foreach($procedimentos as $item){
                       $recibo = false;
                        $situacao = '';
                        if ($item->situacao == 'FATURAMENTO_PENDENTE') {
                            $situacao = "<span style='color:red'>Faturamento</span>";
                        } elseif ($item->situacao == 'AGUARDANDO') {
                            $situacao = "<span style='color:#ff8400'>Aguardando</span>";
                            $recibo = true;
                        } elseif ($item->situacao == 'EQUIPE_FATURADA') {
                            $situacao = "<span style='color:green'>Aguardando</span>";
                        } elseif ($item->situacao == 'REALIZADA') {
                            $situacao = "<span style='color:green'>Realizada</span>";
                            $recibo = true;
                        }
                       ?>
                   <tr>
                       <td style="font-size: 10px;"> <?php echo $item->nome; ?></td>
                       <td style="font-size: 10px;"><?php echo $situacao; ?> </td>
                       <td style="font-size: 10px;"><?php echo date("d/m/Y H:i:s", strtotime($item->data_cadastro)); ?></td>
                       <td style="font-size: 10px;"><?= date('d/m/Y', strtotime($item->data_prevista)) ?> </td>
                      <td style="font-size: 10px;">
                          <?if($item->situacao == 'REALIZADA' && $item->data_atualizacao != "") {
                                            echo   date('d/m/Y H:i:s', strtotime($item->data_atualizacao)) ;
                           } ?>
                      </td> 
                        <? if ($recibo) { ?>
                            <td  width="135px;"><div  class="bt_link">
                                   <a  href="<?= base_url() ?>centrocirurgico/centrocirurgico/recibocirurgia/<?= $item->solicitacao_cirurgia_id; ?>" target="_blank">Recibo</a></div>
                            </td>
                        <?} ?>  
                   </tr>  
                   <?}?>  
               </table>
 
            </fieldset>
            <br>
            <?php }?>
            

        </form>


    </div>

    <div class="clear"></div>
</div>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>

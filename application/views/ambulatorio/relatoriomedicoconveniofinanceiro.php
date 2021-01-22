<div class="content"> <!-- Inicio da DIV content -->
    <div id="">
        <div class="alert alert-primary"><a>Relatório</a></div>
        <div class="panel-body infodados">
            <form name="form_paciente" id="form_paciente"  method="post" action="<?= base_url() ?>ambulatorio/guia/gerarelatoriomedicoconveniofinanceiro">
              
                <!-- <div>
                        <label>Medico</label>
                        <select name="medicos" id="medicos" class="size2">
                            <option value="0">TODOS</option>
                            <? foreach ($medicos as $value) : ?>
                                <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </div> -->
                <div class="row">
                    <!-- <br> -->
                    <!-- <div>
                        <label>Especialidade</label>
                        <select name="grupo[]" id="grupo" class="chosen-select" data-placeholder="Selecione as especialidades (Todos ou vázio trará todos)..." multiple>
                            <option value='0' >TODOS</option>
                            <option value='1' >SEM RM</option>
                            <? foreach ($grupos as $grupo) { ?>                                
                                <option value='<?= $grupo->nome ?>' <?
                                if (@$obj->_grupo == $grupo->nome):echo 'selected';
                                endif;
                                ?>><?= $grupo->nome ?></option>
                                    <? } ?>
                        </select>
                    </div> -->
                    <div class="col-lg-2">
                        <div>
                            <label>Faturamento</label>
                            <select name="faturamento" id="faturamento" class="form-control" >
                                <option value='' >TODOS</option>
                                <option value='t' >Faturado</option>
                                <option value='f' >Não Faturado</option>
                            </select>
                        </div>
                        <!-- <div>
                            <label>Clinica</label>
                            <select name="clinica" id="clinica" class="form-control" >
                                <option value='SIM' >SIM</option>
                                <option value='NAO' >NÃO</option>
                            </select>
                        </div> -->
                        <div>
                            <label>Ordem do Relatório</label>
                            <select name="ordem" id="recibo" class="form-control" >

                                <option value='0' >NORMAL</option>
                                <option value='1' >ATENDIMENTO</option>
                            </select>
                        </div>
                        <div>
                            <label>Gerar PDF</label>
                            <select name="gerarpdf" id="gerarpdf" class="form-control">
                                <option value="NAO">NÃO</option>
                                <option value="SIM">SIM</option>
                            </select>
                        </div>
                         <div>
                                <label>Sala</label>
                                <select name="sala_id" id="sala_id" class="form-control">
                                    <option value='0' >TODOS</option>
                                    <? foreach ($salas as $value) : ?>
                                        <option value="<?= $value->exame_sala_id; ?>" ><?php echo $value->nome; ?></option>
                                    <? endforeach; ?>
                                </select>
                           </div>
                    </div>
                        <? $empresa_id = $this->session->userdata('empresa_id'); ?>
                        
                    <!-- </div>
                    <div class="col-lg-2"> -->
                       
                        <!-- <div>
                            <label>Produção Ambulatorial</label>
                            <select name="producao_ambulatorial" id="producao_ambulatorial" class="form-control">
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                        </div> -->
                        <!-- <div>
                            <label>Laboratório</label>
                        </div>
                        <div>
                            <select name="laboratorio" id="laboratorio" class="form-control" >
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                        </div> -->
                        <!-- </div> -->
                            <!-- <label>Empresa</label>
                            <select name="empresa" id="empresa" class="form-control">
                                <? foreach ($empresa as $value) : ?>
                                     <option value="<?= $value->empresa_id; ?>" <? if ($empresa_id == $value->empresa_id) { ?>selected<? } ?>><?php echo $value->nome; ?></option> -->
                                <? endforeach; ?>
                                <!-- <option value="0">TODOS</option> -->
                            <!-- </select> 
                    </div> -->
                    <div class="col-lg-2">
                       
                        <div>
                            <label>Promotor</label>
                            <select name="promotor" id="promotor" class="form-control" >
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                        </div>
                        <div>
                            <label>Somar Crédito</label>
                            <select name="somarcredito" id="somarcredito" class="form-control" >
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                        </div>
                        <div>
                            <label>Desconto Especial</label>
                            <select name="tipo_desconto" id="tipo_desconto" class="form-control">
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                            <div>
                                <label>Solicitante</label>
                                <select name="solicitante" id="solicitante" class="form-control" >
                                    <option value='NAO' selected="">NÃO</option>
                                    <option value='SIM' >SIM</option>
                                </select>
                           </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Forma de Pagamento</label>
                            <select name="forma_pagamento" id="forma_pagamento" class="form-control" >
                                <option value='NAO' >NÃO</option>
                                <option value='SIM' >SIM</option>
                            </select>
                        </div>
                        <div>
                            <label>Situação</label>
                            <select name="situacao" id="situacao" class="form-control" >
                                <option value='' >TODOS</option>
                                <option value='1'>FINALIZADO</option>
                                <option value='0' >ABERTO</option>
                            </select>
                            
                                <div>
                                    <label>Revisor</label>
                                    <select name="revisor" id="revisor" class="form-control">
                                        <option value="0">TODOS</option>
                                        <? foreach ($medicos as $value) : ?>
                                            <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                              </div>
                        </div>
                        <div>
                            <label>Convenio</label>
                            <select name="convenio" id="convenio" class="form-control">
                                <option value='0' >TODOS</option>
                                <option value="" >SEM PARTICULAR</option>
                                <option value="1" >PARTICULARES</option>
                                <? foreach ($convenio as $value) : ?>
                                    <option value="<?= $value->convenio_id; ?>" ><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                   
                    
                     <div class="col-lg-2">
                            <div>
                                    <label>Grupo Convenio</label>
                                    <select name="grupoconvenio" id="convenio" class="form-control">
                                        <option value='0' >TODOS</option>
                                        <? foreach ($grupoconvenio as $value) : ?>
                                            <option value="<?= $value->convenio_grupo_id; ?>" ><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                            </div>
                            <div>
                                <label>Valor Líquido</label>
                                <select name="valor_liquido" id="valor_liquido" class="form-control" >
                                    <option value='NAO' >NÃO</option>
                                    <option value='SIM' >SIM</option>
                                </select>
                            </div>
                            
                            <div>
                                <label>Recibo</label>
                                <select name="recibo" id="recibo" class="form-control" >

                                    <option value='NAO' >NÃO</option>
                                    <option value='SIM' >SIM</option>
                                </select>
                            </div>
                            <div>
                                <label>Taxa de administração</label>
                                <select name="mostrar_taxa" id="mostrar_taxa" class="form-control" >
                                    <option value='NAO' >NÃO</option>
                                    <option value='SIM' >SIM</option>
                        
                                </select>
                            </div>
                     </div>
              
            </form>

        </div>
    </div>


</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#txtdata_inicio").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function () {
        $("#txtdata_fim").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function () {
        $("#accordion").accordion();
    });

</script>
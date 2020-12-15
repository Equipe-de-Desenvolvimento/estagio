<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Gerar relatorio Agenda</a></h3>
        <div>
            <form method="post" action="<?= base_url() ?>ambulatorio/exame/gerarelatoriorecepcaoagenda">
                <dl>
                    <dt>
                        <label>Médico</label>
                    </dt>
                    <dd>
                        <select name="medicos" id="medicos" class="size2">
                            <option value="" >Todos</option>
                            <? foreach ($medicos as $value) : ?>
                                <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <dt>
                            <label>Operador</label>
                        </dt>
                        <dd>
                            <select name="operador[]" id="operador" class="size3 chosen-select"  multiple data-placeholder="Selecione" >
                                <option value="0">TODOS</option>
                                <option value="1">Administrador</option>
                                <? foreach ($operadores as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </dd> 
                       
                    <br><br>
                     <?php
                    }
                    
                    ?>
                    <dt>
                        <label>Convênio</label>
                    </dt>
                    <dd>
                        <select name="convenio" id="convenio" class="size2">
                            <option value='0' >Todos</option>
                            <? foreach ($convenio as $value) : ?>
                                <option value="<?= $value->convenio_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>

                    <dt>
                        <label>Sala</label>
                    </dt>
                    <dd>
                        <select name="salas" id="salas" class="size2">
                            <option value="" >Todas</option>
                            <? foreach ($salas as $value) : ?>
                                <option value="<?= $value->exame_sala_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>

                    <dt>

                    <dt>
                        <label>Data inicio</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_inicio" id="txtdata_inicio" alt="date" required=""/>
                    </dd>
                    <dt>
                        <label>Data fim</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_fim" id="txtdata_fim" alt="date" required=""/>
                    </dd>

                    <dt>
                        <label>Hora inicio</label>
                    </dt>
                    <dd>
                        <input type="text" alt="time" id="horainicio" name="horainicio" class="size1"/>
                    </dd>
                    <dt>
                        <label>Hora fim</label>
                    </dt>
                    <dd>
                        <input type="text" alt="time" id="horafim" name="horafim" class="size1"/>
                    </dd>

                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <dt>
                            <label>Procedimento</label>
                        </dt>
                        <dd>
                            <select name="procedimento" id="procedimento" class="size2">
                                <option value="SIM">SIM</option>
                                <option value="NAO">NÃO</option>
                            </select>
                        </dd>
                        <?php
                    }
                    ?>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <dt>
                            <label>Mostrar Sala</label>
                        </dt>
                        <dd>
                            <select name="sala" id="sala" class="size2">
                                <option value="SIM">SIM</option>
                                <option value="NAO">NÃO</option> 
                            </select>
                        </dd>
                        <?php
                    }
                    ?>
                    <dt>
                        <label>Apenas Pacientes Agendados</label>
                    </dt>
                    <dd>
                        <select name="agendados" id="agendados" class="size2">
                            <option value="SIM">SIM</option>
                            <option value="NAO">NÃO</option>
                        </select>
                    </dd>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        
                    } else {
                        ?>
                        <dt>
                            <label>Data</label>
                        </dt>
                        <dd>
                            <select name="data_mostrar" id="data_mostrar" class="size2">
                                <option value="SIM">SIM</option>
                                <option value="NAO">NÃO</option>
                            </select>
                        </dd>
                        <?php
                    }
                    ?>
                    <dt>
                        <label>Empresa</label>
                    </dt>
                    <dd>
                        <select name="empresa" id="empresa" class="size2">
                            <? foreach ($empresa as $value) : ?>
                                <option value="<?= $value->empresa_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                            <option value="0">TODOS</option>
                        </select>
                    </dd>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                        <input type="hidden" name="modelorealatorio" value="0">
                        <?
                    } else {
                        ?>
                     <dt>
                        <label>Modelos</label>
                    </dt>
                    <dd>
                        <select name="modelorealatorio" id="modelorealatorio" class="size2">
                            <option value="1">Modelo 1</option>    
                            <option value="0">Modelo 2</option>                                                  
                        </select>
                    </dd>
                    <?
                    }
                    ?>
                    <dt>
                        <label>Relatório</label>
                    </dt>
                    <?php
                    if (@$permissaoempresa[0]->agendahias == "t") {
                        ?>
                    <dd>
                        <select name="tipoRelatorio" id="tipoRelatorio" class="size2">
                            <option value="0">Agenda Consulta</option>
                            <option value="1">Agenda Exame</option>
                            <option value="3">Agenda Especialidade</option>
                            <option value="2">Faltas</option>
                            <option value="4">Todos</option> 
                        </select>
                    </dd>
                        <?
                       
                    } else {
                        ?>
                    <dd>
                        <select name="tipoRelatorio" id="tipoRelatorio" class="size2">
                            <option value="0">Agenda Consulta</option>
                            <option value="1">Agenda Exame</option>
                            <option value="3">Agenda Especialidade</option>
                            <option value="2">Faltas</option>
                            <option value="4">Todos</option> 
                        </select>
                    </dd>
                    <?
                    }
                    ?>
                </dl>
                <button type="submit" >Pesquisar</button>
            </form>

        </div>
    </div>


</div> <!-- Final da DIV content -->







<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
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
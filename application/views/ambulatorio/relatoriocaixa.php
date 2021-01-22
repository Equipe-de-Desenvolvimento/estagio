<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <div class="alert alert-primary"><a >Gerar relatorio Caixa</a></div>
        <div>
            <form method="post" action="<?= base_url() ?>ambulatorio/guia/gerarelatoriocaixa">
                <div>
                   <div>
                        <div>
                            <label>Operador</label>
                            <select name="operador[]" id="operador[]" class="chosen-select size3" multiple data-placeholder="Selecione">
                                <option value="0">TODOS</option>
                                <option value="1">Administrador</option>
                                <? foreach ($operadores as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <!-- <div>
                            <label>Médico</label>
                            <select name="medico" id="medico" class="size2">
                                <option value="0">TODOS</option>
                                <? foreach ($medicos as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </div> -->
                        <div>
                            <label>Grupo</label>
                            <select name="grupomedico" id="grupomedico" class="size2">
                                <option value="0">TODOS</option>
                                <? foreach ($grupomedico as $value) : ?>
                                    <option value="<?= $value->operador_grupo_id; ?>" ><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label>Convênio</label>
                            <select name="MostrarConvenio" id="MostrarConvenio" class="size2">
                                <option value="NAO">NÃO</option>
                                <option value="SIM">SIM</option>
                            
                            </select>
                        </div>
                    </div>
                    <div>   
                        <div>
                            <label>Data inicio</label>
                            <input type="text" name="txtdata_inicio" id="txtdata_inicio" alt="date"/>
                        </div>
                        <div>
                            <label>Data fim</label>
                            <input type="text" name="txtdata_fim" id="txtdata_fim" alt="date"/>
                        </div>
                        <div>
                            <label>Especialidade</label>
                            <select name="grupo" id="grupo" class="size2" >
                                <option value='0' >TODOS</option>
                                <option value='1' >SEM RM</option>
                                <? foreach ($grupos as $grupo) { ?>                                
                                    <option value='<?= $grupo->nome ?>' <?
                                    if (@$obj->_grupo == $grupo->nome):echo 'selected';
                                    endif;
                                    ?>><?= $grupo->nome ?></option>
                                        <? } ?>
                            </select>
                        </div>
                        <style>
                            #grupo_chosen a{
                                width: 180px;
                            }

                        </style>
                        <div>
                            <label>Procedimentos</label>
                            <select name="procedimentos" id="grupo" class="size4 chosen-select" tabindex="1">
                                <option value='0' >TODOS</option>
                                <!--<option value='1' >SEM RM</option>-->
                                <? foreach ($procedimentos as $grupo) { ?>                                
                                    <option value='<?= $grupo->procedimento_tuss_id ?>' <?
                                    if (@$obj->_grupo == $grupo->procedimento_tuss_id):echo 'selected';
                                    endif;
                                    ?>><?= $grupo->codigo ?> - <?= $grupo->nome ?></option>
                                        <? } ?>
                            </select>
                        </div>
                        <div>
                            <label>Gerar PDF / Planilha</label>
                            <select name="gerar" id="gerar" class="size2">
                                <option value='0' >NÃO</option>
                                <option value='pdf' >PDF</option>
                                <option value='planilha' >PLANILHA</option>
                            </select>
                        </div>
                        <?
                        $empresa_id = $this->session->userdata('empresa_id');
                        $perfil_id = $this->session->userdata('perfil_id');
    //                    var_dump($perfil_id); die;
                        ?>
                        <div>
                            <label>Empresa</label>
                            <select name="empresa" id="empresa" class="size2">
                                <? foreach ($empresa as $value) : ?>
                                    <? if (($gerente_relatorio_financeiro == 't' && ($perfil_id == 18 || $perfil_id == 20) && $empresa_id == $value->empresa_id) || $perfil_id != 18) { ?>
                                        <option value="<?= $value->empresa_id; ?>" <? if ($empresa_id == $value->empresa_id) { ?>selected<? } ?>><?php echo $value->nome; ?></option>
                                    <? } ?>
                                <? endforeach; ?>
                                <? if (($gerente_relatorio_financeiro == 't' && ($perfil_id == 18 || $perfil_id == 20) && $empresa_id == $value->empresa_id) || $perfil_id != 18) { ?>
                                    <option value="0">TODOS</option>
                                <? } ?>
                            </select>
                        </div>
                        <div>
                </div>

            </form>

        </div>
    </div>


</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
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

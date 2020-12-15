<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Gerar Manter Gastos Operador</a></h3>
        <div>
            <form method="post" action="<?= base_url() ?>ambulatorio/exame/gerarelatoriomantergastos">
                <dl>
                    <dt>
                        <label>Operador</label>
                    </dt>
                    <dd>
                        <select name="operador[]" id="operador[]" class="chosen-select size3" multiple data-placeholder="Selecione">
                            <option value="1">Administrador</option>
                            <? foreach ($operadores as $value) : ?>
                                <option value="<?= $value->operador_id; ?>" ><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <br>
                    <br>

                    <dt>
                        <label>Data inicio</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_inicio" id="txtdata_inicio" alt="date"/>
                    </dd>
                    <dt>
                        <label>Data fim</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_fim" id="txtdata_fim" alt="date"/>
                    </dd>

                    <style>
                        #grupo_chosen a{
                            width: 180px;
                        }

                    </style>

                    <dt>
                        <label>Status</label>
                    </dt>
                    <dd>
                        <select name="status" id="status" class="size2">
                            <option value='0' >Todos</option>
                            <option value='1' >Abertos</option>
                            <option value='2' >Finalizados</option>
                        </select>
                    </dd>

                    <dt>
                        <label>Gerar PDF / Planilha</label>
                    </dt>
                    <dd>
                        <select name="gerar" id="gerar" class="size2">
                            <option value='0' >NÃO</option>
                            <option value='pdf' >PDF</option>
                            <option value='planilha' >PLANILHA</option>
                        </select>
                    </dd>
                    <?
                    $empresa_id = $this->session->userdata('empresa_id');
                    $perfil_id = $this->session->userdata('perfil_id');
//                    var_dump($perfil_id); die;
                    ?>
                    <dt>
                        <label>Empresa</label>
                    </dt>
                    <dd>
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
                    </dd>
                    <dt>
                </dl>
                <button type="submit" >Pesquisar</button>

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

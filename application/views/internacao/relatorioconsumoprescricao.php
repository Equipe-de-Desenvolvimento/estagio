<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Gerar Relatório Consumo de Prescrição</a></h3>
        <div>
            <form method="post" action="<?= base_url() ?>internacao/internacao/gerarelatorioconsumoprescricao">
                <dl>
                    <dt>
                        <label>Operador</label>
                    </dt>
                    <dd>
                        <select name="operador[]" id="operador[]"  class="chosen-select size3" multiple data-placeholder="Selecione"  >
                            <option value="0">TODOS</option> 
                            <? foreach($operadores as $value){ ?>
                            <option value="<?= $value->operador_id; ?>"><?= $value->nome;?></option> 
                            
                            <?  } ?>
                        </select>
                    </dd>
                    <br>
                    <br>
                     <!-- <dt>
                        <label>Convênio</label>
                    </dt>
                    <dd>
                        <select name="convenio[]" id="convenio[]"  class="chosen-select size3" multiple data-placeholder="Selecione"  >
                            <option value="0">TODOS</option> 
                            <? foreach($convenio as $value){ ?>
                            <option value="<?= $value->convenio_id; ?>"><?= $value->nome;?></option>  
                            <?  } ?>
                        </select>
                    </dd>
                    <br>
                    <br> -->
                    <dt>
                        <label>Data inicio</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_inicio" id="txtdata_inicio" alt="date" required/>
                    </dd>
                    <dt>
                        <label>Data fim</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_fim" id="txtdata_fim" alt="date" required/>
                    </dd> 
                    <dt>
                        <label>Empresa</label>
                    </dt> 
                    <dd>
                        <select name="empresa" id="empresa" class="size2" required="">
                            <?php
                         
                            foreach ($empresa as $item) {
                                ?> 
                              <option  value ="TODAS" >
                                     TODAS
                                </option>
                                <option   value =<?php echo $item->empresa_id; ?> >
                                    <?php echo $item->nome; ?>
                                </option>

                            <? } ?> 

                        </select>
                    </dd> 
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

    $(function () {
        $("#txtCidade").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cidade",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtCidade").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtCidade").val(ui.item.value);
                $("#txtCidadeID").val(ui.item.id);
                return false;
            }
        });
    });

</script>
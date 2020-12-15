<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Gerar Relatório Ocorrência</a></h3>
        <div>
            <form name="form_paciente" id="form_paciente"  method="post" action="<?= base_url() ?>ambulatorio/guia/gerarelatorioocorrencia">
                <dl> 
                    
                   <dt>
                        <label>Operador</label>
                    </dt>
                    <dd>
                        <select name="operador" id="operador">
                        <option value="">Todos</option>
                            <?php 
                            foreach($operadores as $item){
                                ?>
                            <option value="<?= $item->operador_id; ?>"><?= $item->nome; ?></option>
                            <?
                            }
                            ?>
                    </select>
                    </dd>
                    <dt>
                        <label>Data inicio</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_inicio" id="periodo_inicio" alt="date" required=""/> 
                    </dd> 
                    <dt>
                        <label>Data Fim</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtdata_fim" id="periodo_fim" alt="date" required=""/>
                    </dd>  
                    <dt>
                        <label>Status</label>
                    </dt>
                    <dd>
                        <select name="status" id="status">
                            <option value="1">Finalizada</option>
                            <option value="2">Não finalizada</option>
                            <option value="">Todas</option>
                        </select>
                    </dd>  
                     
                    <dt>
                </dl> 
                <button type="submit" >Pesquisar</button> 
            </form>

        </div>
    </div>


</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
 

    $(function () {
        $("#periodo_inicio").datepicker({
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
        $("#periodo_fim").datepicker({
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
<div class="panel-body">
    <div class="alert alert-primary"> <b>Relatorio de Vagas Associadas</b></div>

    <form action="<?=base_url()?>cadastros/pacientes/gerarrelatoriovagasassociadas" method="post">
    
        <div class="row">
            <div class="col-lg-4">
                <label for="">Instituição</label>
                <select name="instituicao_id" id="" class="form-control">
                    <option value="">TODOS</option>
                        <?foreach($instituicao as $item){?>
                            <option value="<?=$item->instituicao_id?>"> <?=$item->nome_fantasia?></option>
                        <?}?>
                </select>
            </div>

            <div class="col-lg-2">
                <label for="">Data Inicial</label>
                <input type="text" name="data_inicio" id="data_inicio" class="form-control">
            </div>

            <div class="col-lg-2">
                <label for="">Data Final</label>
                <input type="text" name="data_final" id="data_final" class="form-control">
            </div>
        </div>

        <br>

        <button class="btn btn-outline-default btn-sm" type="submit">Gerar</button>
    </form>
</div>

<script>
 $(function () {
        $("#data_inicio").datepicker({
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
        $("#data_final").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });
</script>
<meta charset="UTF-8">
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Auditoria Laudo</h3>
        <div>
            <form name="form_faturar" id="form_faturar" action="<?= base_url() ?>ambulatorio/exame/gravarpacientedetalhes" method="post">
                <fieldset>

                    <table border="1">
                        <thead>
                            <tr>
                                <th class="tabela_header">Data</th>
                                <th class="tabela_header">Operador</th>
                                <th class="tabela_header">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($auditoria as $item) :
                                ?>
                                <tr>
                                    <td width="250px;"><?= date("d/m/Y H:i:s",strtotime($item->data_cadastro))?></td>
                                    <td width="400px;"><?= $item->operador ?></td>
                                    <td width="400px;"><?= $item->alteracao ?></td>
                                </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>
                    <hr/>
            </form>
            </fieldset>
        </div>
    </div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript">
    (function($) {
        $(function() {
            $('input:text').setMask();
        });
    })(jQuery);

</script>
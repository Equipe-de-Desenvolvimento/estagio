  
<html>
    <head>
        <title>Editar</title>
        <style>
            body{
                background-color: silver;
            }
        </style>
    </head>
    <body>
        <h2>Alterar data</h2>
        <form  method="post" action="<?= base_url()?>centrocirurgico/centrocirurgico/gravardataprocedimento"> 
            <table>
                <tr>
                    <td><input type="text" name="data" id="data" class="texto02" value="<?
                    if ($dados[0]->data != "1969-12-31" && $dados[0]->data != "") {
                       echo  date('d/m/Y',strtotime($dados[0]->data));
                    }
                    ?>" required=""/>
              </td>
                </tr>
                 <tr>
                     <td> <input type="submit" value="Enviar"> </td>
                </tr>
              
              </table>
            <input type="hidden" id="agenda_exames_id" name="agenda_exames_id" value="<?= $dados[0]->agenda_exames_id; ?>">
            
        </form>

    </body>
</html>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css"> 
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>


<script>
 $(function () {
        $("#data").datepicker({
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

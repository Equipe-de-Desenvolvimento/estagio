<meta charset='UTF-8'>
<style>
input{
    width: 300px;
}
</style>
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Paciente <br> <?=$paciente[0]->nome?></h3>
        <div>
        <?
        foreach($paciente as $item){
        ?>
        <form method="POST" action="<?=base_url()?>ambulatorio/laudo/salvaralteracaoemail/<?=$item->paciente_id?>"> 
            <table border='0'>

            <tr>
            <th>Email</th>
            <td><input type="text" id="txtCns" name="cns" onchange="validaremail()" value="<?=$item->cns?>"></td>
            </tr>

            <tr>
            <th>Email Alternativo</th>
            <td><input type="text" id="txtCns2" name="cns2" onchange="validaremail2()" value="<?=$item->cns2?>"></td>
            </tr>

            <tr>
            <td colspan="2" align="center"><br><br><button type="submit">Salvar</button></td>
            </tr>

            </table>
        </form>

        <?
         }
        ?>
        </div>
    </div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>

<script>
    function validaremail(){
        var email = $("#txtCns").val();
        var email2 = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns").val('');
                }else if(email == email2){
                    alert('O E-mail não pode ser Igual ao E-mail Alternativo');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function validaremail2(){
        var email2 = $("#txtCns").val();
        var email = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns2").val('');
                }else if(email == email2){
                    alert('O E-mail Alternativo não pode ser Igual ao E-mail');
                    $("#txtCns2").val('');
                }
            });
        }
    }
</script>
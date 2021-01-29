<html>
    <head>
        <title>Informações</title>
        <meta charset="utf-8">
        <style>
            body{
                background-color:silver;
            }
        </style>
    </head>
    <body>
        <table border=1 cellspacing=0 cellpadding=2 bordercolor="black">
            <?php
            foreach ($listas as $item) {
                $telefone = "(" . substr(@$item->telefone, 0, 2) . ")" . substr(@$item->telefone, 2, strlen(@$item->telefone) - 2);
                ?>
                <tr>
                    <td>Nome</td>
                    <td><?= $item->nome; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $item->email; ?></td>
                </tr>
                <tr>
                    <td>Telefone</td>
                    <td><?= $telefone; ?></td>
                </tr>
                <tr>
                    <td>Cidade</td>
                    <td><?= $item->municipio; ?></td>
                </tr>
                <?
            }
            ?>
        </table>

        <h3>Vizualizar Arquivos</h3>
        <div>
            <table>                  
                <!-- <tr>                        -->
                    <?
                    $arquivo_pasta = directory_map("./upload/arquivoestagio/$paciente_id");
                    if ($arquivo_pasta != false) {
                        sort($arquivo_pasta);
                    }
                    $i = 0;
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            foreach($value as $key => $item){
                               ?>
                                    <tr>
                                        <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url()?>upload/arquivoestagio/<?=$paciente_id?>/<?=$key?>/<?=$item?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"></td>
                                        <td><? echo substr($item, 0, 30) ?></td>
                                    </tr>
                               <? 
                            }
                            $i++;
                            ?> 
                            <!-- <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url()?>upload/arquivoestagio/<?=$paciente_id?>/<?=$value?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? //echo substr($value, 0, 10) ?><br> </td> -->
                            <?
                            if ($i == 8) {
                                ?>
                                <!--</tr><tr>-->
                                <?
                            }
                        }
                    }
                    ?>
                <!-- </tr>                     -->
            </table>
        </div> <!-- Final da DIV content -->
    </body>
</html>

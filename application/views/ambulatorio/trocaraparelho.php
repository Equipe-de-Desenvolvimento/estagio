<html>
    <head>
        <title>trocar</title>
        <style>
            body{
                background-color: silver;
            }
            td{
                font-family: arial;
            }
        </style>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        
        <form action="<?= base_url()?>ambulatorio/exame/gravartrocaaparelho" method="post">
            <table border="0" width="100%" >
                <tr>
                    <td><h3>Troca de aparelho</h3></td>
                </tr>
            <tr> 
             <input type="hidden" name="aparelho_gasto_sala_id" id="aparelho_gasto_sala_id"   class="texto01" value="<?= $aparelho_gasto_sala_id; ?>"/>
            </tr>
            <tr>
                     <td>
                         <select name="aparelho" id="aparelho" required="">
                             <option value="">Selecione</option>
                        <?php 
                        foreach($lista as $item){
                            ?>
                          <option value="<?= $item->fila_aparelhos_id; ?>"><?= $item->aparelho; ?></option> 
                            <?
                            }
                            ?>
                        </select>
                    </td>
            </tr>
            <tr>
                <td>
                    <textarea name="descricao" id="descricao" style="width: 100%;" placeholder="Descrição" required=""></textarea>
                </td>
            </tr>
            <tr> 
                <td>
                    
                    <input type="submit" value="Enviar">
                </td>
            </tr>
            </table>
        </form>

    </body>
</html>

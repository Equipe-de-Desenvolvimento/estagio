<html>

    <h3><center>Funcionario<center></h3>
    <table border="1" >
        <thead>
            <tr bgcolor ="gray">

                <th>Matr&iacute;cula</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Fun&ccedil;&atilde;o</th>
                <th>Setor</th>
                <th>Horario</th>
            </tr>

            <?php
                 foreach ($lista as $item) {
            ?>
                 <tr>
                    <td><?php echo $item->matricula; ?></td>
                    <td><?php echo ($item->nome); ?></td>
                    <td><?php echo ($item->cargo); ?></td>
                    <td><?php echo ($item->funcao); ?></td>
                    <td><?php echo ($item->setor); ?></td>
                    <td><?php echo ($item->horariostipo); ?></td>
                 </tr>

            <?php
                 }
            ?>
    </table>
<?php session_start() ?>
<?php $result = isset($_SESSION['result']) ? $_SESSION['result'] : ""; ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <header>
            <h1 id="title">Order Format</h1>
            <p>Ferramenta para ordenação de formatos de apresentação de dados.</p>
        </header>
        <main id="content">
            <form action="app/OrderFormat.php" method="post">
                <label for="format-content">Conteúdo ou URL</label>
                <br>
                <input type="checkbox" id="removeEmptyElements" name="removeEmptyElements" value="1">
                <label for="removeEmptyElements">Remover elementos vazios</label>
                <br>
                <input type="radio" name="order" value="asc" id="asc" checked>
                <label for="asc">Crescente</label>
                <input type="radio" name="order" value="desc" id="desc">
                <label for="desc">Decrescente</label>
                <br>
                <textarea name="format-content" id="format-content" cols="80" rows="20"></textarea>
                <textarea cols="80" rows="20"><?php echo $result; ?></textarea>
                <br>
                <input type="submit" value="Ordenar!">
            </form>
        </main>
    </body>
</html>
<?php unset($_SESSION['result']); ?>
<?php include 'template/header.php'?>
<?php
    if(empty($_SESSION['cLogin'])){
    ?>
        <script type="text/javascript">window.location.href="login.php"</script>
    <?php
    exit;
    }
?>
<div class="container">
    <h1>Meus Anúncios</h1>

    <a href="add-anuncio.php" class="btn btn-outline-dark">Adicionar Anúncio</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Titulo</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <?php 
        include 'class/anuncios.class.php';
        $a = new Anuncio();
        $anuncios = $a->getMeusAnuncios();

        foreach ($anuncios as $anuncio):
        ?>
        <tr>
            <td>
            <?php if(!empty($anuncio['url'])):?>
                <img src="assets/img/anuncios/<?php echo $anuncio['url'];?>"  height="100px" alt="res">
            <?php else:?>
                <img src="assets/img/anuncios/default.png" height="50px" alt="res">
            <?php endif;?>
            </td>
            <td><?php echo $anuncio['titulo']?></td>
            <td>R$<?php echo number_format($anuncio['valor'], 2)?></td>
            <td>
            <a href="editar-anuncio.php?id=<?php echo $anuncio['id'];?>" class="btn btn-outline-primary">Editar</a>
            <a href="excluir-anuncio.php?id=<?php echo $anuncio['id'];?>" class="btn btn-outline-danger">Excluir</a>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </table>

</div>


<?php include 'template/footer.php'?>
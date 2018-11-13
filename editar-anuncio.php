<?php include 'template/header.php'?>
<?php
    if(empty($_SESSION['cLogin'])){
    ?>
        <script type="text/javascript">window.location.href="login.php"</script>
    <?php
    exit;
    }

   
    include 'class/anuncios.class.php';
    if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
        $a = new Anuncio();
        $titulo = addslashes($_POST['titulo']);
        $categoria = addslashes($_POST['categoria']);
        $valor = addslashes($_POST['valor']);
        $descricao = addslashes($_POST['descricao']);
        $estado = addslashes($_POST['estado']);
        $id = intval(addslashes($_GET['id']));
        if(isset($_FILES['fotos'])){
            $fotos = $_FILES['fotos'];
        }else{
            $fotos = array();
        }  
    

        $a->editAnuncio($categoria, $titulo, $descricao, $valor, $estado, $fotos, $id);
        ?>
            <div class="alert alert-success">
                Produto editado com sucesso!
            </div>

        <?php
    }
    $info = array();
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $ad = new Anuncio();
        $id = $_GET['id'];
        $info = $ad->getAnuncio($id);
    } else {
        header("Location: meus-anuncios.php");
        exit;
    }

    
?>

<div class="container">
    <h1>Meus Anúncios - Editar Anúncio</h1>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="categoria" class="form-control">
                <?php
                include 'class/categorias.class.php';
                $c = new Categoria();
                $cats = $c->getLista();
                foreach($cats as $cat):             
                ?>
                    <option value="<?php echo $cat['id']?>"  <?php echo ($info['id_categoria'] ==  $cat['id'])?'selected="selected"':''; ?> ><?php echo utf8_encode($cat['nome']);?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $info['titulo'];?>">
        </div>

        <div class="form-group">
            <label for="valor">Valor:</label>
            <input type="text" name="valor" id="valor" class="form-control" value="<?php echo $info['valor'];?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" name="descricao" id="descricao" cols="30" rows="5"><?php echo $info['descricao'];?></textarea>
        </div>

        <div class="form-group">
            <label for="estado">Estado de Conservação:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="0" <?php echo ($info['estado'] == '0')?'selected="selected"':''; ?> >Ruim</option>
                <option value="1" <?php echo ($info['estado'] == '1')?'selected="selected"':''; ?> >Bom</option>
                <option value="2" <?php echo ($info['estado'] == '2')?'selected="selected"':''; ?> >Ótimo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="add_foto">Fotos do anúncio:</label><br>
            <input type="file" name="fotos[]" multiple /> <br><br>
            
        <div class="card">
            <div class="card-header">
                Fotos do Anúncio
            </div>
            <div class="card-body">
                <?php foreach ($info['fotos'] as $foto):
                ?>
                <div class="foto_item">
                    <img src="assets/img/anuncios/<?php echo $foto['url']; ?>" class="img-thumbnail" ><br>
                    <a href="excluir-foto.php?id=<?php echo $foto['id'];?>" class="btn btn-outline-danger">Excluir Imagem</a>
                </div>

                <?php endforeach;?>
        
            </div>
        </div>
        </div>

        <input type="submit" value="Salvar" class="btn btn-outline-primary">
    </form>

</div>


<?php include 'template/footer.php'?>
<?php
include 'connection/config.php';

if(empty($_SESSION['cLogin'])){
    header("Location: login.php");
    exit;
}


include 'class/anuncios.class.php';
$a = new Anuncio();

if(isset($_GET['id']) && !empty($_GET['id'])){
    
    $id = intval(addslashes($_GET['id']));
    
    $id_anuncio = $a->excluirFoto($id);

}

if(isset($id_anuncio)){
    header("Location: editar-anuncio.php?id=".$id_anuncio);
}else{
    header("Location: meus-anuncios.php");
}



?>
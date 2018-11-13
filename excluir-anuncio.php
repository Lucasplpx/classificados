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
    
    $a->excluirAnuncio($id);

}

header("Location: meus-anuncios.php");

?>
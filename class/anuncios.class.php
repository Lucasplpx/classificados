<?php
class Anuncio {

    public function getTotalAnuncios(){
        global $pdo;

        $sql = $pdo->query("SELECT COUNT(*) as c FROM anuncios");
        $row = $sql->fetch();

        return $row['c'];

    }

    public function getMeusAnuncios(){
        global $pdo;
        $array = array();

        $sql = $pdo->prepare("SELECT *, (select anuncios_imagens.url from anuncios_imagens where anuncios_imagens.id_anuncio = anuncios.id limit 1) as url FROM anuncios WHERE id_usuario = :id_usuario");
        $sql->bindValue(":id_usuario", $_SESSION['cLogin']);
        $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }

        return $array;

    }

    public function addAnuncio($id_categoria, $titulo, $descricao, $valor, $estado){
        global $pdo;

        $sql = $pdo->prepare("INSERT INTO anuncios (id_usuario, id_categoria, titulo, descricao, valor, estado) 
        VALUES (:id_usuario, :id_categoria, :titulo, :descricao, :valor, :estado)");
        $sql->bindValue(":id_usuario", $_SESSION['cLogin']);
        $sql->bindValue(":id_categoria", $id_categoria);
        $sql->bindValue(":titulo", $titulo);
        $sql->bindValue(":descricao", $descricao);
        $sql->bindValue(":valor", $valor);
        $sql->bindValue(":estado", $estado);
        $sql->execute();
    }

    public function editAnuncio($categoria, $titulo, $descricao, $valor, $estado, $fotos, $id){
        global $pdo;

        $sql = $pdo->prepare("UPDATE anuncios SET id_usuario = :id_usuario, id_categoria = :id_categoria, titulo = :titulo, descricao = :descricao, valor = :valor, estado = :estado WHERE id = :id");
        $sql->bindValue(":id_usuario", $_SESSION['cLogin']);
        $sql->bindValue(":id_categoria", $categoria);
        $sql->bindValue(":titulo", $titulo);
        $sql->bindValue(":descricao", $descricao);
        $sql->bindValue(":valor", $valor);
        $sql->bindValue(":estado", $estado);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if(count($fotos) > 0){
           for($q=0;$q<count($fotos['tmp_name']);$q++){
               $tipo = $fotos['type'][$q];
               if(in_array($tipo, array('image/jpeg', 'image/png'))){
                    $tmpname = md5(time().rand(0,9999)).'.jpg';
                    move_uploaded_file($fotos['tmp_name'][$q], 'assets/img/anuncios/'.$tmpname);

                    list($width_orig, $height_orig) = getimagesize('assets/img/anuncios/'.$tmpname);
                    $ratio = $width_orig/$height_orig;

                    $width = 500;
                    $height = 500;

                    if($width/$height > $ratio){
                        $width = $height*$ratio;
                    }else{
                        $height = $width/$ratio;
                    }

                    $img = imagecreatetruecolor($width, $height);

                    if($tipo == 'image/jpeg'){
                        $origi = imagecreatefromjpeg('assets/img/anuncios/'.$tmpname);
                    }else if($tipo == 'image/png'){
                        $origi = imagecreatefrompng('assets/img/anuncios/'.$tmpname);
                    }

                    imagecopyresampled($img, $origi, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

                    imagejpeg($img, 'assets/img/anuncios/'.$tmpname, 80);

                    $sql = $pdo->prepare("INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, url = :url");
                    $sql->bindValue(":id_anuncio", $id);
                    $sql->bindValue(":url", $tmpname);
                    $sql->execute();

               }
           }
        }
    }


    public function excluirAnuncio($id){
        global $pdo;

        $sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id_anuncio = :id_anuncio ");
        $sql->bindValue(":id_anuncio", $id);
        $sql->execute();

        $sql = $pdo->prepare("DELETE FROM anuncios WHERE id = :id ");
        $sql->bindValue(":id", $id);
        $sql->execute();


    }

    public function getAnuncio($id){
        global $pdo;
        $array = array();

        $sql = $pdo->prepare("SELECT * FROM anuncios WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0){
           $array = $sql->fetch();
           $array['fotos'] = array();

           $sql = $pdo->prepare("SELECT id,url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
           $sql->bindValue(":id_anuncio", $id);
           $sql->execute();

            if($sql->rowCount() > 0){
                $array['fotos'] = $sql->fetchAll();
            }

        }           
        
        return $array;
    }



    public function excluirFoto($id){
        global $pdo;
        $id_anuncio = 0;

        $sql = $pdo->prepare("SELECT id_anuncio FROM anuncios_imagens WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $row = $sql->fetch();
            $id_anuncio = $row['id_anuncio'];
        }

        $sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id = :id ");
        $sql->bindValue(":id", $id);
        $sql->execute();

        return $id_anuncio;
    }


}

?>
<?php
class Categoria{


    public function getLista(){
        global $pdo;
        $array = array();

        $sql = $pdo->query("SELECT * FROM categorias");
        if($sql->rowCount() > 0){   
            $array = $sql->fetchAll();
        }

        return $array;

    }

}

?>
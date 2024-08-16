<?php
    class Usuario extends Conectar{

        public function get_usuario($usu_dni){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario where usu_dni = ?;";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$usu_dni);
            $sql->execute();
            return $sql->fetchAll();
        }
    }
?>
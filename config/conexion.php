<?php
    class Conectar{
        protected $dbh;

        protected function conexion(){
            try{
                $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=u964384602_apibotzito","u964384602_botzito","B0tz1t0_D3v");
                return $conectar;
            }catch(Exception $e){
                print "Error BD:" . $e->getMessage() . "<br>";
                die();
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }
    }
?>
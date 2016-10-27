<?php
namespace Maude;
require_once("src/stdlib/algorithms.php");

abstract class ExistsinDeviceTableFunctor implements MaudeFunctor {

     private  $mdr_report_keys;   // sorted \Ds\Vector
 
     public function __construct(\PDO $pdo)
     {
        // If the table is empty, then set device_max_mdr_report_key to -1.
        $cnt_stmt = $pdo->query("SELECT count(*) FROM devicefoi");

        $count = (int) $cnt_stmt->fetchColumn();

        $this->mdr_report_keys = new \Ds\Vector(); // default size of zero.
 
        if ($count != 0) {

           $this->mdr_report_keys->allocate($count);
                      
           $stmt = $pdo->query("SELECT DISTINCT mdr_report_key from devicefoi ORDER BY mdr_report_key ASC");

           foreach($stmt as $mdr_report_key) {

                $this->mdr_report_keys->push($mdr_report_key);
           }
        } 
     }

     abstract function __invoke(\Ds\Vector $v) : bool;
     
     public function existsInDeviceTable($mdr_report_key) : bool
     {
         return binary_search($this->$mdr_report_keys, $mdr_report_key);
     }
     
} 

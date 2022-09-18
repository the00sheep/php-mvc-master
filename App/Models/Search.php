<?php

namespace App\Models;

use PDO;
use \App\Config;
use \App\Token;
use \Core\View;
use \App\Mail;
use \App\Paginator;
use Attribute;

/**
 * Error messages
 * 
 * @var array
 *
 */

$errors = [];


class Search extends \Core\Model
{
    public static function getSearchResults($data){
    
        $sql = "SELECT * FROM hotels WHERE name like '{$data}%'";
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
       //var_dump($stmt);
        
        
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }
}

?>
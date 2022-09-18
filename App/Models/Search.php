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
    public static function getSearchResults($name, $city, $minprice, $maxprice){
    
        $sql = "SELECT * FROM hotels WHERE name like '%{$name}%'";
        $sql2 = "SELECT * FROM hotels WHERE country like %{city}%'";
        $sql3 = "SELECT * FROM hotels WHERE country like %{country}%'";
        $sql4 = "SELECT * FROM hotels WHERE (vergeprice > {minprice}) && (verprice <{maxprice}'";


        $db = static::getDB();
        $stmt = $db->prepare($sql);
       //var_dump($stmt);
        
        
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }
}

?>
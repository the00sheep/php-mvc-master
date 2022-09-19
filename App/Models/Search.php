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
    public static function getSearchResults($name, $city, $country, $minprice, $maxprice){
    
       
        var_dump($minprice);
        var_dump($maxprice);
 
        
        $sql = "SELECT DISTINCT * FROM hotels WHERE name like '%{$name}%'";
        $sql2 = "SELECT * FROM hotels WHERE country like '%{$city}%'";
        $sql3 = "SELECT * FROM hotels WHERE country like '%{$country}%'";
        $sql4 = "SELECT * FROM hotels WHERE (vergeprice > {$minprice}) AND (vergeprice <{$maxprice})";
        
       $finalSql = "";
        
        if(!empty($city) || (!empty($country)) || (!empty($minprice)) || ($maxprice < 2000)) {
            $finalSql .= " SELECT DISTINCT * FROM  (".$sql.") AS tab_name,";
            $finalSql .= "(".$sql2.") AS tab_city,";
            $finalSql .= "(".$sql3.") AS tab_country,";
            $finalSql .= "(".$sql4.") AS tab_price";
            $finalSql .= " WHERE tab_name.hotel_id = tab_city.hotel_id AND tab_name.hotel_id = tab_country.hotel_id AND tab_name.hotel_id = tab_price.hotel_id  ";            
            //$finalSql .= $sql. " UNION ". $sql2 . " UNION ". $sql3 ." UNION " .$sql4;            
        }
        else{
            $finalSql = $sql;
        }
        var_dump($finalSql);

        
        //TBD $stmt->bind_param replace hardcoded
        
        //TBD get query based on inner joins sqls
        

        $db = static::getDB();
        $stmt = $db->prepare($finalSql);
       //var_dump($stmt);
        
        
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }
}

?>
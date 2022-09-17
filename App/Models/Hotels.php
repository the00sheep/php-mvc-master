<?php

namespace App\Models;

use PDO;
use \App\Config;
use \App\Token;
use \Core\View;
use \App\Mail;
use \App\Paginator;


class Hotels extends \Core\Model
{

    /**
   * Class constructor
   *
   * @param array $data  Initial property values
   *
   * @return void
   */
    public function __construct($data)
    {
       foreach ($data as $key => $value) {
            $this->$key = $value;
       };
    }

  
      /**
     * Get all hotels
     *
     * @return array  An array of all the hotel records
     */
    public static function getAllHotels()
    {
        $db = static::getDB();

        $sql = 'SELECT * FROM hotels
                ORDER BY name';

        $stmt = $db->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function save()
    {
     
        $sql = 'INSERT INTO hotels (name, address, city, zipcode, website, email, verge_price, status)
                VALUES (:name, :address, :city, :zipcode, :website, :email, :verge_price, :status)';
    
        $db = static::getDB();
        $stmt = $db->prepare($sql);
    
        $stmt->bindValue(':name',       $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':address',    $this->address, PDO::PARAM_STR);
        $stmt->bindValue(':city',       $this->city, PDO::PARAM_STR);
        $stmt->bindValue(':zipcode',    $this->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(':website',    $this->website, PDO::PARAM_STR);
        $stmt->bindValue(':email',      $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':verge_price',$this->verge_price, PDO::PARAM_STR);
        $stmt->bindValue(':status',     $this->status, PDO::PARAM_STR);
    
        $stmt->execute();
    }
}

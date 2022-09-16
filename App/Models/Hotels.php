<?php

namespace App\Models;

use PDO;

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
     * Get all the hotels as an associative array
     *
     * @return array
     */
    
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM hotels');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

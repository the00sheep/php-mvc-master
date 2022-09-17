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
    public static function getAllHotels($orderBy = 0)
    {
        
        //$columns =['name', 'address', 'city', 'country', 'zipcode', 'website', 'email', 'vergeprice'];

        $db = static::getDB();
        
       
        switch ($orderBy) {
            case "name":
                $sql = 'SELECT * FROM hotels
                        ORDER BY name';
                break;
            case "address":
                $sql = 'SELECT * FROM hotels
                        ORDER BY address';
                break;
            case "city":
                $sql = 'SELECT * FROM hotels
                        ORDER BY city';
                break;
            case 'country':
                $sql = 'SELECT * FROM hotels
                        ORDER BY country';
                break;
            case 'zipcode':
                $sql = 'SELECT * FROM hotels
                        ORDER BY zipcode';  
                break;
             case 'website':
                $sql = 'SELECT * FROM hotels
                        ORDER BY website';
                break;
            case 'email':
                $sql = 'SELECT * FROM hotels
                        ORDER BY email';
                  break;
            case 'vergeprice':
                $sql = 'SELECT * FROM hotels
                        ORDER BY vergeprice';     
                break;
            default:  $sql = 'SELECT * FROM hotels';
        }

              

        $stmt = $db->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

 
    public function save()
    {
        $this->validate();

        if (empty($this->errors)){
   
            //sql insert new hotel into hotels table
            $sql = 'INSERT INTO hotels (name, address, city, country, zipcode, website, email, vergeprice, status)
                VALUES (:name, :address, :city, :zipcode, :country, :website, :email, :vergeprice, 1)';
        
            $db = static::getDB();
            $stmt = $db->prepare($sql);
        
            $stmt->bindValue(':name',       $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':address',    $this->address, PDO::PARAM_STR);
            $stmt->bindValue(':city',       $this->city, PDO::PARAM_STR);
            $stmt->bindValue(':country',    $this->country, PDO::PARAM_STR);
            $stmt->bindValue(':zipcode',    $this->zipcode, PDO::PARAM_STR);
            $stmt->bindValue(':website',    $this->website, PDO::PARAM_STR);
            $stmt->bindValue(':email',      $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':vergeprice', $this->vergeprice, PDO::PARAM_STR);
            $stmt->bindValue(':status',     $this->status, PDO::PARAM_STR);
                

            //PDO method returns true on success, false on failure 
            return $stmt->execute();
        }
        //validation failed
        return false;
    }

    public static function deleteHotel($id, $active = 0){
             
        if ($active == 0){
        $sql = "UPDATE hotels 
                SET    status = 0
                WHERE  hotel_id ='{$id}'";
        }
        else {
            $sql = "UPDATE hotels 
            SET    status = 1
            WHERE  hotel_id ='{$id}'";
        }

        $db = static::getDB();   
        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }

 
    /**
     * validates current property
     * error gets added to $errors 
     * 
     * name, adress, city, country req
     * email valid
     * verge price > 0
     *  
     * @return void
     */


    public function validate()
    {
        //name required
        if ($this->name == ''){
            $this->error[] = 'Name is required'; 
        }

        if ($this->adress == ''){
            $this->error[] = 'Adress is required'; 
        }

        if ($this->city == ''){
            $this->error[] = 'City is required'; 
        }

        if ($this->country == ''){
            $this->error[] = 'Country is required'; 
        }

        //email validation using fiter_var method
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
            $this->errors[] = 'Invalid email';
        }

        //verge price > 0
        if($this->vergeprice < 0){
            $this->errors[] = 'Verge Price must be greater than 0';
        }

 
    }

    
    
  
}

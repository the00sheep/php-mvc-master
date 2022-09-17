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
            case "nameASC":
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, name';
                break;
            case "addressASC":
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, address';
                break;
            case "cityASC":
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, city';
                break;
            case 'countryASC':
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, country';
                break;
            case 'zipcodeASC':
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, zipcode';  
                break;
             case 'websiteASC':
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, website';
                break;
            case 'emailASC':
                $sql = 'SELECT * FROM hotels
                        ORDER BY status DESC, email';
                  break;
            case 'vergepriceASC':
                $sql = 'SELECT * FROM hotels
                       ORDER BY status DESC, vergeprice';     
                break;
            default:  $sql = 'SELECT * FROM hotels
                              ORDER BY status DESC';
        }

              

        $stmt = $db->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getHotel($id = 0)
    {    
        $db = static::getDB();
        
               
        $sql = "SELECT * FROM hotels  WHERE  hotel_id ='{$id}'";


        $stmt = $db->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

 
    public function save($edit = 0)
    {
        $this->validate();

        if (empty($this->errors)){
            
            
            //sql insert new hotel into hotels table
           if( $edit == 0){
                $sql = 'INSERT INTO hotels (name, address, city, country, zipcode, website, email, vergeprice, status)
                        VALUES             (:name, :address, :city, :country, :zipcode, :website, :email, :vergeprice, 1)';
            }
           else{
            $sql = "UPDATE hotels SET name       = :name,
                                      address    = :address,
                                      city       = :city,
                                      country    = :country,
                                      zipcode    = :zipcode,
                                      website    = :website,
                                      email      = :email,
                                      vergeprice = :vergeprice,
                                      status     = :status
                    WHERE hotel_id = '.$this->id.'";
           } 
           
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

            if( $edit == 1){
                $stmt->bindValue(':status',     $this->status, PDO::PARAM_STR);
            }

            //var_dump($stmt);
            //exit;
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

        if ($this->address == ''){
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

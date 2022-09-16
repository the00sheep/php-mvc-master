<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Error messages
     * 
     * @var array
     *
     */

     public $errors = [];
    
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
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Inserts a new user instance into the data base
     * @return bool 
    */    
    public function save()
    {
        //calls the validate function
        $this->validate();

        //if validate fct dosnt return any errors it moves to preparing the sql statement
        //else if the error string contains messages (validation failed), it returns false 
        if (empty($this->errors)){
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            //sql insert new user into users table
            $sql = 'INSERT INTO users (name, email, password_hash)
                    VALUES (:name, :email, :password_hash)';
        
            $db = static::getDB();
            $stmt = $db->prepare($sql);
        
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            

            //PDO method returns true on success, false on failure 
            return $stmt->execute();
        }
        //validation failed
        return false;
    }

    /**
     * validates current property
     * error gets added to $errors 
     * 
     * name req, valid email, passw at least 6 length 1 letter 1 nr
     *  
     * @return void
     */


    public function validate()
    {
        //name required
        if ($this->name == ''){
            $this->error[] = 'Name is required'; 
        }

        //email validation using fiter_var method
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
            $this->errors[] = 'Invalid email';
        }

        if (static::emailExists($this->email)){
            $this->errors[] = 'Email is taken';
        }

        //password and password confirmation are the same
        if($this->password != $this->password_confirmation){
            $this->errors[] = 'Passwords must match';
        }

        //min length of password 6
        if(strlen($this->password) < 6){
            $this->errors[] = 'Password must be at least 6 characters long';
        }

        //RegEx at least 1 letter in password
        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0){
            $this->errors[] = 'Password must contain at least 1 letter';
        }

        //RegEx at least 1 number in password
        if (preg_match('/.*\d+.*/i', $this->password) == 0){
            $this->errors[] = 'Password must contain at least 1 number';
        }

    }

    /**
     * Verifies if email is already used
     * 
     * @param string email address to search for
     * 
     * @return bool True if record already exists, false otherwise
     *  
     */

     public static function emailExists($email){
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        //fetch returns false if no record is found
        return $stmt->fetch() !== false;

     }
}


<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Hotels;


/**
 * Signup controller
 *
 * PHP version 7.0
 */
class hotelController extends \Core\Controller
{
  /**
   * Show the signup page
   *
   * @return void
   */

  public function newAction()
  {  
      $hotelList = Hotels::getAllHotels();
      //var_dump($hotelList);
      //exit();
      View::renderTemplate('Hotels/new.html', [
        'hotels' => $hotelList
    ]);
  }

  public function createAction()
  {
    $hotels = new Hotels($_POST);
   
    
    var_dump($_POST);
    //$hotels->save();

    //View::renderTemplate('Signup/success.html');

  }
}

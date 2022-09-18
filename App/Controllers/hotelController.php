<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Hotels;
use \App\Models\Search;


/**
 * Hotel controller
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
      View::renderTemplate('hotels/new.html', [
        'hotels' => $hotelList
    ]);
  }

  public function listAction($orderBy = 0){ 
        
    if(isset($_GET['orderBy']) && !empty($_GET['orderBy'])){
      $orderBy = $_GET['orderBy'];
    }
  
    $hotelList = Hotels::getAllHotels($orderBy);

    View::renderTemplate('hotels/list.html', [
      'hotels' => $hotelList,
   ]);
  }

  public function createAction()
  {
    $hotels = new Hotels($_POST);
   
    
    //var_dump($_POST);
   
    if($hotels->save()){
      
      //post /redirect / get pattern to avoid resubmission
      //303 See Other redirect status response code indicates that the redirects don't link to the requested resource itself, but to another page
      header('Location: http://' . $_SERVER['HTTP_HOST']. '/hotelController/success', true, 303);
      exit;

    }
    //else if validation fails render the new view, passing the error messages
    else{
      View::renderTemplate('Hotels/new.html', [
        'hotels' => $hotels
      ]);
    }    

  }

  public function editHotelAction(){

    
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
    }

    $hotels = Hotels::getHotel($id);

    View::renderTemplate('Hotels/edit.html', [
      'hotels' => $hotels[0]
    ]);
  }

  public function editSaveHotelAction(){
    
    $hotels = new Hotels($_POST);
    

    if($hotels->save(1)){
      
      //post /redirect / get pattern to avoid resubmission
      //303 See Other redirect status response code indicates that the redirects don't link to the requested resource itself, but to another page
      header('Location: http://' . $_SERVER['HTTP_HOST']. '/hotelController/success?edit=1', true, 303);
      exit;

    }
    //else if validation fails render the new view, passing the error messages
    else{
      View::renderTemplate('Hotels/edit.html', [
        'hotels' => $hotels
      ]);
    }    


  }

  /**
   * Soft delete, sets status to 0
   */

  /**
   * TBD error messages
   * merge the two functions
   * return to view with saved order preference
   * ajax, redirect
   */
  public function deleteHotelAction(){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
    }

    $deleteVal = Hotels::deleteHotel($id);

    $hotelList = Hotels::getAllHotels();
    if($deleteVal){
      View::renderTemplate('Hotels/list.html',[
        'hotels' => $hotelList,
     ]);
    }
    
  }

  public function activateHotel(){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
    }

    $activateVal = Hotels::deleteHotel($id, 1);

    $hotelList = Hotels::getAllHotels();
    if($activateVal){
      View::renderTemplate('Hotels/list.html',[
        'hotels' => $hotelList,
     ]);
    }
    
  }


  public function successAction()
  {
    $edit = 'adaugat';
    if(isset($_GET['edit']) && !empty($_GET['searchValue'])){
      $edit = 'editat';
    }

    View::renderTemplate('Hotels/success.html',[
    'edit' => $edit]);
  }


  public function search()
  {
      $data = "";
      $searchResults="";
      if(isset($_POST['searchValue'])){
          $data = $_POST['searchValue'];
      
      //var_dump($data);

      $searchResults = Search::getSearchResults($data);
      //var_dump($searchResults);
      View::renderTemplate('Home/search.html', [
        'searchResults' =>  $searchResults
    ]);
    }else{
      View::renderTemplate('Home/search.html', [
          'searchResults' =>  $searchResults
      ]);
    }
  }
  
}

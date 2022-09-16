<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;


/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{
  /**
   * Show the signup page
   *
   * @return void
   */

  public function newAction()
  {  
       View::renderTemplate('Signup/new.html');
  }

  
  public function createAction()
  {
    $user = new User($_POST);
    
    //var_dump($_POST);

    //if user validation is succesful it loads the succes.html view page
    if($user->save()){
      
      //post /redirect / get pattern to avoid resubmission
      //303 See Other redirect status response code indicates that the redirects don't link to the requested resource itself, but to another page

      header('Location: http://' . $_SERVER['HTTP_HOST']. '/signup/success', true, 303);
      exit;

    }
    //else if validation fails render the new view, passing the error messages
    else{
      View::renderTemplate('Signup/new.html', [
        'user' => $user
      ]);
    }
    
  }

  public function successAction()
  {
    View::renderTemplate('Signup/success.html');
  }


}

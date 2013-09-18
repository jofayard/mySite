<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
 
require 'vendor/autoload.php';
require 'Slim/Slim.php';

 /*  Go Get Redbean for Orm
 use RedBean_Facade as RedBean;  
*/

Twig_Autoloader::register();  
\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// GET route
$app->get('/', function () {  

/*      RedBean::setup('mysql:host=localhost;  
     dbname=test','user','password');  
*/
      $loader = new Twig_Loader_Filesystem('views');  
      $twig = new Twig_Environment($loader, array(  
        //'cache' => 'cache',  
      ));  

/*       $row = RedBean::getRow( 'SELECT * FROM book WHERE id = :id',   
     array(':id'=>7)   
   );  
*/
      echo $twig->render('home.twig.html', array());  

 });  

// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
    '/delete',
    function () {
        echo 'This is a DELETE route';
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

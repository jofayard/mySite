<?php
  
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Validator\Constraints as Assert;

// Require Vendors
require_once __DIR__.'/../vendor/autoload.php';

// Start App
$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/../vendor/Twig/lib',
));

$app['debug'] = true; //debugging
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'transport' => 'gmail',
    'username' => '',
    'password' => '',
);

//  ROUTES

//Home Page
$app->get('/', function (Request $request) use ($app) {
    return $app['twig']->render('home.twig.html', array(
        //empty array
    ));
});

//Contact Form
$app->match('/contact', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
        'message' => 'Your message',
    );

$form = $app['form.factory']->createBuilder('form')
    ->add('name', 'text', array(
        'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
    ))
    ->add('email', 'text', array(
        'constraints' => new Assert\Email()
    ))
    ->add('message', 'textarea', array(
        'constraints' => new Assert\NotBlank()
    ))
    ->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

          $message = \Swift_Message::newInstance()
            ->setSubject('[YourSite] Feedback')
            ->setFrom(array($data['email']))
            ->setTo(array())
            ->setBody($request->get('message'));

    $app['mailer']->send($message);

    return new Response("Thank you for your feedback! <pre> $message  </pre>", 201);
        }
    }
    // display the form
    return $app['twig']->render('contact.twig.html', array('form' => $form->createView()));
});


$app->run();

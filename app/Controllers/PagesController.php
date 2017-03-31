<?php

namespace App\Controllers;

use Respect\Validation\Validator;

class PagesController extends Controller {

    public function home($request, $response)
    {
        $this->render($response, 'pages/home.twig');
    }

    public function getContact($request, $response) {
        return $this->render($response, 'pages/contact.twig');
    }

    public function postContact($request, $response) {
        $errors = [];
        Validator::email()->validate($request->getParam('email')) || $errors['email'] = 'Invalid email';
        Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Name required';
        Validator::notEmpty()->validate($request->getParam('message')) || $errors['message'] = 'Message required';

        if (empty($errors)) {
            $message = \Swift_Message::newInstance('Message de contact')
            ->setFrom([$request->getParam('email') => $request->getParam('name')])
            ->setTo('contact@localhost')
            ->setBody("Email sent : {$request->getParam('content')}");
            $this->mailer->send($message);
            $this->flash('Message sent!');
            return $this->redirect($response, 'contact');
        } else {
            $this->flash('Fields error', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'contact', 400);
        }
    }

}

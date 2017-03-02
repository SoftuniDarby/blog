<?php

namespace Controllers;

use MVC\DefaultController;

class User extends DefaultController
{

    /* login functionality */

    public function login(){
        // this will display the user login form
        $this->view->render("user/login");
    }

    public function loginPost(){
        $input = $this->input; // we get the input array that contains all the get and post params

        // checking for login parameter in post request ..
        // if there is no such param that means that the wrong form was send or someone access the /user /registerPost url!
        // that means, we haveto redirect the user to the register form
        if ($input->post("login") == null) {
            $this->view->redirect("register");
        }

        $email = $input->post('email'); // get the email from  post request
        $password = $input->post('password'); // get the password from post request

        // check if the email is a valid email
        $this->validate->setRule('email', $email , null , "This is not an valid Email!");

        // check if password is more then 6 symbols
        $this->validate->setRule('minlength', $password , 6 , "Password must be more then 6 symbols!");

        // validate all filters and if there is an error .. we have to redirect and display the errors
        if ($this->validate->validate() === false) {
            $errors = $this->validate->getErrors();
            $this->view->redirect("login",$errors);
        }

        //if we are at this point of the code .. this means that everything is ok with our form information and we can register out user

        // to register the user we need an instance of UserModel
        $userModel = new \Models\UserModel();

        // we check if there is user with that username in database. If there is one we need to redirect to register form and display the error
        if (!$userModel->userExist($email)) {
            $error = "There is no such user registered!";
            $this->view->redirect("register",$error);
        }


        // if we are here in the code.. that means that there is no reason not to login the user .. so we do it :)

        // and we register the user FINALLY!!!
        // if everything is ok with the registration we redirect the user to the login page

        try{
            if ($userModel->authenticate($email,$password)) {
                $messageSuccess = "Registration completed successful.";

                $this->view->redirect("login",$messageSuccess, "success");
            }
        }catch (\Exception $exception){
            // if there is a problem with the registration we log the message and redirect to the registration page
            $message = $exception->getMessage();
            $this->view->redirect("register",$message);
        }
    }

    /* register functionality */

    public function register(){
        // this will display the user login form
        $this->view->render("user/register");

    }

    public function registerPost(){

        $input = $this->input; // we get the input array that contains all the get and post params

        // checking for register parameter in post request..
        // if there is no such param that means that the wrong form was send or someone access the /user /registerPost url!
        // that means, we haveto redirect the user to the register form
        if ($input->post("register") == null) {

            $this->view->redirect("register");
        }

        $email = $input->post('email'); // get the email from  post
        $password1 = $input->post('password'); // get the password 1 from post
        $password2 = $input->post('password2'); // get the password 2 from post

        // check if the email is a valid email
        $this->validate->setRule('email', $email , null , "This is not an valid Email!");
        // check if password1 matches password2 are the same
        $this->validate->setRule('matches', $password1 ,$password2, "Passwords are not the same!");
        // check if password is more then 6 symbols
        $this->validate->setRule('minlength', $password1 , 6 , "Password must be more then 6 symbols!");

        // validate all filters and if there is an error .. we have to redirect and display the errors
        if ($this->validate->validate() === false) {
            $errors = $this->validate->getErrors();
            $this->view->redirect("register",$errors);
        }

        //if we are at this point of the code .. this means that everything is ok with our form information and we can register out user

        // to register the user we need an instance of UserModel
        $userModel = new \Models\UserModel();

        // we check if there is user with that username in database. If there is one we need to redirect to register form and display the error
        if ($userModel->userExist($email)) {
            $error = "User with that name already exist!";
            $this->view->redirect("register",$error);
        }

        // if we are here in the code.. that means that there is no reason not to register the user .. so we do it :)
        // if everything is ok with the registration we redirect the user to the login page
        try{
            if ($userModel->tryRegisterUser($email,$email,$password1)) {
                $messageSuccess = "Registration completed successful.";

                $this->view->redirect("login",$messageSuccess, "success");
            }
        }catch (\Exception $exception){
            // if there is a problem with the registration we log the message and redirect to the registration page
            $message = $exception->getMessage();
            $this->view->redirect("register",$message);
        }



    }
}
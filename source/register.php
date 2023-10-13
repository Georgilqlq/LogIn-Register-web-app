<?php

include_once("utility.php");
include_once("../controllers/user.php");

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    $fields = [
        'username' => 'required | alphanumeric | between: 5, 30 | unique: username',
        'email' => 'required | email | unique: username, email',
        'password' => 'required | secure',
        'password2' => 'required | same: password',
    ];

    [$inputs, $errors] = filter($_POST, $fields);

    if ($errors) {
        //show message that some fields are invalid
    }

    $userInstance = new User();
    if ($userInstance->register_user($inputs['username'], $inputs['password'], $inputs['email'])) {
        //to do
        //redirect the user to another page
        //keep the user data in the session
    }

}
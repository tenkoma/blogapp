<?php
$world->getPathTo = function($path) use($world) {
    switch ($path) {
//  case 'TopPage': return '/';
    default: return $path;
    }
};
$world->getUser = function ($username) use ($world) {
    $users = ['会員'=>['username'=>'testuser', 'password'=>'secretkey']];
    $user = $world->getModel('Users.user')->findByUsername($users[$username]);
    $user['User']['password'] = $users[$username]['password'];
    return $user['User'];
};

<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Makiavelo\Flex\FlexRepository;
use Makiavelo\Flex\Flex;

$app = Quark::app();
FlexRepository::get()->connect('172.17.0.1', 'example_project', 'root', 'root');
session_start();

$app->use('/admin/.*', function(Request $req, Response $res) {
    if ($_SESSION['logged_in'] !== true) {
        $res->redirect('/');
    }
});

$app->get('/', function(Request $req, Response $res) {
    $layout = new View('../src/Views/login_layout.php');
    $content = $layout->fetch();

    $res->status(200)->send($content);
});

$app->get('/logout', function(Request $req, Response $res) {
    $_SESSION['logged_in'] = false;
    $res->redirect('/');
});

$app->post('/', function(Request $req, Response $res) {
    if ($req->param('user') === 'admin' && $req->param('pass') === 'admin') {
        $_SESSION['logged_in'] = true;
        $res->redirect('/admin/dashboard');
    } else {
        $res->redirect('/?user=' . $req->param('user'));
    }
});

$app->get('/admin/dashboard', function(Request $req, Response $res) {
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/home/index.php')
    ]);

    $res->status(200)->send($content);
});

$app->get('/admin/users', function(Request $req, Response $res) {
    $users = FlexRepository::get()->find('user');
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/list.php', ['users' => $users])
    ]);

    $res->status(200)->send($content);
});

$app->get('/admin/users/create', function(Request $req, Response $res) {
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/create.php')
    ]);

    $res->status(200)->send($content);
});

$app->post('/admin/users/create', function(Request $req, Response $res) {
    $repo = FlexRepository::get();
    $user = $repo->create('user');
    $user->name = $req->param('name');
    $user->last_name = $req->param('last_name');
    $repo->save($user);
    $res->redirect('/admin/users/edit/' . $user->id);
});

$app->get('/admin/users/edit/@id', function(Request $req, Response $res) {
    $params = [':id' => $req->param('id')];
    $users = FlexRepository::get()->find('user', 'id = :id', $params);
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/edit.php', ['user' => Flex::build($users[0])])
    ]);

    $res->status(200)->send($content);
});

$app->post('/admin/users/edit/@id', function(Request $req, Response $res) {
    $params = [':id' => $req->param('id')];
    $users = FlexRepository::get()->find('user', 'id = :id', $params);
    $user = $users[0];

    $user->setName($req->param('name'));
    $user->setLastName($req->param('last_name'));
    FlexRepository::get()->save($user);

    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/edit.php', ['user' => $user])
    ]);

    $res->status(200)->send($content);
});

$app->all('/.*', function(Request $req, Response $res) {
    $res->status(404)->send('Oops, page not found...');
});

$app->start();
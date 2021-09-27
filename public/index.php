<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Makiavelo\Flex\FlexRepository;

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

$app->all('/.*', function(Request $req, Response $res) {
    $res->status(404)->send('Oops, page not found...');
});

$app->start();
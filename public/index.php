<?php
include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Makiavelo\Quark\Util\Session;
use Makiavelo\Quark\Util\Cookies;
use Makiavelo\Flex\FlexRepository;
use App\Example\Models\Tag;
use App\Example\Routers\UserRouter;

$app = Quark::app();
FlexRepository::get()->connect([
    'host' => '172.17.0.1',
    'db' => 'example_project',
    'user' => 'root',
    'pass' => 'root'
]);

$app->all('/.*', function(Request $req, Response $res) {
    $session = Session::get();
    $session->start();
    $session->set('session_var', 'SomeValue222');

    $cookies = Cookies::get();
    if (!$cookies->param('welcome')) {
        $cookies->send([
            'name' => 'welcome',
            'value' => 'Hello!',
            'expires' => time()+60*60*24*30, // 30 days
            'path' => "/", // Available for all routes
            'domain' => "example.local",
        ]);

        $res->redirect($req->path());
    }
});

$app->use('/admin/.*', function(Request $req, Response $res) {
    if (Session::get()->param('logged_in') !== true) {
        $res->redirect('/');
    }
});

$app->get('/', function(Request $req, Response $res) {
    $layout = new View('../src/Views/login_layout.php');
    $content = $layout->fetch();

    $res->status(200)->send($content);
});

$app->get('/logout', function(Request $req, Response $res) {
    Session::get()->set('logged_in', false);
    $res->redirect('/');
});

$app->post('/', function(Request $req, Response $res) {
    if ($req->param('user') === 'admin' && $req->param('pass') === 'admin') {
        Session::get()->set('logged_in', true);
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

// Example using a router
$app->addRouter(new UserRouter('/admin/users'));

$app->get('/admin/tags', function(Request $req, Response $res) {
    $tags = FlexRepository::get()->find('tag');
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/tags/list.php', ['tags' => $tags])
    ]);

    $res->status(200)->send($content);
});

$app->get('/admin/tags/create', function(Request $req, Response $res) {
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/tags/create.php')
    ]);

    $res->status(200)->send($content);
});

$app->post('/admin/tags/create', function(Request $req, Response $res) {
    $repo = FlexRepository::get();
    $tag = new Tag();
    $tag->setName($req->param('name'));
    $repo->save($tag);
    $res->redirect('/admin/tags/edit/' . $tag->id);
});

$app->get('/admin/tags/edit/@id', function(Request $req, Response $res) {
    $params = [':id' => $req->param('id')];
    $tags = FlexRepository::get()->find('tag', 'id = :id', $params, ['class' => 'App\\Example\\Models\\Tag']);

    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/tags/edit.php', ['tag' => $tags[0]])
    ]);

    $res->status(200)->send($content);
});

$app->post('/admin/tags/edit/@id', function(Request $req, Response $res) {
    $params = [':id' => $req->param('id')];
    $tags = FlexRepository::get()->find('tag', 'id = :id', $params, ['class' => 'App\\Example\\Models\\Tag']);
    $tag = $tags[0];

    $tag->setName($req->param('name'));
    FlexRepository::get()->save($tag);

    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/tags/edit.php', ['tag' => $tag])
    ]);

    $res->status(200)->send($content);
});

$app->all('/.*', function(Request $req, Response $res) {
    $res->status(404)->send('Oops, page not found...');
});

$app->start();
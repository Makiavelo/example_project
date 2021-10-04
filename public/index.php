<?php
include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Makiavelo\Flex\FlexRepository;
use Makiavelo\Flex\Flex;
use App\Example\Models\Tag;
use App\Example\Models\User;

$app = Quark::app();
FlexRepository::get()->connect([
    'host' => '172.17.0.1',
    'db' => 'example_project',
    'user' => 'root',
    'pass' => 'root'
]);

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
    $users = FlexRepository::get()->find('user', 'id = :id', $params, ['class' => 'App\\Example\\Models\\User']);

    $tags = FlexRepository::get()->query('SELECT * FROM tag', [], ['class' => 'App\\Example\\Models\\Tag']);

    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/edit.php', ['user' => $users[0], 'tags' => $tags])
    ]);

    $res->status(200)->send($content);
});

$app->post('/admin/users/edit/@id', function(Request $req, Response $res) {
    $params = [':id' => $req->param('id')];
    $users = FlexRepository::get()->find('user', 'id = :id', $params, ['class' => 'App\\Example\\Models\\User']);
    $user = $users[0];

    $user->setName($req->param('name'));
    $user->setLastName($req->param('last_name'));

    $tags = [];
    if ($req->param('tags')) {
        $tags = FlexRepository::get()->find('tag', 'id IN ('. implode(',', $req->param('tags')) .')', [], ['class' => 'App\\Example\\Models\\Tag']);
    }
    $user->setTags($tags);

    FlexRepository::get()->save($user);
    $res->redirect('/admin/users/edit/' . $user->id);
});

$app->get('/admin/users', function(Request $req, Response $res) {
    $users = FlexRepository::get()->find('user');
    $layout = new View('../src/Views/layout.php');
    $content = $layout->fetch([
        'content' => new View('../src/Views/users/list.php', ['users' => $users])
    ]);

    $res->status(200)->send($content);
});

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
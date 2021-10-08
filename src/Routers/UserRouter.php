<?php

namespace App\Example\Routers;

use Makiavelo\Quark\Route;
use Makiavelo\Quark\Router;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Makiavelo\Flex\FlexRepository;

class UserRouter extends Router
{
    public function init()
    {
        $this->add(new Route([
            'path' => '/',
            'method' => 'GET',
            'callback' => function(Request $req, Response $res) {
                $users = FlexRepository::get()->find('user');
                $layout = new View('../src/Views/layout.php');
                $content = $layout->fetch([
                    'content' => new View('../src/Views/users/list.php', ['users' => $users])
                ]);
            
                $res->status(200)->send($content);
            }
        ]));

        $this->add(new Route([
            'path' => '/create',
            'method' => 'GET',
            'callback' => function(Request $req, Response $res) {
                $layout = new View('../src/Views/layout.php');
                $content = $layout->fetch([
                    'content' => new View('../src/Views/users/create.php')
                ]);
            
                $res->status(200)->send($content);
            }
        ]));

        $this->add(new Route([
            'path' => '/create',
            'method' => 'POST',
            'callback' => function(Request $req, Response $res) {
                $repo = FlexRepository::get();
                $user = $repo->create('user');
                $user->name = $req->param('name');
                $user->last_name = $req->param('last_name');
                $repo->save($user);
                $res->redirect('/admin/users/edit/' . $user->id);
            }
        ]));

        $this->add(new Route([
            'path' => '/edit/@id',
            'method' => 'GET',
            'callback' => function(Request $req, Response $res) {
                $params = [':id' => $req->param('id')];
                $users = FlexRepository::get()->find('user', 'id = :id', $params, ['class' => 'App\\Example\\Models\\User']);
            
                $tags = FlexRepository::get()->query('SELECT * FROM tag', [], ['class' => 'App\\Example\\Models\\Tag']);
            
                $layout = new View('../src/Views/layout.php');
                $content = $layout->fetch([
                    'content' => new View('../src/Views/users/edit.php', ['user' => $users[0], 'tags' => $tags])
                ]);
            
                $res->status(200)->send($content);
            }
        ]));

        $this->add(new Route([
            'path' => '/edit/@id',
            'method' => 'POST',
            'callback' => function(Request $req, Response $res) {
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
            }
        ]));

        $this->add(new Route([
            'path' => '',
            'method' => 'GET',
            'callback' => function(Request $req, Response $res) {
                $users = FlexRepository::get()->find('user');
                $layout = new View('../src/Views/layout.php');
                $content = $layout->fetch([
                    'content' => new View('../src/Views/users/list.php', ['users' => $users])
                ]);
            
                $res->status(200)->send($content);
            }
        ]));
    }
}
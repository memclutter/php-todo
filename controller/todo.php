<?php

namespace controller;

use memclutter\PhpTodo\Application;
use memclutter\PhpTodo\Controller;
use memclutter\PhpTodo\Response;
use memclutter\PhpTodo\Template;
use memclutter\PhpTodo\Todo as TodoActiveRecord;

class todo extends Controller
{
    /**
     * List all todos.
     */
    public function indexAction()
    {
        $template = new Template();
        $template->set('items', TodoActiveRecord::findAll());
        $template->layout()->content = $template->render('index.tpl.php');
        $content = $template->layout()->render();
        return new Response(200, $content);
    }

    /**
     * View detail todo by ID.
     * @param $id
     * @return Response
     */
    public function viewAction($id)
    {
        $item = TodoActiveRecord::find($id);
        if (!$item) {
            return new Response(404, "Todo item by ID {$id} not found.");
        }

        $template = new Template();
        $template->set('item', $item);
        $template->layout()->content = $template->render('view.tpl.php');
        $content = $template->layout()->render();
        return new Response(200, $content);
    }

    public function createAction()
    {
        $template = new Template();
        $template->set('caption', 'Create todo');
        $template->layout()->content = $template->render('form.tpl.php');
        $content = $template->layout()->render();
        return new Response(200, $content);
    }

    public function updateAction($id)
    {
        $item = TodoActiveRecord::find($id);
        if (!$item) {
            return new Response(404, "Todo item by ID {$id} not found.");
        }

        $template = new Template();
        $template->set('caption', 'Update todo #' . $item->id);
        $template->set('item', $item);
        $template->layout()->content = $template->render('form.tpl.php');
        $content = $template->layout()->render();
        return new Response(200, $content);
    }

    public function deleteAction($id)
    {
        $item = TodoActiveRecord::find($id);
        if (!$item) {
            return new Response(404, "Todo item by ID {$id} not found.");
        }

        // todo: delete active record
        return new Response(303, '', ['Location: /' . Application::getInstance()->router->reverse('todoIndex')]);
    }
}
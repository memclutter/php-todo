<?php

namespace controller;

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
        $content = $template->render('index.tpl.php');
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
        $content = $template->render('view.tpl.php');
        return new Response(200, $content);
    }
}
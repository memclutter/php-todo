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
        $request = Application::getInstance()->request;
        $values = $request->postParams;
        $errors = [];

        if ($request->isPost() && $this->validate($values, $errors)) {
            $todo = new TodoActiveRecord();
            $todo->fromArray($values);
            $todo->created = date('Y-m-d H:i:s');
            $todo->save();

            $location = Application::getInstance()
                ->router
                ->reverse('todoIndex');
            return new Response(307, '', ["Location: $location"]);
        }

        $template = new Template();
        $template->set('caption', 'Create todo');
        $template->set('values', $values);
        $template->set('errors', $errors);
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

        $request = Application::getInstance()->request;
        $values = $request->postParams;
        $errors = [];

        if ($request->isPost() && $this->validate($values, $errors)) {
            $item->fromArray($values);
            $item->updated = date('Y-m-d H:i:s');
            $item->save();

            $location = Application::getInstance()
                ->router
                ->reverse('todoView', ['id' => $item->id]);
            return new Response(307, '', ["Location: $location"]);
        }

        $template = new Template();
        $template->set('caption', 'Update todo #' . $item->id);
        $template->set('values', $values);
        $template->set('errors', $errors);
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
        $item->delete();

        $location = Application::getInstance()
            ->router
            ->reverse('todoIndex');
        return new Response(307, '', ["Location: $location"]);
    }

    private function validate($values, &$errors)
    {
        $statuses = TodoActiveRecord::statusLabels();
        $priorities = TodoActiveRecord::priorityLabels();

        if (!isset($values['text']) || empty($values['text'])) {
            $errors['text'] = 'Text required';
        }

        if (!isset($values['status']) || ($values['status'] === null) || ($values['status'] === '')) {
            $errors['status'] = 'Status required';
        } elseif (!isset($statuses[$values['status']])) {
            $errors['status'] = "Unknown status {$values['status']}.";
        }

        if (!isset($values['priority']) || ($values['priority'] === null) || ($values['priority'] === '')) {
            $errors['priority'] = 'Priority required';
        } elseif (!isset($priorities[$values['priority']])) {
            $errors['priority'] = "Unknown priority {$values['priority']}.";
        }

        return count($errors) == 0;
    }
}
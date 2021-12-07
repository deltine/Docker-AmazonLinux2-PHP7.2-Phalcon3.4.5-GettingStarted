<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Sample;

class SampleController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for sample
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Sample', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $sample = Sample::find($parameters);
        if (count($sample) == 0) {
            $this->flash->notice("The search did not find any sample");

            $this->dispatcher->forward([
                "controller" => "sample",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $sample,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a sample
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $sample = Sample::findFirstByid($id);
            if (!$sample) {
                $this->flash->error("sample was not found");

                $this->dispatcher->forward([
                    'controller' => "sample",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $sample->id;

            $this->tag->setDefault("id", $sample->id);
            $this->tag->setDefault("name", $sample->name);
            $this->tag->setDefault("age", $sample->age);
            $this->tag->setDefault("sample_date", $sample->sample_date);
            
        }
    }

    /**
     * Creates a new sample
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'index'
            ]);

            return;
        }

        $sample = new Sample();
        $sample->name = $this->request->getPost("name");
        $sample->age = $this->request->getPost("age");
        $sample->sampleDate = $this->request->getPost("sample_date");
        

        if (!$sample->save()) {
            foreach ($sample->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("sample was created successfully");

        $this->dispatcher->forward([
            'controller' => "sample",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a sample edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $sample = Sample::findFirstByid($id);

        if (!$sample) {
            $this->flash->error("sample does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'index'
            ]);

            return;
        }

        $sample->name = $this->request->getPost("name");
        $sample->age = $this->request->getPost("age");
        $sample->sampleDate = $this->request->getPost("sample_date");
        

        if (!$sample->save()) {

            foreach ($sample->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'edit',
                'params' => [$sample->id]
            ]);

            return;
        }

        $this->flash->success("sample was updated successfully");

        $this->dispatcher->forward([
            'controller' => "sample",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a sample
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $sample = Sample::findFirstByid($id);
        if (!$sample) {
            $this->flash->error("sample was not found");

            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'index'
            ]);

            return;
        }

        if (!$sample->delete()) {

            foreach ($sample->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sample",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("sample was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "sample",
            'action' => "index"
        ]);
    }

}

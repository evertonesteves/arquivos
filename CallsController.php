<?php

namespace CaosAdmin\Controller;

use Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Zend\Authentication\Storage\Session  as SessionStorage,
    Zend\Authentication\AuthenticationService;
use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;


class CallsController extends CRUDController
{
    private $user;
    public function __construct()
    {
        $this->entity = 'Caos\Entity\Call';
        $this->form = 'CaosAdmin\Form\Call';
        $this->service = 'Caos\Service\Call';
        $this->controller = 'calls';
        $this->route = 'Caos-admin';
        $auth = new AuthenticationService();
        $sessionStorage = new SessionStorage('CaosAdmin');
        $auth->setStorage($sessionStorage);
        $this->user = $sessionStorage->read();
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {

        $list = $this->getEm()->getRepository($this->entity)->findCallBySchool($this->user->getId());

        $page = $this->params()->fromRoute('page');
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(15);
        //echo '<pre>'; print_r($paginator);echo '</pre>';
        $viewModel = new ViewModel(array('data' => $paginator, 'page' => $page));
        $this->layout('layout/empty.phtml');
        //$viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     * @return JsonModel
     */
    /*public function indexAction()
    {
        $list = $this->getEm()->getRepository($this->entity)->findCallBySchool($this->user->getId());

        $page = $this->params()->fromRoute('page');
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(15);

        return new JsonModel(array('data' => $paginator, 'page' => $page));
    }*/


    public function newAction()
    {
        //          $form
        $form = $this->getServiceLocator()->get($this->form);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //                                             $service
                $service = $this->getServiceLocator()->get($this->service);
                $service->insert($request->getPost()->toArray());

                //                                $route                             $controller
                return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function editAction()
    {
        //           $form
        $form = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();

        //                                                $entity
        $repository = $this->getEm()->getRepository($this->entity);
        $entity = $repository->find($this->params()->fromRoute('id', 0));

        if ($this->params()->fromRoute('id', 0)) {
            $form->setData($entity->toArray());
        }
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //                                            $service
                $service = $this->getServiceLocator()->get($this->service);
                $service->update($request->getPost()->toArray());

                //                                  $route                             $controller
                return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function examinedAction()
    {
        $data = array(
            'id' => $this->params()->fromRoute('id', 0),
            'staff' => $this->user->getId(),
        );
        //                                            $service
        $service = $this->getServiceLocator()->get($this->service);
        if ($service->examined($data))
        {
            //                                 $route                            $controller
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }
    }

    public function closedAction()
    {
        //                                            $service
        $service = $this->getServiceLocator()->get($this->service);
        if ($service->closed($this->params()->fromRoute('id', 0)))
        {
            //                                 $route                            $controller
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }
    }
    
}
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserModel;
use AppBundle\Form\FormUser;
use AppBundle\Form\FormUserDelete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected $response;


    public function __construct()
    {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json');
    }


    /**
     * @Route("/api", name = "api")
     */
    public function indexAction()
    {
        die('xD');

    }

    /**
     * @Route("/api/all", name="api_get_all")
     */
    public function getAllUsersAction()
    {
        try {
            $serviceCache = $this->get('memcache.default');
            $model = new UserModel($serviceCache);
            $res = $model->getAllUsers();
        } catch (\Exception $ex) {
            $res = [];
        }
        $this->response->setContent(json_encode($res));
        return $this->response;
    }


    /**
     * @Route("/api/edit/{id}", name="api_edit_user", requirements={"id": "\d+"})
     */
    public function editUserAction(Request $request, $id)
    {
        $rs = [
            'success' => false
        ];

        if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {

            $user = new User();

            $form = $this->createForm(FormUser::class, $user, [
                'csrf_protection' => false
            ]);
            $form->submit($request->request->all());
            if ($form->isValid()) {

                $serviceCache = $this->get('memcache.default');
                $model = new UserModel($serviceCache);
                if ($model->saveUser($form->getData())) {
                    $rs['success'] = true;
                }

            } else {

                $errors = '';
                foreach ($form->getErrors(true) as $error)
                    $errors .= $error->getMessage() . '. ';

                $rs['error'] = $errors;
            }

        }

        $this->response->setContent(json_encode($rs));
        return $this->response;
    }

    /**
     * @Route("/api/add", name="api_add_user")
     */
    public function addUserAction(Request $request)
    {
        $rs = [
            'success' => false
        ];

        if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {

            $user = new User();
            $form = $this->createForm(FormUser::class, $user, [
                'csrf_protection' => false
            ]);

            $request->request->add(['started' => $user->getStarted() ]);
            $form->submit($request->request->all());

            if ($form->isValid()) {

                try {
                    $serviceCache = $this->get('memcache.default');
                    $model = new UserModel($serviceCache);
                    if ($model->saveUser($form->getData())) {
                        $rs['success'] = true;
                    }
                } catch (\Exception $ex) {
                    /// Logs
                    $rs['error'] = $ex->getMessage();
                }


            } else {
                $errors = '';
                foreach ($form->getErrors(true) as $error)
                    $errors .= $error->getMessage() . '. ';

                $rs['error'] = $errors;
            }
        }


        $this->response->setContent(json_encode($rs));
        return $this->response;

    }


    /**
     * @Route("/api/delete/{id}", name="api_delete_user", requirements={"id": "\d+"})
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $rs = [
            'success' => false
        ];

        if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
            $serviceCache = $this->get('memcache.default');
            $model = new UserModel($serviceCache);
            if ($user = $model->getUserById($id) ) {
                $user->setId($id);
                $form = $this->createForm(FormUserDelete::class, $user,[
                    'action' => $this->generateUrl('api_delete_user', [
                        'id' => $id
                    ]),
                     'csrf_protection' => false
                ]);

                $form->submit($request->request->all());
                if ($form->isValid()) {
                    $model->deleteUserById($id);
                    $rs['success'] = true;
                } else {

                    $errors = '';
                    foreach ($form->getErrors(true) as $error) {
                        $errors .= $error . '. ';
                    }

                    $rs['error'] = $errors;
                    exit;
                }
            }


        }

        $this->response->setContent(json_encode($rs));
        return $this->response;


    }



}

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

class AbmController extends Controller
{
    /**
     * @Route("/crud", name = "listUsers")
     */
    public function indexAction()
    {
        return $this->render('abm/index.html.twig');

    }

    /**
     * @Route("/crud/user/edit/{id}", name="editUser", requirements={"id": "\d+"})
     * @param Request $request
     */
    public function editAction(Request $request, $id)
    {

        if ($request->isMethod('GET') && $id) {

            $model = new UserModel($this->get('memcache.default'));
            $dataUser = $model->getUserById($id);
            if ($dataUser) {

                $form = $this->createForm(FormUser::class, $dataUser, [
                    'action' => $this->generateUrl('api_edit_user', [
                        'id' => $id
                    ])
                ]);

                $form->handleRequest($request);

                return $this->render('abm/edit.html.twig', [
                    'form' => $form->createView(),
                    'dataUser' => $form->getData()->getDump(true),
                    'subjectPage' => 'Editando usuario'
                ]);
            }
        }

        return $this->redirectToRoute('listUsers');


    }

    /**
     * @Route("/crud/user/add", name="addUser")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(FormUser::class, $user, [
            'action' => $this->generateUrl('api_add_user')
        ]);

        $form->remove('started');
        $form->add('started', HiddenType::class);


        return $this->render('abm/edit.html.twig', [
            'form' => $form->createView(),
            'subjectPage' => 'Nuevo usuario'
        ]);

    }


    /**
     * @Route("/crud/user/delete/{id}", name="deleteUser", requirements={"id": "\d+"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {
        if ($request->isMethod('GET') && $id) {

            $model = new UserModel($this->get('memcache.default'));
            $dataUser = $model->getUserById($id);
            if ($dataUser) {
                $dataUser->setId($id);
                $form = $this->createForm(FormUserDelete::class, $dataUser,[
                    'action' => $this->generateUrl('api_delete_user', [
                        'id' => $id
                    ])
                ]);


                return $this->render('abm/delete.html.twig', [
                    'form' => $form->createView(),
                    'id' => $dataUser->getId(),
                    'names' => $dataUser->getNames(),
                ]);
            }

        }

        return $this->redirectToRoute('listUsers');



    }

}

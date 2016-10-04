<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserModel;
use AppBundle\Form\FormUser;
use AppBundle\Form\FormUserDelete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json');
    }


    /**
     * Get all items registered
     *
     * @Route("/users", name="api_index")
     * @Method({"GET"})
     * @ApiDoc(
     *  resource=true,
     *  description="Get all items registered",
     *  filters={
     *      {"name"="a-filter", "dataType"="integer"},
     *      {"name"="another-filter", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}
     *  }
     * )
     *
     */
    public function indexAction()
    {
        try {
            $items = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findAll();
            $res = [];

            foreach ($items as $item) {
                $res[] = [
                    'id' => $item->getId(),
                    'names' => $item->getNames(),
                    'username' => $item->getUsername(),
                    'password' => $item->getPassword(),
                    'created_at' => $item->getCreatedAt(),
                    'email' => $item->getEmail(),
                    'status' => $item->getStatus(),
                ];
            }
        } catch (\Exception $ex) {
            $res = [];
        }
        return new JsonResponse($res);
    }

    /**
     * @Route("/users/", name="api_add_user")
     * @Method({"POST"})
     */
    public function addUserAction(Request $request)
    {
        $rs = ['success' => false];

        $user = new User();
        $form = $this->createForm(FormUser::class, $user, ['csrf_protection' => false]);
        $dataRequest = json_decode($request->getContent(), true);
        $dataRequest['created_at'] = date('Y-m-d H:i:s');
        $dataRequest['status'] = true;

        $form->submit($dataRequest);

        if ($form->isValid()) {
            $newUser = $form->getData();
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($newUser);
                $em->flush();

                $rs['success'] = true;

            } catch (\Exception $ex) {
                /// Logs
                $rs['error'] = $ex->getMessage();
            }


        } else {
            $errors = '';
            foreach ($form->getErrors(true) as $error)
                $errors .= $error->getMessage() . '. ';

            die($errors);
            $rs['error'] = $errors;
        }

        return new JsonResponse($rs);
    }

    /**
     * Endpoint for edit item
     *
     * @Route("/edit/{id}", name="api_edit_user", requirements={"id": "\d+"})
     * @Method({"POST"})
     */
    public function editUserAction(Request $request, $id)
    {
        $rs = ['success' => false];
        $doc = $this->getDoctrine();
        $itemToEdit = $doc->getRepository('AppBundle:User')->find($id);

        if ($itemToEdit) {
            try {
                $dataOriginal = $itemToEdit->toArray();

                $form = $this->createForm(FormUser::class, $itemToEdit, ['csrf_protection' => false]);
                $dataRequest = json_decode($request->getContent(), true);
                $dataRequest = array_replace($dataOriginal, $dataRequest);
                $form->submit($dataRequest);

                if ($form->isValid()) {

                    $dataToSave = $form->getData();
                    if (!$dataToSave->getStatus()) $dataToSave->setStatus(false);

                    $em = $doc->getManager();
                    $em->persist($dataToSave);
                    $em->flush();

                    $rs['success'] = true;

                } else {
                    $errors = '';
                    foreach ($form->getErrors(true) as $error)
                        $errors .= $error->getMessage() . '. ';
                    $rs['error'] = $errors;
                }

            } catch (\Exception $ex) {
                $rs['error'] = $ex->getMessage();
            }
        }


        return new JsonResponse($rs);
    }


    /**
     * @Route("/get/{id}", name="api_view_user", requirements={"id": "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $rs = [];

        try {
            $rs = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id)
                ->toArray();

        } catch (\Exception $ex) {
            $rs['error'] = $ex->getMessage();
        }

        return new JsonResponse($rs);


    }



    /**
     * @Route("/delete/{id}", name="api_delete_user", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $rs = ['success' => false];
        $doc = $this->getDoctrine();
        $itemToDelete = $doc->getRepository('AppBundle:User')->find($id);

        if ($itemToDelete) {
            try {
                $em = $doc->getManager();
                $em->remove($itemToDelete);
                $em->flush();
                $rs['success'] = true;
            } catch (\Exception $ex) {
                $rs['error'] = $ex->getMessage();
            }
        }

        return new JsonResponse($rs);
    }



}

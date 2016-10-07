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
     *
     * @Route("/users")
     * @Route("/users/{id}/details")
     * @Route("/users/{id}")
     * @Route("/users/add")
     * @Method({"OPTIONS"})
     *
     */
    public function requestHeaderOptions()
    {
        return new JsonResponse();
    }

    /**
     *
     * @Route("/users", name="api_index")
     * @Method({"GET"})
     * @ApiDoc(
     *  resource=true,
     *  description="Get all items registered"
     * )
     *
     */
    public function indexAction(Request $request)
    {
		$res = [];
		
        try {
            if ($hPag = $request->headers->get('pagination')) {
                $arPag = explode(',', $hPag);
            } else {
                $arPag = [1,20];
            }

            $items = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->getListItems($arPag[0], $arPag[1]);

            $optHeader = [
				'access-control-expose-headers' => 'Pagination',
				'Pagination' => json_encode([
					'CurrentPage' => $arPag[0],
					'ItemsPerPage' => $arPag[1],
					'TotalItems' => $items['total'],
					'TotalPages' => round($items['total'] / $arPag[1])
				])
			];
			
			
			foreach ($items['result'] as $item) {
				$res[] = [
					'id' => $item['id'],
					'names' => $item['names'],
					'email' => $item['email'],
					'status' => $item['status'],
				];
			}
			
			
			
			
        } catch (\Exception $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], 500);
        }
		
        return new JsonResponse($res, 200, $optHeader);
    }

    /**
     * @Route("/users/add", name="api_add_user")
     * @Method({"PUT"})
     * @ApiDoc(
     *  resource=true,
     *  description="Add new user"
     * )
     *
     */
    public function addUserAction(Request $request)
    {
        $rs = ['success' => false];
        $dataRequest = json_decode($request->getContent(), true);

        if (is_array($dataRequest)) {
            $user = new User();
            $form = $this->createForm(FormUser::class, $user, ['csrf_protection' => false]);
            $dataRequest['created_at'] = (new \DateTime($dataRequest['created_at']))->format('Y-m-d H:i:s');
            $dataRequest['status'] = ( $dataRequest['status'] == 'Enabled');

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
                $rs['error'] = $errors;
            }
        }

        return new JsonResponse($rs);
    }

    /**
     *
     * @Route("/users/{id}", name="api_edit_user", requirements={"id": "\d+"})
     * @Method({"PUT"})
     * @ApiDoc(
     *  resource=true,
     *  description="Endpoint for edit item"
     * )
     *
     */
    public function editUserAction(Request $request, $id)
    {
        $rs = ['success' => false];
        $doc = $this->getDoctrine();
        $itemToEdit = $doc->getRepository('AppBundle:User')->find($id);

        if ($itemToEdit) {
            try {
                $dataOriginal = $itemToEdit->toArray();
                $dataRequest = json_decode($request->getContent(), true);

                if (is_array($dataRequest)) {
                    $form = $this->createForm(FormUser::class, $itemToEdit, ['csrf_protection' => false]);

                    $dataRequest = array_replace($dataOriginal, $dataRequest);
                    $dataRequest['status'] = ( $dataRequest['status'] == 'Enabled');
                    $dataRequest['created_at'] = (new \DateTime($dataRequest['created_at']))->format('Y-m-d H:i:s');


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
                }


            } catch (\Exception $ex) {
                $rs['error'] = $ex->getMessage();
            }
        }


        return new JsonResponse($rs);
    }

    /**
     * @Route("/init/all", name="init_data")
     * @Method({"GET", "OPTIONS"})
     * @ApiDoc(resource=true, description="Get initial data from database")
     */
    public function getInitData()
    {
        return new JsonResponse([
            'statuses' => [
                'Enabled',
                'Disabled'
            ]
        ]);

    }


    /**
     * @Route("/users/{id}/details", name="api_view_user", requirements={"id": "\d+"})
     * @Method({"GET"})
     * @ApiDoc(
     *  resource=true,
     *  description="Get details from user registered"
     * )
     */
    public function getAction($id)
    {
        $rs = [];

        try {
            $rs = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id)
                ->toArray();

            if (isset($rs['status'])) {
                $rs['status'] = ($rs['status'] ? 'Enabled' : 'Disabled');
                $rs['statuses'] = ['Enabled', 'Disabled'];
            }

        } catch (\Exception $ex) {
            $rs['error'] = $ex->getMessage();
        }

        return new JsonResponse($rs);


    }



    /**
     * @Route("/users/{id}", name="api_delete_user", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     * @ApiDoc(
     *  resource=true,
     *  description="Delete user from database"
     * )
     *
     */
    public function deleteAction($id)
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

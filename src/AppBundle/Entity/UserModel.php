<?php
/**
 * Created by PhpStorm.
 * User: denis.arosquipa
 * Date: 20/09/2016
 * Time: 12:16 PM
 */

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Lsw\MemcacheBundle\Cache\AntiDogPileMemcache;

class UserModel
{
    protected $service;

    /**
     * UserModel constructor.
     * @param AntiDogPileMemcache $service
     */
    public function __construct(AntiDogPileMemcache $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public  function getAllUsers()
    {
        $dataActual = $this->service->get('data');
        if (false === $dataActual) return [];

        $res = [];
        foreach ($dataActual['items'] as $id => $user) {
            $dataUser = \unserialize($user);
            $res[] = [
                'id' => $id,
                'names' => $dataUser->getNames(),
                'email' => $dataUser->getEmail(),
                'status' => ($dataUser->getStatus() ? 'Activo' : 'Inactivo'),
                'started' => is_string($dataUser->getStarted()) ?
                    $dataUser->getStarted() : $dataUser->getStarted()->format('Y-m-d H:i:s'),
            ];
        }
        return $res;
    }

    /**
     * @param User $pUser
     * @return bool
     */
    public function saveUser(User $pUser)
    {
        $dataActual = $this->service->get('data');
        $idUser = $pUser->getId();

        if (false === $dataActual) {
            $dataActual = [
                'total' => 1,
                'items'=> [
                    1 => \serialize($pUser)
                ]
            ];
        } else {
            if ($idUser != null) { // Edicion
                $dataActual['items'][$idUser] = \serialize($pUser);
            } else { // Adicion
                $dataActual['total']++;
                $pUser->setId($dataActual['total']);
                $dataActual['items'][$dataActual['total']] = \serialize($pUser);
            }
        }

        $this->service->set('data', $dataActual);
        return true;
    }


    /**
     * @param $idUser
     * @return bool
     */
    public function getUserById($idUser)
    {
        $dataActual = $this->service->get('data');
        if ($dataActual) {
            return \unserialize($dataActual['items'][$idUser]);
        }
        return false;
    }


    /**
     * @param $idUser
     * @return bool
     */
    public function deleteUserById($idUser)
    {
        $dataActual = $this->service->get('data');
        if ($dataActual) {

            if ($key = array_search($idUser, array_keys($dataActual['items'])) ) {
                unset($dataActual['items'][$idUser]);
                $this->service->set('data', $dataActual);
                return true;
            }

        }

        return false;
    }




}
<?php
/**
 * Created by PhpStorm.
 * User: denis.arosquipa
 * Date: 19/09/2016
 * Time: 12:46 PM
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Valida;

class User
{

    protected $id;

    /**
     * @Valida\NotBlank()
     */
    protected $names;

    /**
     * @Valida\NotBlank()
     */
    protected $username;

    /**
     * @Valida\NotBlank()
     */
    protected $password;

    /**
     * @Valida\NotBlank()
     * @Valida\Date(
     *     message="La fecha no es valida"
     * )
     */
    protected $started;

    protected $status;


    /**
     * @Valida\NotBlank()
     * @Valida\Email(
     *     message="El correo no es valido"
     * )
     */
    protected $email;



    public function  __construct()
    {
        $this->started = date('Y-m-d');
        $this->status = true;
        $this->id = null;
    }


    /**
     * @return mixed
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setNames($lastName)
    {
        $this->names = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param mixed $dateRegistered
     * @return User
     */
    public function setStarted($dateRegistered)
    {
        $this->started = $dateRegistered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getDump($array = false)
    {
        $createdAt = $this->getStarted();
        if (!is_string($createdAt)) {
            $createdAt = $createdAt->format('Y-m-d H:i:s');
        }

        $res = [
            'id' => $this->getId(),
            'names' => $this->getNames(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'status' => $this->getStatus() ? 1 : 0,
            'started' => $createdAt,
            'password' => $this->getPassword(),
        ];

        if (!$array) $res = json_decode(json_encode($res));

        return $res;

    }



}
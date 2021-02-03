<?php

namespace App\Service;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encode;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminService constructor.
     * @param UserPasswordEncoderInterface $encode
     * @param EntityManagerInterface $em
     */
    public function __construct(UserPasswordEncoderInterface $encode, EntityManagerInterface $em)
    {
        $this->encode = $encode;
        $this->em = $em;
    }

    /**
     * permet d'enregistrer en bdd un admin
     *
     * @param array $data les données du formulaire
     * @return Admin
     */
    public function createAdmin(array $data)
    {
        $admin = new Admin();
        $admin->setUsername($data['username']);
        $admin->setPassword(
            $this->encode->encodePassword(
                $admin,
                $data['password']
            )
        );

        $this->em->persist($admin);
        $this->em->flush();

        return $admin;
    }
}
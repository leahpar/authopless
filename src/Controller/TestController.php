<?php

namespace App\Controller;

use App\Entity\PublicKeyCredentialSource;
use App\Entity\User;
use App\Repository\PublicKeyCredentialSourceRepository;
use App\Repository\PublicKeyCredentialUserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function profile(
        PublicKeyCredentialUserEntityRepository $publicKeyCredentialUserEntityRepository,
        PublicKeyCredentialSourceRepository $publicKeyCredentialSourceRepo)
    {
        /** @var User $user */
        $user = $this->getUser();

        $keys = $publicKeyCredentialSourceRepo->findAllForUserEntity(
            $publicKeyCredentialUserEntityRepository->getUserEntity($user)
        );

        return $this->render('test/index.html.twig', [
            'keys' => $keys,
        ]);
    }

}

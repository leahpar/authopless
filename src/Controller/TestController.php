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
    public function test(
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

    #[Route('/profile/security/devices/{id}/del', name: 'del_device')]
    public function del(Request $request, PublicKeyCredentialSourceRepository $publicKeyCredentialSourceRepo)
    {
        $key = $publicKeyCredentialSourceRepo->findOneById($request->get('id'));
        $publicKeyCredentialSourceRepo->remove($key);

        return $this->redirectToRoute('index');
    }

}

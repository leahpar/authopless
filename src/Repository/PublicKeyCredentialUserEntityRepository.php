<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\Uid\Ulid;
use Webauthn\Bundle\Repository\PublicKeyCredentialUserEntityRepository
    as PublicKeyCredentialUserEntityRepositoryInterface;
use Webauthn\PublicKeyCredentialUserEntity;

final class PublicKeyCredentialUserEntityRepository
    implements PublicKeyCredentialUserEntityRepositoryInterface
{
    /**
     * The UserRepository $userRepository is the repository
     * that already exists in the application
     */
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * This method creates the next Webauthn User Entity ID
     * In this example, we use Ulid
     */
    public function generateNextUserEntityId(): string {
        return Ulid::generate();
    }

    /**
     * This method saves the user or does nothing if the user already exists.
     * It may throw an exception. Just adapt it on your needs
     */
    public function saveUserEntity(PublicKeyCredentialUserEntity $userEntity): void
    {
        /** @var User|null $user */
        $user = $this->userRepository->findOneBy([
            'id' => $userEntity->getId(),
        ]);
        if ($user) return;

        $user = new User(
            $userEntity->getId(),           // UUID
            $userEntity->getDisplayName(),  // jean Michel
            $userEntity->getName(),         // jean.michel@lycos.fr
        );
        $this->userRepository->save($user, true); // Custom method to be added in your repository
    }

    public function findOneByUsername(string $username): ?PublicKeyCredentialUserEntity
    {
        /** @var User|null $user */
        $user = $this->userRepository->findOneBy([
            'email' => $username,
        ]);

        return $this->getUserEntity($user);
    }

    public function findOneByUserHandle(string $userHandle): ?PublicKeyCredentialUserEntity
    {
        /** @var User|null $user */
        $user = $this->userRepository->findOneBy([
            'id' => $userHandle,
        ]);

        return $this->getUserEntity($user);
    }

    /**
     * Converts a Symfony User (if any) into a Webauthn User Entity
     */
    public function getUserEntity(null|User $user): ?PublicKeyCredentialUserEntity
    {
        if ($user === null) {
            return null;
        }

        return new PublicKeyCredentialUserEntity(
            $user->getUsername(),           // jean.michel@lycos.fr
            $user->getUserIdentifier(),     // UUID
            $user->getDisplayName(),        // jean Michel
            null
        );
    }
}


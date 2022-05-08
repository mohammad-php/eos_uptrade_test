<?php declare(strict_types=1);

namespace App\Adapter\DataSource;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DatabaseAdapter implements DataSourceAdapterInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return User
     */
    public function get(int $id): User
    {
        /**
         * @var User $user
         */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User Not Found!');
        }
        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $entities = $this->entityManager->getRepository(User::class)->findAll();
        $users = [];
        foreach($entities as $entity)
        {
            $users [] = [
                'id' => $entity->getId(),
                'username' => $entity->getUsername(),
                'email' => $entity->getEmail()
            ];
        }
        return $users;
    }

    /**
     * @param array $payload
     * @return User
     */
    public function add(array $payload): User
    {
        $user = new User();
        $user->setUsername($payload['username']);
        $user->setEmail($payload['email']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param array $payload
     * @return User
     * @var User $user
     */
    public function update(array $payload): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($payload['id']);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $user->setUsername($payload['username']);
        $user->setEmail($payload['email']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param array $payload
     * @return bool
     */
    public function delete(array $payload): bool
    {
        $user = $this->entityManager->getRepository(User::class)->find($payload['id']);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return true;
    }
}
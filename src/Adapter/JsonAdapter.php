<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Entity\User;
use JsonException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JsonAdapter implements DataSourceAdapterInterface
{
    public function __construct(private string $file)
    {
    }

    public function get(int $id): User
    {
        $users = $this->getFromFile();
        if(in_array($id, array_column($users, 'id')))
        {
            $userIndex = array_search($id, array_column($users, 'id'));
            $userData = $users[$userIndex];
            return $this->initUserEntity($userData);
        }
        throw new NotFoundHttpException('User Not Found!');
    }

    /**
     * @return User[]
     * @throws JsonException
     */
    public function getAll(): array
    {
        $content = $this->getFromFile();

        $users = [];
        foreach ($content as $userData) {
            $user = $this->initUserEntity($userData);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * @param array $payload
     * @return User
     * @throws JsonException
     */
    public function add(array $payload): User
    {
        $content = $this->getFromFile();
        $userId = (int) (!empty($content)) ? max(array_column($content, 'id')) + 1 : 1;
        $payload['id'] = $userId;
        $user = $this->initUserEntity($payload);
        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ];
        $content[] = $userData;
        $this->writeToFile($content);
        return $user;
    }

    /**
     * @param array $payload
     * @return User
     * @throws JsonException
     */
    public function update(array $payload): User
    {
        $content = $this->getFromFile();
        $userId = $payload['id'];
        if(in_array($userId, array_column($content, 'id')))
        {
            $userIndex = array_search($userId, array_column($content, 'id'));
            $payload['id'] = $userId;
            $user = $this->initUserEntity($payload);
            $userData = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ];
            $content[$userIndex] = $userData;
            $this->writeToFile($content);
            return $user;
        }
        throw new NotFoundHttpException('User Not Found!');
    }

    /**
     * @param array $payload
     * @return bool
     * @throws JsonException
     */
    public function delete(array $payload): bool
    {
        $content = $this->getFromFile();
        if(in_array($payload['id'], array_column($content, 'id')))
        {
            $userIndex = array_search($payload['id'], array_column($content, 'id'));
            unset($content[$userIndex]);
            $this->writeToFile(array_values($content));
            return true;
        }
        throw new NotFoundHttpException('User Not Found!');
    }

    /**
     * @throws JsonException
     */
    private function writeToFile(array $users): void
    {
        $content['users'] = $users;
        $content = json_encode($content, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($this->file, $content);
    }


    /**
     * @return array
     */
    private function getFromFile(): array
    {
        $fileContent = file_get_contents($this->file);
        $content = json_decode($fileContent, true);
        return (!empty($content['users'])) ? $content['users'] : [];
    }

    /**
     * @param array $data
     * @return User
     */
    private function initUserEntity(array $data): User
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        return $user;
    }
}

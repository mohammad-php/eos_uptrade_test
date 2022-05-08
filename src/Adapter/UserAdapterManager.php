<?php

namespace App\Adapter;

use App\Entity\User;
use App\Adapter\DataSource\DataSourceAdapterInterface;

class UserAdapterManager
{
    /**
     * @var DataSourceAdapterInterface
     */
    private DataSourceAdapterInterface $dataSourceAdapter;


    /**
     * @param DataSourceAdapterInterface $adapter
     */
    public function __construct(DataSourceAdapterInterface $adapter)
    {
        $this->dataSourceAdapter = $adapter;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUser(int $id): User
    {
        return $this->dataSourceAdapter->get($id);
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->dataSourceAdapter->getAll();
    }

    /**
     * @param array $payload
     * @return User
     */
    public function addUser(array $payload): User
    {
        return $this->dataSourceAdapter->add($payload);
    }

    /**
     * @param array $payload
     * @return User
     */
    public function updateUser(array $payload): User
    {
        return $this->dataSourceAdapter->update($payload);
    }

    /**
     * @param array $payload
     * @return bool
     */
    public function deleteUser(array $payload): bool
    {
        return $this->dataSourceAdapter->delete($payload);
    }
}
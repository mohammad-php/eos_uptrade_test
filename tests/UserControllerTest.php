<?php

use App\Adapter\DatabaseAdapter;
use App\Adapter\DataSourceAdapterInterface;
use App\Adapter\JsonAdapter;
use App\Adapter\UserAdapterManager;
use App\Controller\UserController;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserAPITest extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private EntityManagerInterface $entityManager;

    private DataSourceAdapterInterface $dataSourceAdapter;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
//        $this->dataSourceAdapter = new DatabaseAdapter($this->entityManager);
        $this->dataSourceAdapter = new JsonAdapter($kernel->getProjectDir().'/Users.json');
        $this->userAdapter = new UserAdapterManager($this->dataSourceAdapter);
    }

    public function testGetUser()
    {
        $user = $this->userAdapter->getUser(1);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testCreateUser()
    {
        $user = $this->userAdapter->addUser([
            'username' => 'John',
            'email' => 'johnUser@gmail.com'
        ]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testUpdateUser()
    {
        $user = $this->userAdapter->updateUser([
            'id' => 1,
            'username' => 'First User Updated',
            'email' => 'firstUserUpdated@gmail.com'
        ]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testDeleteUser()
    {
        $status = $this->userAdapter->deleteUser([
            'id' => 3
        ]);
        $this->assertTrue($status);
    }


}
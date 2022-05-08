<?php
declare(strict_types=1);

namespace App\Controller;

use App\Adapter\UserAdapterManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     * @ORM\Column(type="string")
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserAdapterManager
     */
    private UserAdapterManager $adapterManager;

    /**
     * @var Serializer
     */
    private Serializer $serializer;


    /**
     * @param EntityManagerInterface $entityManager
     * @param UserAdapterManager $adapterManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserAdapterManager $adapterManager
    )
    {
        $this->entityManager = $entityManager;
        $this->adapterManager = $adapterManager;
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function get(int $id): JsonResponse
    {
        $user = $this->adapterManager->getUser($id);
        return $this->json([
            'response_code' => Response::HTTP_OK,
            'data' => $this->serializer->normalize($user, 'json'),
        ]);
    }

    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getAll(): JsonResponse
    {
        $users = $this->adapterManager->getUsers();
        return $this->json([
            'response_code' => Response::HTTP_OK,
            'data' => $this->serializer->normalize($users, 'json'),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws JsonException
     */
    public function add(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $user = $this->adapterManager->addUser($payload);
        return $this->json([
            'response_code' => Response::HTTP_OK,
            'data' => $this->serializer->normalize($user, 'json'),
            'message' => 'New user has been created!',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws JsonException
     */
    public function update(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $user = $this->adapterManager->updateUser($payload);

        return $this->json([
            'response_code' => Response::HTTP_OK,
            'data' => $this->serializer->normalize($user, 'json'),
            'message' => 'User data has been updated!',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws JsonException
     */
    public function delete(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->adapterManager->deleteUser($payload);
        return $this->json([
            'response_code' => Response::HTTP_OK,
            'data' => [],
            'message' => 'User data has been deleted!',
        ]);
    }

}

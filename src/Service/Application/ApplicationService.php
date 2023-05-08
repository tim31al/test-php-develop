<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use App\DTO\ApplicationDTO;
use App\DTO\ApplicationListDTO;
use App\DTO\ApplicationsCountDTO;
use App\DTO\BadRequestDTO;
use App\DTO\ForbiddenDTO;
use App\DTO\InternalServerErrorDTO;
use App\DTO\NotFoundDTO;
use App\DTO\ResponseDTOInterface;
use App\Repository\ApplicationRepository;
use App\Service\LoggerService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ApplicationService
{
    public const LIST_LIMIT = 100;
    public const SORT_LIST = ['asc', 'desc'];

    public function __construct(
        private readonly Security $security,
        private readonly ApplicationRepository $repository,
        private readonly LoggerService $logger
    ) {
    }

    /**
     * Список заявок.
     */
    public function list(Request $request): ResponseDTOInterface
    {
        try {
            $author = $this->getAuthor();
            $limit = $request->query->getInt('limit', self::LIST_LIMIT);
            $offset = $request->query->getInt('offset', 0);
            $sort = $request->query->getAlpha('sort', 'asc');

            if (!\in_array($sort, self::SORT_LIST, true)) {
                $message = sprintf(
                    'Unsupported sort format. Available: %s',
                    implode(', ', self::SORT_LIST)
                );

                return new BadRequestDTO(['sort' => $message]);
            }

            $applications = $this->repository->findByUser($limit, $offset, $author, $sort);

            return new ApplicationListDTO($applications);
        } catch (\Throwable $e) {
            $this->logger->error(__METHOD__, $e);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Количество заявок пользователя. Для админа общее количество.
     */
    public function count(): ResponseDTOInterface
    {
        try {
            $author = $this->getAuthor();
            $count = $this->repository->findCountByUser($author);

            return new ApplicationsCountDTO($count);
        } catch (\Throwable $e) {
            $this->logger->error(__METHOD__, $e);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Просмотр заявки.
     */
    public function show(Request $request): ResponseDTOInterface
    {
        try {
            $id = $request->attributes->getInt('id');
            $application = $this->repository->find($id);

            if (
                null === $application ||
                (
                    !$this->security->isGranted('ROLE_ADMIN') &&
                    $application->getAuthor() !== $this->security->getUser()
                )
            ) {
                return new NotFoundDTO();
            }

//            if (
//                !$this->security->isGranted('ROLE_ADMIN') &&
//                $application->getAuthor() !== $this->security->getUser()
//            ) {
//                return new ForbiddenDTO();
//            }

            return new ApplicationDTO($application);
        } catch (\Throwable $e) {
            $this->logger->error(__METHOD__, $e);

            return new InternalServerErrorDTO();
        }
    }

    private function getAuthor(): ?UserInterface
    {
        return $this->security->isGranted('ROLE_ADMIN') ? null : $this->security->getUser();
    }
}

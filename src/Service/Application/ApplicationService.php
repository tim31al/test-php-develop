<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use App\DTO\ApplicationCreatedDTO;
use App\DTO\ApplicationDTO;
use App\DTO\ApplicationListDTO;
use App\DTO\ApplicationsCountDTO;
use App\DTO\BadRequestDTO;
use App\DTO\InternalServerErrorDTO;
use App\DTO\NotFoundDTO;
use App\DTO\ResponseDTOInterface;
use App\DTO\SuccessDTO;
use App\Entity\Application;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use App\Service\LoggerService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Crud Service для заявок.
 * Манипуляции с заявками доступны верифицированным пользователям.
 * Для ROLE_ADMIN доступ ко всем заявкам, остальным - только свои.
 */
class ApplicationService implements ApplicationServiceInterface
{
    public const LIST_LIMIT = 100;
    public const SORT_LIST = ['asc', 'desc'];

    public function __construct(
        private readonly Security $security,
        private readonly ApplicationRepository $repository,
        private readonly LoggerService $logger,
        private readonly ApplicationValidator $validator,
        private readonly ApplicationBuilder $builder,
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
                $message = sprintf('Unsupported sort format. Available: %s', implode(', ', self::SORT_LIST));

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
            $application = $this->findApplication($request);
            if (null === $application || !$this->assertUserApplicationOwner($application)) {
                return new NotFoundDTO();
            }

            return new ApplicationDTO($application);
        } catch (\Throwable $e) {
            $this->logger->error(__METHOD__, $e);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Создание заявки.
     */
    public function create(Request $request): ResponseDTOInterface
    {
        try {
            $data = json_decode($request->getContent(), true) ?? [];

            $errors = $this->validator->validate($data);
            if (0 !== \count($errors)) {
                return new BadRequestDTO($errors);
            }

            /** @var User $author */
            $author = $this->security->getUser();
            $application = $this->builder->build($data, $author);

            $this->repository->save($application, true);

            return new ApplicationCreatedDTO($application);
        } catch (\Throwable $e) {
            $this->logger->error(__METHOD__, $e);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Удаление заявки.
     */
    public function delete(Request $request): ResponseDTOInterface
    {
        $application = null;
        try {
            $application = $this->findApplication($request);
            if (null === $application || !$this->assertUserApplicationOwner($application)) {
                return new NotFoundDTO();
            }

            $this->repository->remove($application, true);

            return new SuccessDTO();
        } catch (\Throwable $e) {
            $context = $this->getErrorContext($application);
            $this->logger->error(__METHOD__, $e, $context);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Обновление заявки.
     */
    public function update(Request $request): ResponseDTOInterface
    {
        $application = null;
        try {
            $application = $this->findApplication($request);
            if (null === $application || !$this->assertUserApplicationOwner($application)) {
                return new NotFoundDTO();
            }

            $data = json_decode($request->getContent(), true) ?? [];

            $errors = $this->validator->validate($data, false);
            if (0 !== \count($errors)) {
                return new BadRequestDTO($errors);
            }

            $application = $this->builder->fill($application, $data);

            $this->repository->save($application, true);

            return new SuccessDTO();
        } catch (\Throwable $e) {
            $context = $this->getErrorContext($application);
            $this->logger->error(__METHOD__, $e, $context);

            return new InternalServerErrorDTO();
        }
    }

    /**
     * Найти заявку по id из запроса.
     */
    private function findApplication(Request $request): ?Application
    {
        $id = $request->attributes->getInt('id');

        return $this->repository->find($id);
    }

    /**
     * Заявка доступна только создателю или администратору.
     */
    private function assertUserApplicationOwner(Application $application): bool
    {
        if (
            !$this->security->isGranted('ROLE_ADMIN') &&
            $application->getAuthor() !== $this->security->getUser()
        ) {
            return false;
        }

        return true;
    }

    private function getAuthor(): ?UserInterface
    {
        return $this->security->isGranted('ROLE_ADMIN') ? null : $this->security->getUser();
    }

    /**
     * @return array<string, int>
     */
    private function getErrorContext(?Application $application): array
    {
        if (null !== $application && null !== $application->getId()) {
            return ['applicationId' => $application->getId()];
        }

        return [];
    }
}

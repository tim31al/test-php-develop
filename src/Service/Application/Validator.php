<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Валидатор заявки.
 */
class Validator
{
    public const MIN_TITLE = 2;
    public const MAX_TITLE = 255;
    public const MIN_TEXT = 2;
    public const MAX_TEXT = 65536;
    public const MAX_FILE_SIZE = '1024K';

    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * Проверка заявки.
     *
     * @param array<string, string> $data
     *
     * @return array<int<0, max>, string>
     */
    public function validateApplication(array $data, bool $isStrict = true): array
    {
        $constraints = $this->getConstraints($isStrict);
        $violations = $this->validator->validate($data, $constraints);

        if (0 !== \count($violations)) {
            return $this->getErrors($violations);
        }

        if (!$isStrict && 0 === \count($data)) {
            return ['title or text : Required.'];
        }

        return [];
    }

    /**
     * Проверка файла.
     *
     * @param array<string, string> $data
     *
     * @return array<int<0, max>, string>
     */
    public function validateFile(array $data): array
    {
        $constraints = $this->getFileConstraints();
        $violations = $this->validator->validate($data, $constraints);

        return $this->getErrors($violations);
    }

    /**
     * @return array<int<0, max>, string>
     */
    private function getErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = $violation->getPropertyPath().' : '.$violation->getMessage();
        }

        return $errors;
    }

    private function getConstraints(bool $isStrict): Assert\Collection
    {
        $allowMissingFields = !$isStrict;

        return new Assert\Collection([
            'title' => new Assert\Length(['min' => self::MIN_TITLE, 'max' => self::MAX_TITLE]),
            'text' => new Assert\Length(['min' => self::MIN_TEXT, 'max' => self::MAX_TEXT]),
        ], null, null, false, $allowMissingFields);
    }

    private function getFileConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'file' => new Assert\File([
                'maxSize' => self::MAX_FILE_SIZE,
            ]),
        ]);
    }
}

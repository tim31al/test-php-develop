<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 * Валидатор заявки.
 */
class ApplicationValidator
{
    public const MIN_TITLE = 2;
    public const MAX_TITLE = 255;
    public const MIN_TEXT = 2;
    public const MAX_TEXT = 65536;

    /**
     * @param array<string, string> $data
     *
     * @return array<int<0, max>, string>
     */
    public function validate(array $data, bool $isStrict = true): array
    {
        $validator = Validation::createValidator();

        $constraints = $this->getConstraints($isStrict);

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        if (0 !== \count($violations)) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath().' : '.$violation->getMessage();
            }
        } elseif (!$isStrict && 0 === \count($data)) {
            $errors[] = 'title or text : Required.';
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
}

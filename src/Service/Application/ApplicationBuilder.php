<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use App\Entity\Application;
use App\Entity\User;

class ApplicationBuilder
{
    /**
     * @param array<string, string> $data
     */
    public function build(array $data, User $author): Application
    {
        $application = new Application();

        $application
            ->setTitle($data['title'])
            ->setText($data['text'])
            ->setAuthor($author)
        ;

        return $application;
    }

    /**
     * @param array<string, string> $data
     */
    public function fill(Application $application, array $data): Application
    {
        if (\array_key_exists('title', $data)) {
            $application->setTitle($data['title']);
        }

        if (\array_key_exists('text', $data)) {
            $application->setText($data['text']);
        }

        return $application;
    }
}

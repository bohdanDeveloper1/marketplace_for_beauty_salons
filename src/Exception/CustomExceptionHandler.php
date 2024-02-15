<?php
// src/Exception/CustomExceptionHandler.php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CustomExceptionHandler
{
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // Отримати виняток
        $exception = $event->getThrowable();

        // Обробити виняток
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // Встановити шаблон для помилки 404
        if ($statusCode === Response::HTTP_NOT_FOUND) {
            $content = $this->twig->render('404.html.twig');
        } else {
            // Інші типи помилок можуть використовувати стандартну поведінку
            return;
        }

        // Повернути відповідь
        $response = new Response($content);
        $response->setStatusCode($statusCode);

        // Встановити відповідь у викликаючому об'єкті події
        $event->setResponse($response);
    }
}

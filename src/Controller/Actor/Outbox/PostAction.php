<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        throw new Exception('Not implemented');
    }
}

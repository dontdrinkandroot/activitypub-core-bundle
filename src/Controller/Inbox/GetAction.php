<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Inbox;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        throw new Exception('Not implemented');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: hamid
 * Date: 08/07/2020
 * Time: 12:47
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MaintenanceSubcriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function methodCalledOnKernelResponse(FilterResponseEvent $filterResponseEvent){

        if ($_SERVER['MAINTENANCE'] == false){
            $content = $this->twig->render('maintenance/maintenance.html.twig');
//            $content = $this->twig->render('maintenance/maintenance.html.twig');
            $response = new Response($content);
            $filterResponseEvent->setResponse($response);
        }
        return $filterResponseEvent->getResponse()->getContent();
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE=> 'methodCalledOnKernelResponse'
        ];
    }
}
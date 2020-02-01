<?php


namespace App\Subscriber;


use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return[UserRegisteredEvent::class => "onUserRegisteredEvent"];
    }

    public function onUserRegisteredEvent(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        $this->logger->info(sprintf(
            "User created with email: %s, firstName: %s and lastName: %s ",
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName()
        ));
    }
}
<?php


namespace App\EventListener;

use App\Entity\Traits\TimestampableInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class TimestapableEntityListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // Se llama antes de que la entidad se persista en la base de datos
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Si la entidad implementa TimestampableInterface
        if ($entity instanceof TimestampableInterface) {
            $user = $this->security->getUser();
            $now = new \DateTimeImmutable();

            // Asignar valores de tiempo y usuario al crear la entidad
            $entity->setCreatedAt($now);
            $entity->setUpdatedAt($now);

            if ($user) {
                $entity->setCreatedBy($user);
                $entity->setUpdatedBy($user);
            }
        }
    }

    // Se llama antes de que la entidad se actualice en la base de datos
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Si la entidad implementa TimestampableInterface
        if ($entity instanceof TimestampableInterface) {
            $user = $this->security->getUser();
            $now = new \DateTimeImmutable();

            // Asignar valores de tiempo y usuario al actualizar la entidad
            $entity->setUpdatedAt($now);

            if ($user) {
                $entity->setUpdatedBy($user);
            }
        }
    }
}
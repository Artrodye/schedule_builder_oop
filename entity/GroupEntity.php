<?php

namespace app\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('groups')]
class GroupEntity
{
    #[Id]
    #[Column(name: 'id'), GeneratedValue]
    private int $id;

    #[Column(name: 'name', type: Types::STRING, length: 8)]
    private string $name;

    #[ManyToMany(targetEntity: EventEntity::class, mappedBy: 'groups')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function addEventsCollection(EventEntity $event): void
    {
        $this->events->add($event);
    }
    public function getEvents()
    {
        $events = $this->events->getValues();
        $result = [];
        foreach ($events as $event) {
            $result[] = $event->getId();
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): GroupEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): GroupEntity
    {
        $this->name = $name;
        return $this;
    }


}
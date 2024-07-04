<?php

namespace app\entity;

use app\Repository\EventEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: EventEntityRepository::class)]
#[Table('events')]
class EventEntity
{
    #[Id]
    #[Column(name: 'id'), GeneratedValue]
    private int $id;

    #[Column(name: 'name')]
    private string $name;

    #[Column(name: 'begin_time')]
    private string $beginTime;

    #[Column(name: 'room')]
    private string $room;

    #[Column(name: 'teacher')]
    private string $teacher;

    #[Column(name: 'isManyGroups')]
    private int $isManyGroups;

    #[Column(name: 'comment')]
    private string $comment;

    #[ManyToMany(targetEntity: GroupEntity::class, inversedBy: 'events')]
    #[JoinTable(
        name: 'event_group',
        joinColumns: [new ORM\JoinColumn(name:'event_id', referencedColumnName: 'id')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    )]

    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function clearGroupsCollection()
    {
        $this->groups->clear();
    }

    public function addGroupsCollection(GroupEntity $group): EventEntity
    {
        $this->groups->add($group);
        $group->addEventsCollection($this);
        return $this;
    }

    public function getGroups(): array
    {
        $groups = $this->groups->getValues();
        $result = [];
        foreach ($groups as $group) {
            $result[] = $group->getId();
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): EventEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): EventEntity
    {
        $this->name = $name;
        return $this;
    }

    public function getBeginTime(): string
    {
        return $this->beginTime;
    }

    public function setBeginTime(string $beginTime): EventEntity
    {
        $this->beginTime = $beginTime;
        return $this;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setRoom(string $room): EventEntity
    {
        $this->room = $room;
        return $this;
    }

    public function getTeacher(): string
    {
        return $this->teacher;
    }

    public function setTeacher(string $teacher):EventEntity
    {
        $this->teacher = $teacher;
        return $this;
    }

    public function getIsManyGroups(): int
    {
        return $this->isManyGroups;
    }

    public function setIsManyGroups(int $isManyGroups): EventEntity
    {
        $this->isManyGroups = $isManyGroups;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): EventEntity
    {
        $this->comment = $comment;
        return $this;
    }
}
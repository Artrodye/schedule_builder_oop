<?php

namespace app\Service;

use app\ApplicationException\ApplicationException;
use app\dto\Group\SafeGroupDTO;
use app\entity\GroupEntity;
use app\validator\Group\GroupValidator;
use app\views\TemplateWriter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Dompdf\Dompdf;

class GroupService
{
    public function __construct(
        protected readonly EntityManager $entityManager,
    )
    {
    }

    public function htmlToPDF($html, $name)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4');

        $dompdf->render();

        $dompdf->stream($name . '.pdf');

        $output = $dompdf->output();
        file_put_contents('C:/Users/bryle/Downloads/' . $name . ".pdf", $output);
    }

    public function getEventsForGroup(SafeGroupDTO $dto): array
    {
        if (is_null($dto->id)) {
            throw new ApplicationException("Не передан идентификатор группы", 400);
        }
        $result = $this->entityManager->getRepository(GroupEntity::class)->getEventsForGroup($dto);
        $template = (new TemplateWriter())->load('getEventsForGroup');
        $group = $this->entityManager->getRepository(GroupEntity::class)->find($dto->id);
        if (is_null($group)) {
            throw new ApplicationException('Такой группы не существует', 400);
        }
        $name = $group->getName();
        $html = $template->render([
            'groupName' => $name,
            'events' => $result
        ]);
        $this->htmlToPDF($html, $name);
        return $result;
    }

    public function getAll(): array
    {
        $groups = $this->entityManager->getRepository(GroupEntity::class)->findAll();
        $result = [];
        foreach ($groups as $group) {
            $result[] = [
                "id" => $group->getId(),
                "name" => $group->getName()
            ];
        }
        return $result;
    }

    public function getGroup(int $id): array
    {
        $group = $this->entityManager->getRepository(GroupEntity::class)->find($id);
        if (is_null($group)) {
            throw new ApplicationException('Данной группы не существует', 400);
        }
        return ["id" => $group->getId(), "name" => $group->getName(), "events" => $group->getEvents()];
    }

    public function delete(int $id): void
    {
        try {
            $group = $this->entityManager->getRepository(GroupEntity::class)->find($id);
            if (is_null($group)) {
                throw new ApplicationException("Данной группы не существует", 404);
            }
            $this->entityManager->remove($group);
            $this->entityManager->flush();
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }

    public function update(SafeGroupDTO $dto): void
    {
        try {
            if (!isset($dto->id, $dto->name)) {
                throw new ApplicationException("Не переданы параметры группы", 400);
            }
            $group = $this->entityManager->getRepository(GroupEntity::class)->find($dto->id);
            if (is_null($group)) {
                throw new ApplicationException("Данной группы не существует", 404);
            }
            if ((new GroupValidator($this->entityManager))->validateGroup($dto)) {
                $group->setName($dto->name);
                $this->entityManager->flush();
            }
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }

    public function create(SafeGroupDTO $dto)
    {
        try {
            if ((new GroupValidator($this->entityManager))->validateGroup($dto)) {
                $group = new GroupEntity();
                $group->setName($dto->name);
                $this->entityManager->persist($group);
                $this->entityManager->flush();
            }
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }
}

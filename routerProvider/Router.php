<?php

namespace app\routerProvider;

use app\ApplicationException\ApplicationException;
use app\controller\EventController;
use app\controller\GroupController;
use app\http\JsonResponse;
use app\http\Request;
use app\Service\EventService;
use app\Service\GroupService;

class Router
{
    public const CONTROLLER_NAMESPACE = '';

    public function handle(Request $request)
    {
        $entityManager = getEntityManager();
        $act = $request->getQueryValue('act');
        $method = $request->getQueryValue('method');

        try {
            $controller = match ($act) {
                'group' => new GroupController(new GroupService($entityManager)),
                'event' => new EventController(new EventService($entityManager)),
                default => throw new ApplicationException('роут не найден', 404),
            };
            $result = $controller->$method($request);
            return new JsonResponse(200, ['success' => true, 'rows' => $result]);
        } catch (ApplicationException $exception) {
            return new JsonResponse($exception->getCode(), ['success' => false, 'message' => $exception->getMessage()]);
        } catch (\Throwable $throwable) {
            return new JsonResponse(500, ['success' => false, 'message' => 'Возникла ошибка при выполнении']);
        }
    }
}
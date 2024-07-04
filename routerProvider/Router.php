<?php

namespace app\routerProvider;

use app\ApplicationException\ApplicationException;
use app\Controller\EventController;
use app\Controller\GroupController;
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
            switch ($act) {
                case 'group':
                    $controller = new GroupController(new GroupService($entityManager));
                    break;
                case 'event':
                    $controller = new EventController(new EventService($entityManager));
                    break;
                default:
                    throw new ApplicationException(404, 'роут не найден');
            }
            $result = $controller->$method($request);
            return new JsonResponse(200, ['success' => true, 'rows' => $result]);
        } catch (ApplicationException $exception) {
            return new JsonResponse($exception->getCode(), ['success' => false, 'message' => $exception->getMessage()]);
        } catch (\Throwable $throwable) {
            return new JsonResponse(500, ['success' => false, 'message' => 'Возникла ошибка при выполнении']);
        }
    }
}
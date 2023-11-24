<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Requests\CustomerController\NotificationRequest;
use App\Service\Notification\NotificationOrchestratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer", name="customer_")
 */
class CustomerController extends AbstractController
{
    private NotificationOrchestratorService $notificationModifierService;

    public function __construct(NotificationOrchestratorService $notificationModifierService)
    {
        $this->notificationModifierService = $notificationModifierService;
    }

    /**
     * @Route("/{code}/notifications", name="notifications", methods={"POST"})
     *
     * @throws \Exception
     */
    public function notifyCustomer(NotificationRequest $request, string $code): Response
    {
        $request->validate();
        try {
            $this->notificationModifierService->send($request->getRequestAsArray(), $code);

            return $this->json('Message was sent successfully')->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }
}
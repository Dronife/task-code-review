<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Requests\CustomerController\NotificationRequest;
use App\Service\Customer\CustomerProviderService;
use App\Service\Message\MessageProviderService;
use App\Service\Messenger\MessengerModifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer", name="customer_")
 */
class CustomerController extends AbstractController
{
    private CustomerProviderService $customerProviderService;
    private MessageProviderService $messageProviderService;
    private MessengerModifierService $messengerModifierService;

    public function __construct(
        CustomerProviderService  $customerProviderService,
        MessageProviderService   $messageProviderService,
        MessengerModifierService $messengerModifierService
    )
    {
        $this->customerProviderService = $customerProviderService;
        $this->messageProviderService = $messageProviderService;
        $this->messengerModifierService = $messengerModifierService;
    }

    /**
     * @Route("/{code}/notifications", name="notifications", methods={"POST"})
     *
     * @throws \Exception
     */
    public function notifyCustomer(NotificationRequest $request, string $code): Response
    {
        $request->validate();
        $data = $request->getRequestAsArray();

        try {
            $customer = $this->customerProviderService->getOneCustomerByCode($code);
            $message = $this->messageProviderService->getModel($data, $customer);
            $this->messengerModifierService->send($message);
        } catch (\Exception $e) {
            return $this->json($e->getMessage())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Message was sent successfully')->setStatusCode(Response::HTTP_OK);
    }
}
<?php declare(strict_types=1);

namespace App\Service\Notification;

use App\Service\Customer\CustomerProviderService;
use App\Service\Message\MessageProviderService;
use App\Service\Messenger\MessengerService;
use App\Service\Sender\SenderProviderService;

class NotificationOrchestratorService
{
    private CustomerProviderService $customerProviderService;
    private MessageProviderService $messageProviderService;
    private MessengerService $messengerModifierService;
    private SenderProviderService $senderProviderService;

    public function __construct(
        CustomerProviderService $customerProviderService,
        MessageProviderService  $messageProviderService,
        MessengerService        $messengerModifierService,
        SenderProviderService   $senderProviderService
    )
    {
        $this->customerProviderService = $customerProviderService;
        $this->messageProviderService = $messageProviderService;
        $this->messengerModifierService = $messengerModifierService;
        $this->senderProviderService = $senderProviderService;
    }

    /**
     * @param array $data = [
     *       'body' => '',
     *  ]
     *
     * @throws \Exception
     */
    public function send(array $data, string $code): void
    {
        $customer = $this->customerProviderService->getOneCustomerByCode($code);
        $message = $this->messageProviderService->hydrateFromArrayToModel($data);
        $sender = $this->senderProviderService->getSender($customer);

        $this->messengerModifierService->send($sender, $message);
    }
}
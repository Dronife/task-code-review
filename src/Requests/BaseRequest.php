<?php declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    protected ValidatorInterface $validator;
    private Request $request;

    public function __construct(ValidatorInterface $validator, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->validator = $validator;
        $this->populate();
    }

    public function validate()
    {
        $errors = $this->validator->validate($this);

        $messages = ['message' => 'validation_failed', 'errors' => []];

        /** @var ConstraintViolation $errors */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
            $response->send();
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getRequestAsArray(): array
    {
        $request = $this->getRequest();

        if ($request->getContent()) {
            return json_decode($request->getContent(), true);
        }

        return [];
    }

    protected function populate(): void
    {
        try {
            $requestData = json_decode($this->getRequest()->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Malformed JSON received.');
            }

            foreach ($requestData as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->{$property} = $value;
                }
            }
        } catch (\Exception $e) {
            $messages = [
                'message' => 'Failed to process request data.',
                'errors' => $e->getMessage()
            ];

            $response = new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
            $response->send();
        }
    }
}
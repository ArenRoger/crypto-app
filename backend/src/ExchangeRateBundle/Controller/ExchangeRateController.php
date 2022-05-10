<?php

namespace App\ExchangeRateBundle\Controller;

use App\CommonBundle\Controller\BaseController;
use App\ExchangeRateBundle\DTO\ExchangeRateGetDto;
use App\ExchangeRateBundle\Service\GetExchangeRateService;
use App\ExchangeRateBundle\Service\Strategy\BitcoinStrategy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ExchangeRateController extends BaseController
{
    protected GetExchangeRateService $getExchangeRateService;

    protected BitcoinStrategy $bitcoinStrategy;

    public function __construct(
        ValidatorInterface $validator,
        GetExchangeRateService $getExchangeRateService,
        BitcoinStrategy $bitcoinStrategy,
    )
    {
        parent::__construct($validator);

        $this->getExchangeRateService = $getExchangeRateService;
        $this->bitcoinStrategy = $bitcoinStrategy;
    }

    /**
     * @Route("/get", methods={"GET"})
     */
    public function get(Request $request): JsonResponse
    {
        return $this->json($this->getUser()->getEmail());
        die();
        $exchangeRateGetDto = new ExchangeRateGetDto($request->query->all());
        $errors = $this->validateDto($exchangeRateGetDto);
        if (count($errors) > 0) {
            return $this->json($errors);
        }

        // TODO Add strategies, EtheriumStrategy, BitcoinStrategy, LitecoinStrategy, ...
        if ($exchangeRateGetDto->from === 'BTC') {
            $this->getExchangeRateService->setStrategy($this->bitcoinStrategy);
        } else {
            return $this->json('Service is not supported', Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json($this->getExchangeRateService->getRateToUsd()->rate);
    }
}

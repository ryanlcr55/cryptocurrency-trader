<?php
namespace App\Exchange;

use EasyExchange\Factory;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ExchangeBinance
{
    const SIDE_ARRAY = [
        'BUY' => 'buy',
        'SELL' => 'sell',
    ];

    private const RECV_WINDOW = 60000;
    private $app;

    public function __construct($apiKey, $secretKey)
    {
        $this->app = $this->getApp($apiKey, $secretKey);
    }

    public function getBnbBurnStatus()
    {
        return $this->app->user->getBnbBurnStatus();
    }

    protected function getApp($apiKey, $secretKey)
    {
        $binanceConfig = config('exchange_config.binance');
        $binanceConfig['app_key'] = $apiKey;
        $binanceConfig['secret'] = $secretKey;
        return Factory::binance($binanceConfig);
    }

    public function getOrderInfo(string $symbol, string $binanceOrderId)
    {
        $params = [
            'symbol' => $symbol,
            'orderId' => $binanceOrderId,
        ];
        return $this->app->spot->get($params);
    }

    public function buyingTrade(string $symbol, string $quoteOrderQty)
    {
        $symbol = strtoupper($symbol);
        $params = [
            'symbol' => $symbol,
            'side' => 'BUY', //BUY or SELL
            'type' => 'MARKET',
            'quoteOrderQty' => $quoteOrderQty,
            'recvWindow' => self::RECV_WINDOW,
        ];
        try {
            $response = $this->app->spot->order($params);
            return $this->formatOrderResponse($response);

        } catch (\Exception $e) {
            if ($e instanceof BadResponseException) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                Log::Critical('Binance buying trade fail', [
                    'params' => $params,
                    'response' => $response,
                ]);
                throw new \Exception(json_encode($response), 400);
            }
            Log::Critical('Binance buying trade fail', [
                'params' => $params,
            ]);
            throw $e;
        }
    }

    public function sellingTrade(string $symbol, string $quantity)
    {
        $symbol = strtoupper($symbol);
        $params = [
            'symbol' => $symbol,
            'side' => 'SELL', //BUY or SELL
            'type' => 'MARKET',
            'quantity' => $quantity,
            'recvWindow' => self::RECV_WINDOW,
        ];
        try {
            $response = $this->app->spot->order($params);
            return $this->formatOrderResponse($response);
        } catch (\Exception $e) {
            if ($e instanceof BadResponseException) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                Log::Critical('Binance selling trade fail', [
                    'params' => $params,
                    'response' => $response,
                ]);
                throw new \Exception(json_encode($response), 400);
            }
            Log::Critical('Binance selling trade fail', [
                'params' => $params,
            ]);
            throw $e;
        }
    }

    public function getCoinBalance(string $coin): string
    {
        $allCoins = $this->app->wallet->getAll();

        foreach ($allCoins as $coin) {
            if ($coin['coin'] === strtoupper($coin)) {
                return $coin['free'];
            }
        }

        return '0';
    }
    
    public function getOrders(string $symbol, int $limit = 10)
    {
        $params = [
            'symbol' => strtoupper($symbol),
            'limit' => $limit,
            'timestamp' => Carbon::now()->getTimestamp(),
        ];

        $response = $this->app->spot->allOrders($params);
        return $response;
    }

    public function getPrice(string $symbol)
    {
        return $this->app->market->getPrice(strtoupper($symbol));
    }

    private function formatOrderResponse(array $response)
    {
        return [
            'symbol' => $response['symbol'],
            'action' => self::SIDE_ARRAY[$response['side']],
            'order_id' => $response['orderId'],
            'price' => bcdiv($response['cummulativeQuoteQty'], $response['executedQty'], 20),
            'cost' => $response['cummulativeQuoteQty'],
            'quantity' => $response['executedQty'],
            'fee' => $this->sumTotalFills($response['fills']),
            'order_created_at' => date('Y-m-d H:i:s', $response['transactTime'] / 1000),
        ];
    }

    private function sumTotalFills(array $fills): string
    {
        $totalFills = "0";
        foreach ($fills as $fill) {
            $totalFills = bcadd($totalFills, $fill['commission'], 18);
        }

        return $totalFills;
    }
}

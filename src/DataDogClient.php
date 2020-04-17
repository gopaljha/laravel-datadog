<?php

namespace GopalJha\LaravelDataDog;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use function GuzzleHttp\json_encode;

class DataDogClient
{
    public function __construct()
    {
        $this->client = new Client;
    }

    public function increment($metric, $tags, $host)
    {
        $series = [
            'metric' => $metric,
            'points' => [
                array(time(), 1),
            ],
            'type' => 'count',
        ];

        if (!empty($tags)) {
            $series['tags'] = $tags;
        }

        if (!is_null($host)) {
            $series['host'] = $host;
        }

        return retry(3, function () use ($series) {
            try {
                $this->client->post(
                    config('datadog.host') . 'series?api_key=' . config('datadog.api_key'),
                    [
                        RequestOptions::JSON => [
                            'series' => [$series],
                        ],
                    ]
                );
            } catch (\Exception $th) {
                $this->writeLog("Metrix: " . json_encode($series));
                $this->writeLog("Parent Error: " . json_encode($th->getMessage()));
                
                try {
                    $this->client->request(
                        "POST",
                        config('datadog.host') . 'series?api_key=' . config('datadog.api_key'),
                        [
                            "json" => [
                                'series' => [$series],
                            ],
                        ]
                    );
                } catch (\Exception $td) {
                    $this->writeLog("Metrix: " . json_encode($series));
                    $this->writeLog("Child Error: " . json_encode($td->getMessage()));
                }
            }
        }, 500);
    }

    public function writeLog($message = null)
    {
        $cudate = date("Y-m-d H:i:s");
        if ($fp = fopen(storage_path('logs/datadog_' . date('Y-m-d') . '.log'), 'a')) {
            fwrite($fp, $cudate . "====>" . $message . PHP_EOL);
            fclose($fp);
        }
    }
}

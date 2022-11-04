<?php

namespace App\Helpers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Consts\TwitterConst;
use Exception;
use Log;

class Utils
{
    /**
     * 連想配列のキーにprefixを付与
     * 
     * @param array  $data
     * @param string $prefix
     * @return array
     */
    public static function addPrefixKeys(array $data, string $prefix): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$prefix . '_' . $key] = $value;
        }
        return $result;
    }

    /**
     * 配列内からpublic_metricsを展開して元の配列に結合
     * 
     * @param array $response
     * @return array
     */
    public static function shapingPublicMetrics(array $response): array
    {
        $public_metrics = array_column($response, 'public_metrics');

        $result = array_map(function ($response, $public_metrics) {
            unset($response->public_metrics);
            return array_merge((array)$response, (array)$public_metrics);
        }, $response, $public_metrics);

        return $result;
    }
}
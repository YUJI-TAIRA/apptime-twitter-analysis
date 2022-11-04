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
     * @return void
     */
    public static function addPrefixKeys(array &$data, string $prefix): void
    {
        array_walk($data, function (&$value, $key) use ($prefix) {
            $value = $prefix . '_' . $key;
        });
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

        array_walk($response, function (&$value, $key) use ($public_metrics) {
            $value = array_merge((array)$value, (array)$public_metrics[$key]);
            unset($value['public_metrics']);
        });
        return $response;
    }
}
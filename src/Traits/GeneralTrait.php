<?php

namespace App\Traits;

trait GeneralTrait
{
    public function slug($text): string
    {
        $slug = strtolower($text);
        $slug = trim(preg_replace('/[^A-Za-z0-9]+/', '-', $slug), '-');
        return $slug;
    }

    /**
     * Convert array of objects to array of arrays
     * 
     * @param array $array Array of objects
     * @return array Array of arrays
     */
    public function toArray(array $array): array
    {
        return array_map(function ($value) {
            return (array)$value;
        }, $array);
    }

    public function getPrefixes(string $network): string
    {
        $prefixes  = "";

        switch ($network) {
            case 'mtn': {
                    $prefixes = $_ENV['MTN_NUMBER_PREFIX'];
                    break;
                }
            case 'airtel': {
                    $prefixes = $_ENV['AIRTEL_NUMBER_PREFIX'];
                    break;
                }
            case 'glo': {
                    $prefixes = $_ENV['GLO_NUMBER_PREFIX'];
                    break;
                }
            case 'ninemobile': {
                    $prefixes = $_ENV['NINEMOBILE_NUMBER_PREFIX'];
                    break;
                }
            default:
                break;
        }

        return $prefixes;
    }

    public function logMessage($type, $message)
    {
        $dir = dirname(__DIR__) . '/../logs/' . date("d-M-Y");

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $message = "[" . date("Y-m-d H:i:s", time()) . "] {$message} \n";

        $file = "{$dir}/$type.txt";

        file_put_contents($file, $message, FILE_APPEND);
    }
}

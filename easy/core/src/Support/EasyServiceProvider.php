<?php

namespace Easy\Core\Support;

use Illuminate\Support\ServiceProvider;

class EasyServiceProvider extends ServiceProvider
{
    /**
     * mergeConfigFileFrom
     *
     * @param mixed $path
     * @param mixed $key
     * @return void
     */
    protected function mergeConfigFileFrom($path, $key, $isPreferenceOrSchema = false)
    {
        $fileName = $key . '.php';
        if (file_exists(config_path($fileName))) {
            $original = $this->app['config']->get($key, []);
            $this->app['config']->set(
                $key,
                ($isPreferenceOrSchema) ? $this->configArrayMerge(require $path, $original) : $this->multiLevelArrayMerge(require $path, $original)
            );
        }
    }

    /**
     * multiLevelArrayMerge
     *
     * @param mixed $toMerge
     * @param mixed $original
     * @return array
     */
    protected function multiLevelArrayMerge($toMerge, $original): array
    {
        $tempArray = [];
        foreach ($original as $key => $value) {
            if (isset($toMerge[$key]) && is_array($value)) {
                $tempArray[$key] = array_merge($value, $toMerge[$key]);
            }
            elseif (isset($toMerge[$key])) {
                $tempArray[$key] = $toMerge[$key];
            }
            else {
                $tempArray[$key] = $value;
            }
        }
        return $tempArray;
    }

    /**
     * configArrayMerge
     *
     * @param mixed $toMerge
     * @param mixed $original
     * @return array
     */
    protected function configArrayMerge($toMerge, $original): array
    {
        if (sizeof($toMerge)) {
            if (sizeof($original)) {
                foreach ($toMerge as $key => $value) {
                    if (isset($original[$key]) && array_key_exists('remove', $value) && $value['remove'] == true) {
                        unset($original[$key]);
                    }
                    $original[$key] =  $value;
                }
                return $original;
            } else {
                return $toMerge;
            }
        } else {
            return $original;
        }
    }
}

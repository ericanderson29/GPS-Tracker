<?php declare(strict_types=1);

namespace App\Domains\Core\Test\Feature;

use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Core\Test\TestAbstract;
use App\Services\Http\Curl\Curl;

abstract class FeatureAbstract extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route;

    /**
     * @param ?string $name = null
     * @param mixed ...$params
     *
     * @return string
     */
    protected function route(?string $name = null, ...$params): string
    {
        return (string)route($this->route.($name ? ('.'.$name) : ''), $params);
    }

    /**
     * @param ?string $model = null
     * @param mixed ...$params
     *
     * @return string
     */
    protected function routeFactoryCreateModel(?string $model = null, ...$params): string
    {
        return $this->route(null, $this->factoryCreate($model)->id, ...$params);
    }

    /**
     * @param ?string $model = null
     * @param mixed ...$params
     *
     * @return string
     */
    protected function routeFactoryLastModel(?string $model = null, ...$params): string
    {
        return $this->route(null, $this->rowLast($model)->id, ...$params);
    }

    /**
     * @param array $data
     * @param \App\Domains\Core\Model\ModelAbstract $row
     * @param array $exclude = []
     * @param array $only = []
     *
     * @return void
     */
    protected function dataVsRow(array $data, ModelAbstract $row, array $exclude = [], array $only = []): void
    {
        if ($only) {
            $data = helper()->arrayValuesWhitelist($data, $only);
        } else {
            $data = helper()->arrayKeysBlacklist($data, array_merge(['created_at', 'updated_at'], $exclude));
        }

        foreach ($data as $key => $value) {
            if (is_string($value) || is_string($row->$key)) {
                $this->assertTrue($value == $row->$key, sprintf('[%s] %s != %s', $key, $value, $row->$key));
            } else {
                $this->assertEquals($value, $row->$key);
            }
        }
    }

    /**
     * @return void
     */
    protected function curlFake(string $file): void
    {
        Curl::fake(preg_replace(['/\n\r/', '/\n/'], ["\n", "\n\r"], file_get_contents(base_path($file))));
    }
}

<?php declare(strict_types=1);

namespace App\Domains\Alarm\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Domains\Device\Model\Device as DeviceModel;
use App\Domains\Alarm\Model\Alarm as Model;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response\\Illuminate\Http\JsonResponse
     */
    public function __invoke(): Response|JsonResponse
    {
        if ($this->request->wantsJson()) {
            return $this->responseJson();
        }

        $this->meta('title', __('alarm-index.meta-title'));

        return $this->page('alarm.index', [
            'list' => $this->list(),
            'devices' => ($devices = $this->devices()),
            'devices_multiple' => ($devices->count() > 1),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return Model::query()
            ->byUserId($this->auth->id)
            ->withDevicesCount()
            ->withNotificationsCount()
            ->withNotificationsPendingCount()
            ->list()
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function devices(): Collection
    {
        return DeviceModel::query()
            ->byUserId($this->auth->id)
            ->list()
            ->get();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseJson(): JsonResponse
    {
        return $this->json($this->factory()->fractal('simple', $this->responseJsonList()));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function responseJsonList(): Collection
    {
        return Model::query()->byUserId($this->auth->id)->enabled()->get();
    }
}

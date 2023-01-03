<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Alarm\Model\Collection\Alarm as AlarmCollection;
use App\Domains\AlarmNotification\Model\Collection\AlarmNotification as AlarmNotificationCollection;
use App\Domains\Position\Model\Collection\Position as PositionCollection;
use App\Domains\Position\Model\Position as PositionModel;
use App\Domains\Trip\Model\Trip as TripModel;

class Map extends Component
{
    /**
     * @param \App\Domains\Trip\Model\Trip $trip
     * @param \App\Domains\Position\Model\Collection\Position $positions
     * @param ?\App\Domains\Alarm\Model\Collection\Alarm $alarms = null
     * @param ?\App\Domains\AlarmNotification\Model\Collection\AlarmNotification $notifications = null
     * @param bool $sidebarHidden = false
     *
     * @return self
     */
    public function __construct(
        readonly public TripModel $trip,
        readonly public PositionCollection $positions,
        readonly public ?AlarmCollection $alarms = null,
        readonly public ?AlarmNotificationCollection $notifications = null,
        readonly public bool $sidebarHidden = false,
    ) {
    }

    /**
     * @return ?\Illuminate\View\View
     */
    public function render(): ?View
    {
        if ($this->positions->isEmpty()) {
            return null;
        }

        return view('components.map', [
            'positionsJson' => $this->positionsJson(),
            'alarmsJson' => $this->alarmsJson(),
            'notificationsJson' => $this->notificationsJson(),
        ]);
    }

    /**
     * @return string
     */
    protected function positionsJson(): string
    {
        return $this->positions
            ->map(fn ($position) => $this->positionsJsonMap($position))
            ->sortByDesc('date_at')
            ->values()
            ->toJson();
    }

    /**
     * @param \App\Domains\Position\Model\Position $position
     *
     * @return array
     */
    protected function positionsJsonMap(PositionModel $position): array
    {
        return $position->only('id', 'date_at', 'latitude', 'longitude', 'speed', 'signal', 'created_at')
            + ['city' => $position->city->name, 'state' => $position->city->state->name];
    }

    /**
     * @return ?string
     */
    protected function alarmsJson(): ?string
    {
        return $this->alarms->toJson();
    }

    /**
     * @return ?string
     */
    protected function notificationsJson(): ?string
    {
        return $this->notifications->map->only('id', 'type', 'date_at', 'latitude', 'longitude', 'config', 'alarm')->toJson();
    }
}

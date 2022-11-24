@extends ('domains.trip.update-layout')

@section ('content')

<div class="mt-5">
    <x-map :trip="$row" :positions="$positions" :alarms="$notifications" sidebar-hidden></x-map>
</div>

<form method="get" class="mt-5">
    <input type="search" class="form-control form-control-lg" placeholder="{{ __('trip-update-device-alarm-notification.filter') }}" data-table-search="#device-alarm-notification-list-table"/>
</form>

<div class="box p-5 mt-5">
    <div class="overflow-auto header-sticky">
        <table id="device-alarm-notification-list-table" class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap" data-table-sort>
            <thead>
                <tr>
                    <th class="text-left w-1">{{ __('trip-update-device-alarm-notification.alarm') }}</th>
                    <th class="text-left w-1">{{ __('trip-update-device-alarm-notification.name') }}</th>
                    <th class="text-left">{{ __('trip-update-device-alarm-notification.message') }}</th>
                    <th class="text-left">{{ __('trip-update-device-alarm-notification.config') }}</th>
                    <th class="w-1">{{ __('trip-update-device-alarm-notification.telegram') }}</th>
                    <th class="w-1">{{ __('trip-update-device-alarm-notification.created_at') }}</th>
                    <th class="w-1">{{ __('trip-update-device-alarm-notification.closed_at') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($notifications as $each)

                <tr>
                    <td class="text-left w-1">
                        @if ($each->alarm)
                        <a href="{{ route('device.update.device-alarm.update', [$row->id, $each->alarm->id]) }}" class="block">{{ $each->typeFormat()->title() }}</a>
                        @else
                        {{ $each->typeFormat()->title() }}
                        @endif
                    </td>
                    <td class="text-left w-1"><a href="#" data-map-point="{{ $each->position_id }}" class="d-t-m-o max-w-2xs" title="{{ $each->name }}">{{ $each->name }}</a></td>
                    <td class="text-left"><a href="#" data-map-point="{{ $each->position_id }}" class="d-t-m-o max-w-2xs" title="{{ $each->typeFormat()->message() }}">{{ $each->typeFormat()->message() }}</a></td>
                    <td class="text-left">@arrayAsBadges($each->typeFormat()->config())</td>
                    <td class="w-1">@status($each->telegram)</td>
                    <td class="w-1"><a href="#" data-map-point="{{ $each->position_id }}" class="d-t-m-o max-w-xs">@dateWithTimezone($each->created_at, $row->timezone->zone)</a></td>
                    <td class="w-1">
                        @if ($each->closed_at)
                        @status(true)
                        @else
                        <a href="{{ route('device-alarm-notification.update.closed-at', $each->id) }}" class="block">@status(false)</a>
                        @endif
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

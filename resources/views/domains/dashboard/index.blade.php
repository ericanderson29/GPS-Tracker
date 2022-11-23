@extends ('layouts.in')

@section ('body')

@if ($devices->isEmpty())

<img class="m-auto mt-10 lg:mt-20 h-60" src="@asset('build/images/device.svg')">

<div class="mt-10 text-center">
    <a href="{{ route('device.create') }}" class="btn bg-white py-3 px-4">{{ __('dashboard-index.device-create') }}</a>
</div>

@elseif ($trips->isEmpty())

<img class="m-auto mt-10 lg:mt-20 h-60" src="@asset('build/images/trip.svg')">

<div class="mt-10 text-center">
    <div class="text-xl lg:text-2xl font-medium">{{ __('dashboard-index.trip-wating') }}</div>
</div>

@else

@each ('domains.device-alarm-notification.molecules.alert', $alarm_notifications, 'row')

<form method="GET">
    <div class="lg:flex lg:space-x-4">
        @if ($devices->count() > 1)

        <div class="flex-1 mb-4">
            <x-select name="device_id" :options="$devices" value="id" text="name" data-change-submit></x-select>
        </div>

        @endif

        <div class="flex-1 mb-4">
            <div class="flex">
                <div class="flex-1">
                    <x-select name="trip_id" :options="$trips" value="id" text="name" data-change-submit></x-select>
                </div>

                @if ($trip_previous_id)
                <a href="?trip_id={{ $trip_previous_id }}" class="btn bg-white ml-2">@icon('chevrons-left', 'w-4 h-4')</a>
                @else
                <span class="btn bg-white ml-2 text-gray-300 cursor-default">@icon('chevrons-left', 'w-4 h-4')</span>
                @endif

                @if ($trip_next_id)
                <a href="?trip_id={{ $trip_next_id }}" class="btn bg-white ml-2">@icon('chevrons-right', 'w-4 h-4')</a>
                @else
                <span class="btn bg-white ml-2 text-gray-300 cursor-default">@icon('chevrons-right', 'w-4 h-4')</span>
                @endif
            </div>
        </div>

        <div class="mb-4 text-center">
            <a href="#" class="btn bg-white mr-2" data-map-live>@icon('play', 'w-4 h-4 sm:w-6 sm:h-6')</a>
            <a href="{{ route('trip.update', $trip->id) }}" class="btn bg-white mr-2">@icon('edit', 'w-4 h-4 sm:w-6 sm:h-6')</a>
            <a href="{{ route('trip.update.map', $trip->id) }}" class="btn bg-white mr-2">@icon('map', 'w-4 h-4 sm:w-6 sm:h-6')</a>
            <a href="{{ route('trip.update.position', $trip->id) }}" class="btn bg-white mr-2">@icon('map-pin', 'w-4 h-4 sm:w-6 sm:h-6')</a>
            <a href="{{ route('trip.update.device-alarm-notification', $trip->id) }}" class="btn bg-white mr-2">@icon('bell', 'w-4 h-4 sm:w-6 sm:h-6')</a>
            <a href="{{ route('trip.update.merge', $trip->id) }}" class="btn bg-white mr-2">@icon('git-merge', 'w-4 h-4 sm:w-6 sm:h-6')</a>
        </div>
    </div>
</form>

<x-map :trip="$trip" :positions="$positions" :alarms="$alarms" data-map-show-last="true"></x-map>

@endif

@stop

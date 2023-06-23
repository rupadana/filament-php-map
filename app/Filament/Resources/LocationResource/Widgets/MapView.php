<?php

namespace App\Filament\Resources\LocationResource\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Webbingbrasil\FilamentMaps\Actions\Action;
use Webbingbrasil\FilamentMaps\Actions\CenterMapAction;
use Webbingbrasil\FilamentMaps\Actions\ZoomAction;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;
use Illuminate\Support\Str;
use Webbingbrasil\FilamentMaps\Actions\FullscreenAction;

class MapView extends MapWidget
{
    protected int | string | array $columnSpan = 2;

    protected bool $hasBorder = false;

    public function getMarkers(): array
    {
        return [
            // Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
        ];
    }


    protected function setUp(): void
    {
    }

    public function getActions(): array
    {
        return [
            ZoomAction::make(),
            CenterMapAction::make()->zoom(2),
            FullscreenAction::make(),
            Action::make('form')
                ->icon('heroicon-o-plus')

                ->form([
                    TextInput::make('name')
                        ->label('Name')
                        ->required(),
                    TextInput::make('lat')
                        ->label('Latitude')
                        ->required(),
                    TextInput::make('lng')
                        ->label('Longitude')
                        ->required(),
                ])

                ->action(function (array $data, self $livewire) {
                    $livewire
                        ->addMarker(
                            Marker::make(Str::camel($data['name']))
                                ->lat((float) $data['lat'])
                                ->lng((float) $data['lng'])
                                ->popup($data['name'])
                        )
                        ->centerTo(location: [(float) $data['lat'], (float) $data['lng']], zoom: 13);
                })
                ->beforeFormFilled(function ($data) {
                    $lat = 2;
                    $lng = 2;
                    
                    $data['lat'] = $lat;
                    $data['lng'] = $lng;
                    // dd($data);
                    self::fill($data);
                })
            // ->callBeforeFormValidated(function ($data) {
            //     dd($data);
            // })
            //     ->callback(<<<JS
            //     console.log(map.getCenter())
            // JS)
        ];
    }

    public function getMapOptions(): array
    {
        return [
            'center' => [0, 0], 'zoom' => 2,
            'click' => fn ($data) => dd($data)
        ];
    }
}

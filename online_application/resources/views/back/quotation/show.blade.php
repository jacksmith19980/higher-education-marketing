@php
    $details = json_decode($booking->details);

@endphp
@extends('back.layouts.core.helpers.table', [
            'show_buttons' => false,
            'title'        => false
        ])
@section('table-content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30">
                            <h4 class="card-title m-t-10">{{$details->user->first_name}} {{$details->user->last_name}}</h4>
                            <h6 class="card-subtitle">{{$details->user->email}}</h6>
                            <h6 class="card-subtitle">{{$details->user->phone}}</h6>
                        </center>
                    </div>
                    <div><hr></div>

                </div>
            </div>

            <div class="col-lg-9 col-xlg-9 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                             <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                    @foreach($booking->invoice['details'] as $key => $item)
                                        @if(in_array($key, ['weeks', 'discount']) || count($item) == 0)
                                            @continue
                                        @endif
                                        <tr>
                                            <th style="width:30%;"><strong>{{ucfirst($key)}}</strong></th>
                                            <td>
                                                @if($key == 'courses')
                                                    {{$item[0]['title']}}

                                                @elseif($key == 'addons')
                                                    @foreach($item as $key => $addon)

                                                        @php

                                                        $dateAddon = \App\Tenant\Models\Date::where('key', $booking->invoice['details']['dates'][0])->first();
                                                        @endphp


                                                        @if(!is_array($addon))
                                                            @continue
                                                        @endif

                                                        @php
                                                            $prop = isset($dateAddon->properties['addons'][reset($addon)]) ? $dateAddon->properties['addons'][reset($addon)] : null;

                                                            $addons_obj = \App\Tenant\Models\Addon::where('key', reset($addon))->first();

                                                        @endphp

                                                        @if(!empty($dateAddon))
                                                            @if($prop)
                                                                <strong>{{__('Category')}}:</strong> {{ucfirst($prop['category'])}}
                                                                <br>
                                                                <strong>{{__('Title')}}:</strong> {{ $prop['title'] }}<br/>
                                                                <strong>{{__('Price')}}:</strong> {{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '' }} {{ isset($prop['price']) ? $prop['price'] : '' }}
                                                                {{ ucfirst(isset($prop['price_type'])) ? ucfirst($prop['price_type']) : '' }}
                                                                <br/><br/>
                                                            @endif
                                                        @endif

                                                        @if(!empty($addons_obj))
                                                            <strong>{{__('Category')}}:</strong> {{ucfirst($addons_obj->category)}}
                                                            <br>
                                                            <strong>{{__('Title')}}:</strong> {{ $addons_obj->title }}

                                                        @endif

                                                    @endforeach


                                                @elseif($key == 'dates')
                                                    @php
                                                      $date = \App\Tenant\Models\Date::where('key', $item[0])->first();
                                                    @endphp

                                                    <strong>Start Date:</strong> {{$date->properties['start_date']}}
                                                    <br>
                                                    <strong>End Date:</strong> {{$date->properties['end_date']}}

                                                @elseif($key == 'transfer')
                                                    @if(!empty($item))
                                                        @foreach($item as $tKey => $transfer)
                                                            <strong>{{__('Option') }}: </strong>{{ ucfirst($transfer['option']) }} <br/>
                                                            <strong>{{__('Price') }}: </strong>{{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '' }} {{ $transfer['price'] }} <br/><br/>
                                                        @endforeach
                                                    @endif

                                                @elseif($key == 'miscellaneous')
                                                    @if(!empty($item))
                                                        @foreach($item as $tKey => $misc)
                                                            <strong>{{__('Option') }}: </strong>{{ ucfirst($misc['option']) }} <br/>
                                                            <strong>{{__('Price') }}: </strong>{{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '' }} {{ $misc['price'] }} <br/><br/>
                                                        @endforeach
                                                    @endif

                                                @elseif($key == 'accomodations')
                                                    @if(!empty($item))
                                                        @foreach($item as $tKey => $accom)
                                                            <strong>{{__('Option') }}: </strong>{{ ucfirst($accom['option']) }} <br/>
                                                            <strong>{{__('Price') }}: </strong>{{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '' }} {{ $accom['price'] }} <br/><br/>
                                                        @endforeach
                                                    @endif
                                                @elseif($key == 'price')
                                                    @if(!empty($item))
                                                        <strong>{{__('Price') }}: </strong>{{isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '' }} {{ $booking->invoice['details']['price']['total'] }} <br/><br/>
                                                    @endif

                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@extends('front.layouts.minimal')
@section('content')
    <div class="page-wrapper" style="padding-top: 100px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <h4 class="">{{ __('My Documents') }}</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-2" id="datatableNewFilter">
                                <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
                                </div>
                                <div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
                                </div>
                                <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
                                </div>
                            </div>
                            <table id="report_list_table" class="table new-table table-bordered table-striped display">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="control-column"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($shareables)
                                        @foreach ($shareables as $shareable)
                                            @php
                                                $type = '';
                                                $name = '';
                                                $read = '';
                                                $link = '';
                                                switch ($shareable->documentable_type) {
                                                    case 'App\Tenant\DocumentBuilder':
                                                        $type = 'PDF Form';
                                                        $name = App\Tenant\DocumentBuilder::find($shareable->documentable_id)->name;
                                                        $link = route('documentBuilder.build', ['documentBuilder' => $shareable->documentable_id, 'id' => $student->id]);
                                                
                                                        break;
                                                
                                                    default:
                                                        $type = '';
                                                        break;
                                                }
                                                $read = $shareable->properties ? json_decode($shareable->properties) : [];
                                                $read = (isset($read->read) and $read->read === true) ? __('read') : __('unread');
                                            @endphp

                                            <tr data-report-id="{{ $shareable->id }}">
                                                <td>{{ $shareable->id }}</td>
                                                <td><a href="{{ $link }}" target="_blank">{{ $name }}</a></td>
                                                <td>{{ $type }}</td>
                                                <td>{{ $read }}</td>
                                                <td class="control-column cta-column">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

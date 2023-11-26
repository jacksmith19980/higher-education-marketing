@extends('back.layouts.default')

@section('content')
    <style>
        .media-body {
            padding-top: 0 !important
        }
    </style>
    <div class="container">
        <input type="hidden" id="current_id" value="1">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{ __('Add Document Builder') }}</h4>
                        <hr>
                        <form method="POST" action="{{ route('documentBuilder.update', $documentBuilder) }}"
                            class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update') }}"
                            data-add-button="{{ __('Update') }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                              
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input', [
                                        'name' => 'pdf-title',
                                        'label' => 'Title',
                                        'class' => '',
                                        'required' => true,
                                        'attr' => '',
                                        'value' => $documentBuilder->name,
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input', [
                                        'name' => 'selector-table',
                                        'label' => 'Selector',
                                        'class' => '',
                                        'required' => true,
                                        'readonly' => true,
                                        'attr' => '',
                                        'value' => $documentBuilder->selector,
                                    ])
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-area',
                                    [
                                    'name' => 'description',
                                    'label' => 'Description' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' =>  $documentBuilder->description
                                    ])
                                </div>
                            </div>

                            <div class="row pt-5 px-2">
                                <div class="col-md-3">
                                    {{ __('Table') }}
                                </div>
                                <div class="col-md-3">
                                    {{ __('Field') }}
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                    {{ __('Document Field') }}
                                </div>
                            </div>

                            @php
                                $properties = json_decode($documentBuilder->properties);
                                $i = 0;
                            @endphp

                            <div class="px-2" id="pdf-fields">
                                @foreach($properties as $property)
                                {{-- {{dd($property)}} --}}
                                @if($property->choices)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control select2" id="value-{{$i}}" name="value-{{$i}}">
                                                @foreach ($property->choices as $choice)
                                                <option value="{{ $choice }}" @if($property->value == $choice) selected @endif>{{ $choice }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="choices-{{$i}}" value="{{ implode(',', $property->choices) }}">
                                        </div>
                                    </div>
                                    @php
                                        $items = explode('.', $property->name)
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input readonly type="text" class="form-control" value="{{ array_pop($items) }}">
                                            <input type="hidden" name="name-{{$i}}" value="{{ $property->name }}">
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $i += 1;
                                @endphp
                                @else
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control select2" onChange="pdfTableChanged(this)" id="table-{{$i}}" name="table-{{$i}}">
                                                <option value=""></option>
                                                @foreach($tables as $index => $table)
                                                    <option value="{{ $index }}" @if($property->type == 'data' and (explode('.', $property->value)[0] == $index)) selected @endif>{{ __($table) }}</option>
                                                @endforeach
                                                <option value="custom" @if($property->type == 'custom') selected @endif>{{ __('Custom') }}</option>
                                                <option value="settings" @if($property->type == 'data' and (explode('.', $property->value)[0] == 'settings')) selected @endif>{{ __('Settings') }}</option>
                                                <option value="special" @if($property->type == 'special') selected @endif>{{ __('Special') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control select2" id="value-{{$i}}" name="value-{{$i}}">
                                            </select>
                                            <input id="text-{{$i}}" class="form-control" style="display: none" type="text" name="text-{{$i}}" value="{{ isset(explode('.', $property->value)[1]) ? explode('.', $property->value)[1] : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control select2" id="format-{{$i}}" name="format-{{$i}}">
                                                <option value=""></option>
                                                <option value="uppercase" @if($property->format === 'uppercase') selected @endif>{{ __('Uppercase') }}</option>
                                                <option value="lowercase" @if($property->format === 'lowercase') selected @endif>{{ __('Lowercase') }}</option>
                                                <option value="capitalize" @if($property->format === 'capitalize') selected @endif>{{ __('Capitalize') }}</option>
                                                <option value="d" @if($property->format === 'd') selected @endif>01 -> 31</option>
                                                <option value="m" @if($property->format === 'm') selected @endif>01 -> 12</option>
                                                <option value="y" @if($property->format === 'y') selected @endif>99</option>
                                                <option value="Y" @if($property->format === 'Y') selected @endif>1999</option>
                                                <option value="Y-m-d" @if($property->format === 'Y-m-d') selected @endif>YYYY-MM-DD</option>
                                                <option value="d-m-Y" @if($property->format === 'd-m-Y') selected @endif>DD-MM-YYYY</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @php
                                                $items = explode('.', $property->name)
                                            @endphp
                                            <input type="text" readonly class="form-control" value="{{ array_pop($items) }}">
                                            <input type="hidden" name="name-{{$i}}" value="{{ $property->name }}">
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $i += 1;
                                @endphp
                                @endif
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            pdfTableChanged = (el) => {
                if ($(el).val() === 'custom') {
                    $('#' + $(el).attr('id').replace('table-', 'value-')).next(".select2-container").hide();
                    $('#' + $(el).attr('id').replace('table-', 'text-')).css('display', 'block')
                } else {
                    $('#' + $(el).attr('id').replace('table-', 'value-')).next(".select2-container").show();
                    $('#' + $(el).attr('id').replace('table-', 'text-')).css('display', 'none')

                    var data = {
                        action: 'documentBuilder.getFields',
                        payload: {
                            table: $(el).val()
                        }
                    };
                    app.appAjax('POST', data, app.ajaxRoute).then(function(data) {
                        $('#' + $(el).attr('id').replace('table-', 'value-')).empty()
                        const inputValue =  $('#' + $(el).attr('id').replace('table-', 'text-')).val();
                        data.extra.columns.forEach(item => {
                            const isSelected = item.name === inputValue;
                            $('#' + $(el).attr('id').replace('table-', 'value-')).append(
                                `<option value="${item.name}" ${isSelected ? 'selected' : ''}>${item.label}</option>`)
                        })
                    });
                }
            }

            $('.select2').change()


            var data = {
                action: 'documentBuilder.viewDocumentBuilder',
                payload: {
                    'document': {{$documentBuilder->id}}
                }
            };

            app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
                if (data.status == 200) {
                    console.log(JSON.parse(data.extra.document.properties));
                }
            }).fail(function (data) {
                toastr.error('Please, Try again later', 'Somthing went wrong!');
            })
        })
    </script>
@endsection

@props([
        'action'            => null,
        'icon'              => 'icon-trash',
        'showButtonText'    => true,
        'buttonText'        => __('Delete'),
        'item'              => 'item',
        'class'             => '',
        'elementId'         => ''
        ])
<div x-data='{
        initial: true,
        deleting: false,
        deleteItem : async function(route){
            event.preventDefault();
            var response = await(app.appAjax( "DELETE", [] , route))
            .done(function(response){
                return response;
            });
            if(response.response == "success"){
                $dispatch("item-deleted" , response);
                $dispatch("item-deleted-{{$item}}" , response);
            }
        },
    }'
    class="{{$class}}"
    >

    <button
        class="text-danger {{(!$icon) ? ' btn btn-light ' : 'icon-button'}}"
        x-on:click.prevent="deleting = true; initial = false"
        x-show="initial"
        x-on:deleting.window="$el.disabled = true"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"

    >


        @if($icon)
            <i class="{{$icon}}"></i>
        @endif

        @if($showButtonText)
            {{ $buttonText }}
        @endif
    </button>
    <div
        x-show="deleting"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
    >
        <strong class="mr-2">{{__('Are you sure?')}}</strong>

        <button
            class="btn btn-xs btn-danger"
            x-on:click.prevent="deleteItem({{ $action ? "'$action'" : '$store.deleted.link'}})"
            x-on:deleting.window="$el.disabled = true"
            type="submit">
            {{__('Yes')}}
        </button>

        <button
            class="btn btn-xs btn-light"
            x-on:click.prevent="deleting = false; $store.deleted.link = null; setTimeout(() => { initial = true }, 150)"
            x-on:deleting.window="$el.disabled = true">
            {{ __('No') }}
        </button>
    </div>
</div>

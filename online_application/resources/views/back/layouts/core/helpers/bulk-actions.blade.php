<div class="btn-group" style="margin-left:-20px ">
    <div class="btn btn-light">
        <input type="checkbox" name="select_all" value="" onchange="app.toggleSelectAll(this)"/>
    </div>
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split toggle-bulk-actions"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
            <span class="sr-only"></span>
        </button>
        <div class="dropdown-menu">
            @if (isset($edit) and isset($edit['allowed']) and $edit['allowed'])
                <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                    onclick="app.bulkEdit('{{$edit['route']}}' , '{{$edit['title']}}')">
                    <i class="fas fa-pencil-alt mr-1"></i> {{__('Edit Selected')}}
                </a>
            @endif

            @if (isset($delete) and isset($delete['allowed']) and $delete['allowed'])
                <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                   onclick="app.bulkDelete('{{$delete['route']}}'@if(isset($delete['reloadOnDelete']) and $delete['reloadOnDelete']) ,true @endif);">
                    <i class="fas fa-trash-alt mr-1 text-danger"></i> {{__('Delete Selected')}}
                </a>
            @endif

            @foreach($buttons as $button)

                @if (isset($button['allowed']) && $button['allowed'])
                    <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                    {{$button['action']}}>
                        <i class="{{$button['icon']}} mr-1"></i> {{__($button['title'])}}
                    </a>
                @endif
            @endforeach

            @if ( \Request::route()->getName() == 'submissions.index')
                <a id="bulk-archive" class="dropdown-item bulk-action-item" href="javascript:void(0)"
                    onclick="app.bulkArchive()">
                    <img src="/assets/images/archive.svg" class="custom-icon" width="12px" alt="Archive"> {{__('Archive')}}
                </a>
            @endif

            @if ( \Request::route()->getName() == 'submissions.index')
                <a id="bulk-activate" class="dropdown-item bulk-action-item hide" href="javascript:void(0)"
                    onclick="app.bulkUnarchive()">
                    <img src="/assets/images/restore.svg" class="custom-icon" width="12px" alt="Restore"> {{__('Restore')}}
                </a>
            @endif
        </div>
</div>

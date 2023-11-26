<div class="row">
    @foreach($customFields as $customfield)

        @include('back.programs._partials.customfields.' . $customfield->field_type . '-data', [
            'customfield' => $customfield,
        ])

    @endforeach
</div>

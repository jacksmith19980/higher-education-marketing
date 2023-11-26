<section class="pricing-line">
    @foreach ($schemas as $schema)
        <div>
            <div class="{{ ($schema['value'] === '0' or !$schema['value']) ? 'normal-line' : (str_starts_with($schema['value'], '-') ? 'minus-line' : 'plus-line') }}">{{ $schema['value'] }}
            </div>
            <div style="padding: 10px 15px; text-align: center;">{{ $schema['week'] }}</div>
        </div>
    @endforeach
</section>

<style>
    .pricing-line {
        display: flex;
        overflow: auto;
    }

    .normal-line {
        padding: 10px 15px;
        border-bottom: 2px solid black;
        text-align: center;
    }

    .plus-line {
        padding: 10px 15px;
        border-bottom: 2px solid green;
        text-align: center;
    }

    .minus-line {
        padding: 10px 15px;
        border-bottom: 2px solid red;
        text-align: center;
    }
</style>

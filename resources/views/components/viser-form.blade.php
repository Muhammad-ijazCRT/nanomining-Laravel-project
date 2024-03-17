@foreach ($formData as $data)
    <div class="form-group">
        <label class="form-label">{{ __($data->name) }}</label>
        @if ($data->type == 'text')
            <input @if ($data->is_required == 'required') required @endif class="form-control form--control" name="{{ $data->label }}" type="text" value="{{ old($data->label) }}">
        @elseif($data->type == 'textarea')
            <textarea @if ($data->is_required == 'required') required @endif class="form-control form--control" name="{{ $data->label }}">{{ old($data->label) }}</textarea>
        @elseif($data->type == 'select')
            <select @if ($data->is_required == 'required') required @endif class="form-control form--control select" name="{{ $data->label }}">
                <option value="">@lang('Select One')</option>
                @foreach ($data->options as $item)
                    <option @selected($item == old($data->label)) value="{{ $item }}">{{ __($item) }}</option>
                @endforeach
            </select>
        @elseif($data->type == 'checkbox')
            @foreach ($data->options as $option)
                <div class="form-check">
                    <input class="form-check-input" id="{{ $data->label }}_{{ titleToKey($option) }}" name="{{ $data->label }}[]" type="checkbox" value="{{ $option }}">
                    <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                </div>
            @endforeach
        @elseif($data->type == 'radio')
            @foreach ($data->options as $option)
                <div class="form-check">
                    <input @checked($option == old($data->label)) class="form-check-input" id="{{ $data->label }}_{{ titleToKey($option) }}" name="{{ $data->label }}" type="radio" value="{{ $option }}">
                    <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                </div>
            @endforeach
        @elseif($data->type == 'file')
            <input @if ($data->is_required == 'required') required @endif accept="@foreach (explode(',', $data->extensions) as $ext) .{{ $ext }}, @endforeach" class="form-control form--control" name="{{ $data->label }}" type="file">
            <pre class="text--base mt-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
        @endif
    </div>
@endforeach

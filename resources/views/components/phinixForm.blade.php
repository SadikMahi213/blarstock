@foreach($formData as $data)
    <div class="col-12 mb-4">
        <label class="form--label mb-2 @if($data->is_required == 'required') required @endif">{{ __($data->name) }}</label>
        @if($data->type == 'text')
            <input type="text"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'textarea')
            <textarea
                class="form--control"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
            >{{ old($data->label) }}</textarea>
        @elseif($data->type == 'select')
            <select
                class="form--control form--select"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
            >
                <option value="">@lang('Select One')</option>
                @foreach ($data->options as $item)
                    <option value="{{ $item }}" @selected($item == old($data->label))>{{ __($item) }}</option>
                @endforeach
            </select>
        @elseif($data->type == 'checkbox')
            <div class="d-flex flex-wrap column-gap-4 row-gap-3">
                @foreach($data->options as $option)
                    <div class="form-check form--switch d-inline-flex gap-1">
                        <input
                            class="form-check-input"
                            name="{{ $data->label }}[]"
                            type="checkbox"
                            value="{{ $option }}"
                            id="{{ $data->label }}_{{ titleToKey($option) }}"
                        >
                        <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
        @elseif($data->type == 'radio')
            <div class="d-flex flex-wrap column-gap-4 row-gap-3">
                @foreach($data->options as $option)
                    <div class="form--radio">
                        <input
                        class="form-check-input"
                        name="{{ $data->label }}"
                        type="radio"
                        value="{{ $option }}"
                        id="{{ $data->label }}_{{ titleToKey($option) }}"
                        @checked($option == old($data->label))
                        >
                        <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
        @elseif($data->type == 'file')
            <input
            type="file"
            class="form--control"
            name="{{ $data->label }}"
            @if($data->is_required == 'required') required @endif
            accept="@foreach(explode(',',$data->extensions) as $ext) .{{ $ext }}, @endforeach"
            >
            <pre class="text--base mt-2 mb-0 lh-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
        @elseif($data->type == 'email')
            <input type="email"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'url')
            <input type="url"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'number')
            <input type="number"
            step="any"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'datetime')
            <input type="datetime-local"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'date')
            <input type="date"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'time')
            <input type="time"
            class="form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @endif
    </div>
@endforeach

@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'model' => '',
    'placeholder' => '',
    'required' => true,
    'options' => [],
    'errorKey' => '',
    'disabled' => false,
])

@php
    $model = $model ?: "form.{$name}";
    $errorKey = $errorKey ?: $name;
    $baseClasses = 'w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors duration-200';
    $errorClasses = 'border-secondary ring-2 ring-secondary/20';
@endphp

<div>
    <label class="block text-sm font-medium text-text-main mb-1">
        {{ $label }} @if($required) <span class="text-secondary">*</span> @endif
    </label>

    @if($type === 'select')
        <select x-model="{{ $model }}"
                @if($disabled) :disabled="true" @endif
                class="{{ $baseClasses }} bg-white cursor-pointer"
                :class="errors.{{ $errorKey }} ? '{{ $errorClasses }}' : ''">
            @foreach($options as $option)
                @if(is_array($option))
                    <option value="{{ $option['value'] }}" @if(isset($option['selected']) && $option['selected']) selected @endif>{{ $option['label'] }}</option>
                @else
                    <option value="{{ $option }}">{{ $option }}</option>
                @endif
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea x-model="{{ $model }}"
                  @if($disabled) :disabled="true" @endif
                  rows="{{ $rows ?? 3 }}"
                  class="{{ $baseClasses }}"
                  :class="errors.{{ $errorKey }} ? '{{ $errorClasses }}' : ''"
                  placeholder="{{ $placeholder }}"></textarea>
    @elseif($type === 'checkbox')
        <label class="flex items-center gap-2">
            <input type="checkbox" x-model="{{ $model }}"
                   class="w-4 h-4 text-primary rounded focus:ring-primary cursor-pointer">
            <span class="text-sm font-medium text-text-main">{{ $label }}</span>
        </label>
    @else
        <input type="{{ $type }}" x-model="{{ $model }}"
               @if($disabled) :disabled="true" @endif
               class="{{ $baseClasses }}"
               :class="errors.{{ $errorKey }} ? '{{ $errorClasses }}' : ''"
               placeholder="{{ $placeholder }}">
    @endif

    <p x-show="errors.{{ $errorKey }}" class="text-secondary text-xs mt-1" x-text="errors.{{ $errorKey }}"></p>
</div>

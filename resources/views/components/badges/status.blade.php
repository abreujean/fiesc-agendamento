@props([
    'condition' => 'true',
    'trueLabel' => '',
    'falseLabel' => '',
    'trueClass' => 'bg-primary/10 text-primary',
    'falseClass' => 'bg-bg-main text-text-muted',
])

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
      :class="{{ $condition }} ? '{{ $trueClass }}' : '{{ $falseClass }}'"
      x-text="{{ $condition }} ? '{{ $trueLabel }}' : '{{ $falseLabel }}'"></span>

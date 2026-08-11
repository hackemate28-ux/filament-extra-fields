@php
    $statePath = $getStatePath();
    $max = $getMax();
    $clearable = $isClearable();
    $disabled = $isDisabled();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            hover: 0,
            lit(i) { return (this.hover || Number(this.state) || 0) >= i },
            pick(i) {
                @if ($disabled)
                    return;
                @elseif ($clearable)
                    this.state = (Number(this.state) === i ? null : i);
                @else
                    this.state = i;
                @endif
            },
        }"
        class="fi-star-rating"
        style="display:inline-flex; align-items:center; gap:.125rem;"
        role="radiogroup"
    >
        @for ($i = 1; $i <= $max; $i++)
            <button
                type="button"
                @disabled($disabled)
                role="radio"
                :aria-checked="Number(state) === {{ $i }}"
                aria-label="{{ $i }}"
                @unless ($disabled)
                    x-on:mouseenter="hover = {{ $i }}"
                    x-on:mouseleave="hover = 0"
                    x-on:click="pick({{ $i }})"
                @endunless
                style="background:none; border:0; padding:.0625rem; line-height:0; cursor:{{ $disabled ? 'default' : 'pointer' }};"
                x-bind:style="lit({{ $i }}) ? 'color:#f59e0b' : 'color:#d1d5db'"
            >
                <svg viewBox="0 0 20 20" fill="currentColor" style="width:1.5rem; height:1.5rem;" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.951.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.446a1 1 0 00-1.176 0l-3.366 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.98 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.958z"/>
                </svg>
            </button>
        @endfor
    </div>
</x-dynamic-component>

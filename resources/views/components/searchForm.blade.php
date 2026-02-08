@props([
    'placeholder' => 'Search...',
    'dateSearch'  => 'no',
    'keySearch'   => 'yes',
])

<form action="" method="GET" class="d-flex flex-wrap gap-2 justify-content-center">
    @if ($keySearch == 'yes')
        <x-searchKeyField placeholder="{{ $placeholder }}" />
    @endif

    @if ($dateSearch == 'yes')
        <x-searchDateField />
    @endif
</form>
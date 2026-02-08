@props(['placeholder' => 'Search...'])

<div class="input--group">
    <input type="search" class="form--control form--control--sm" name="search" value="{{ request()->search }}" placeholder="{{ __($placeholder) }}">
    <button class="btn btn--sm btn--icon btn--base" type="submit"><i class="ti ti-search"></i></button>
</div>
@foreach ($followings as $following)
    <div class="user-list__item">
        <a href="{{ route('author.profile', [encrypt($following->id), slug($following->author_name)]) }}" class="user-list__item__img">
            <img src="{{ getImage(getFilePath('userProfile'). '/' . $following->image, getFileSize('userProfile')) }}" alt="{{ __($following->author_name) }}">
        </a>
        <div class="user-list__item__txt">
            <span>{{ formatNumber($following->approved_images_count) }}</span>
            <span>@lang('Resources')</span>
        </div>
    </div>
@endforeach
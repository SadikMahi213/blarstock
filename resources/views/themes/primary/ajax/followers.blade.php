@foreach ($followers as $follower)
    @if ($follower->author_status == ManageStatus::AUTHOR_APPROVED)
        <div class="user-list__item">
            <a href="{{ route('author.profile', [encrypt($follower->id), slug($follower->author_name)]) }}" class="user-list__item__img">
                <img src="{{ getImage(getFilePath('userProfile') . '/' . $follower->image, getFileSize('userProfile')) }}" alt="{{ __($follower->author_name) }}">
            </a>
            <div class="user-list__item__txt">
                <span>{{ formatNumber($follower->approved_images_count) }}</span>
                <span>@lang('Resource')</span>
            </div>
        </div>
    @else
        <div class="user-list__item">
            <a href="{{ route('member.user.profile', [encrypt($follower->id), slug($follower->username)]) }}" class="user-list__item__img">
                <img src="{{ getImage(getFilePath('userProfile') . '/' . $follower->image, getFileSize('userProfile')) }}" alt="{{ __($follower->username) }}">
            </a>
            <div class="user-list__item__txt">
                <span>{{ __(strLimit($follower->username, 15)) }}</span>
            </div>
        </div>
    @endif    
@endforeach
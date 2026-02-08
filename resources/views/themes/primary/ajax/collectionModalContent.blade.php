<div class="collection-modal">
    <div class="collection-modal__thumb">
        @if ($asset->image_name)
            <img src="{{ imageUrl(getFilePath('stockImage'), $asset->image_name) }}" alt="Image">
        @elseif ($asset->video)
            <video src="{{ videoFileUrl($asset->video) }}" alt="video">
        @endif
    </div>
    <div class="collection-modal__content">
         <div class="modal-header">
              <h3 class="modal-title fs-5" id="addToCollectionModalLabel">@lang('Add to collection')</h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="{{ route('user.collection.add.image') }}" method="POST">
               @csrf

              <input type="hidden" name="asset_id" value="{{ $asset->id }}" required>
              <input type="hidden" name="user_id" value="{{ $user->id }}" required>

              <div class="modal-body">
                   <div class="collection-modal__list">
                        <ul class="collectionUl">
                             @foreach($collections as $collection)
                                @php
                                    $collected = $collection->collectionImages->where('image_id', $asset->id)->first();       
                                @endphp

                                <li class="collection-item {{ $collected ? 'selected' : '' }}" data-collection-id="{{ $collection->id }}">
                                    @if ($collected)
                                        <input type="hidden" name="collection_ids[]" value="{{ $collection->id }}">
                                    @endif

                                    <span class="collection-modal__list__txt">{{ __($collection->title) }}</span>
                                    <span class="collection-modal__list__action">
                                        <i class="ti {{ $collected ? 'ti-circle-minus text--danger' : 'ti-circle-plus' }}"></i>
                                    </span>
                                </li>
                             @endforeach
                        </ul>
                   </div>
                   <div class="input--group">
                        <input type="text" class="form--control form--control--sm" name="title" placeholder="@lang('Create a new collection')">
                        <button type="button" class="btn btn--sm btn--base addCollectionBtn">@lang('Add Collection')</button>
                   </div>
              </div>
              <div class="modal-footer">
                   <button type="submit" class="btn btn--sm btn--base w-100">@lang('Submit') <i class="ti ti-arrow-up-right"></i></button>
              </div>
         </form>
    </div>
</div>
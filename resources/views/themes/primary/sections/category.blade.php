<!-- ========== Category Section Start ========== -->
@php
    $categories = App\Models\Category::active()->orderBy('name')->get();
@endphp

<section class="category py-120">
    <div class="container">
         <div class="category__slider">
               @foreach ($categories as $category)
                    <a href="{{ route('all.assets', ['category_id' => $category->id]) }}" class="category__card">
                         <span class="category__card__icon">
                              <img src="{{ getImage(getFilePath('categories') . '/' . $category->image) }}" alt="{{ __($category->name) }}">
                         </span>
                         <span class="category__card__txt" title="{{ __($category->name) }}">{{ __(strLimit($category->name, 15)) }}</span>
                    </a>     
               @endforeach
         </div>
    </div>
</section>
<!-- ========== Category Section End ========== -->
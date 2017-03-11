@if (!empty($recipe))
@php
    $class_blur = '';

    if (!empty($recipe->image)) {
        $image = $recipe->image;
        $class_blur = ' image-blur';
    } else {
        $image = 'blank.gif';
    }

    $image_color = (!empty($recipe->color)) ? 'style="background-color:'.$recipe->color.';" ' : '';
@endphp
<div class="recipe card">
    <div class="image">
        <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
            <img {!! $image_color !!}class="card-img-top img-fluid{{$class_blur}}" data-original="{{ route('imagecache', ['template' => 'lists', 'filename' => $image]) }}" src="{{ route('imagecache', ['template' => 'lists-tiny', 'filename' => $image]) }}" alt="">
        </a>
    </div>
    <div class="card-block">
        <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
            <h4 class="card-title">{{ $recipe->title }}</h4>
        </a>
        <p class="card-text">{{ Helper::nl2empty($recipe->description).' '.Helper::nl2empty($recipe->directions) }}</p>
    </div>
    @if (!empty($recipe->username))
    <div class="card-footer">
        <small class="text-muted">
            @php $author = (!empty($recipe->display_name)) ? $recipe->display_name : $recipe->username; @endphp
            Recipe by <a href="{{ route('user-profile.show', ['username' => $recipe->username]) }}">{{ $author }}</a>
        </small>
    </div>
    @endif
</div>
@endif
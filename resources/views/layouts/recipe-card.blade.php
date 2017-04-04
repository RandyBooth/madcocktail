@if (!empty($recipe))
    @php
        $class_blur = '';

        if (!empty($recipe->image)) {
            $image = $recipe->image;
            $class_blur = ' image-blur';
        } else {
            $image = 'default-cocktail-glass.jpg';
        }

        $image_color = (!empty($recipe->color)) ? 'style="background-color:'.$recipe->color.';" ' : '';
    @endphp
<div class="recipe card">
    <div class="image">
        <a href="{{ route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug]) }}">
            <img {!! $image_color !!}class="card-img-top img-fluid{{$class_blur}}" data-original="{{ route('imagecache', ['template' => 'lists', 'filename' => $image]) }}" src="{{ route('imagecache', ['template' => 'lists-tiny', 'filename' => $image]) }}" alt="">
        </a>
        <form role="form" @if(Auth::check()) class="recipe-favorite login-recipe-favorite" method="POST" @else class="recipe-favorite" method="GET" @endif action="{{ route('login') }}">

            @if(Auth::check())
            <div class="hidden-xs-up">
                <input type="text" name="id" value="{{ $recipe->token }}">
            </div>
            @endif
            <button type="submit" class="btn-reset recipe-favorite-btn @if(!empty($favorite_recipes)){{ isset($favorite_recipes[$recipe->id]) ? 'recipe-favorite-btn-on' : '' }}@endif"><i class="fa fa-heart-o" aria-hidden="true"></i></button>
        </form>
    </div>
    <div class="card-block">
        <a href="{{ route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug]) }}">
            <h4 class="card-title">{{ $recipe->title }}</h4>
        </a>
        <p class="card-text">{{ Helper::nl2empty($recipe->description).' '.Helper::nl2empty($recipe->directions) }}</p>
    </div>
    @if (!empty($recipe->username))
    <div class="card-footer">
        <small class="text-muted">
            @php $author = (!empty($recipe->user_display_name)) ? $recipe->user_display_name : $recipe->username; @endphp
            <div class="d-flex align-items-center">
                <a class="d-flex" href="{{ route('user-profile.show', ['username' => $recipe->username]) }}">
                @if (!empty($recipe->user_image))
                    <img class="image-icon image-icon-small d-inline-block" src="{{ route('imagecache', ['template' => 'user-small', 'filename' => $recipe->user_image]) }}" alt="">
                @else
                    <span style="background-color: {{ $random_color[$recipe->user_id] ?: Helper::random_color() }};" class="image-icon image-icon-small d-inline-block"></span>
                @endif
                </a>

                <span class="ml-2">Recipe by <a href="{{ route('user-profile.show', ['username' => $recipe->username]) }}">{{ $author }}</a></span>
            </div>
        </small>
    </div>
    @endif
</div>
@endif
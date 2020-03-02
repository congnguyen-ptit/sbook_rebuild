<div class="slider-area">
    <div class="slider-active owl-carousel">
        <div class="single-slider pt-125 pb-130 bg-img" style="background-image:url({{ asset(config('view.image_paths.slide') . $banners[0]['value']) }});">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="slider-content slider-animated-1 text-center">
                            @if (isset($textBanners) && count($textBanners) >0)
                                <h3 class="invisible">{{ $textBanners[1]['value'] }}</h3>
                                <h1 class="color-banner">{{ $textBanners[0]['value'] }}</h1>
                            @endif
                            <a href="{{ route('books.create') }}" class="{{ Auth::check() ? '' : 'login' }}">{{ __('settings.shareBook') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-slider slider-h1-2 pt-215 pb-100 bg-img" style="background-image:url({{ asset(config('view.image_paths.slide') . $banners[1]['value']) }});">
            <div class="container">
                <div class="slider-content slider-content-2 slider-animated-1">
                    @if (isset($textBanners) && count($textBanners) >0)
                        <h1>{{ $textBanners[2]['value'] }}</h1>
                        <h2>{{ $textBanners[3]['value'] }}</h2>
                    @endif
                    <a href="{{ route('books.create') }}" class="{{ Auth::check() ? '' : 'login' }}">{{ __('settings.shareBook') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

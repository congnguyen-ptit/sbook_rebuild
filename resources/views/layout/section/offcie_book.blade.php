@if($item['books'])
    <div class="product-total-2">
        @foreach($item['books'] as $book)
            <div class="single-most-product bd mb-18 office-book">
                <div class="most-product-img">
                    <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                        @if($book->medias->count() > 0)
                            <img src="{{ asset(config('view.image_paths.book') . $book->medias[0]->path) }}" alt="book" class="office-book-img" />
                        @else
                            <img src="{{ asset(config('view.image_paths.book') . 'default.jpg') }}" alt="woman" class="office-book-img" />
                        @endif
                    </a>
                </div>
                <div class="most-product-content">
                    <div class="product-rating">
                        {!! Form::select('rating',
                           [
                                '' => '',
                                '1' => 1,
                                '2' => 2,
                                '3' => 3,
                                '4' => 4,
                                '5' => 5
                            ],
                            null,
                            [
                                'class' => 'rating',
                                'data-rating' => $book->avg_star
                            ])
                        !!}
                    </div>
                    <h4><a href="{{ route('books.show', $book->slug . '-' . $book->id) }}" title="{{ $book->title }}">{{ $book->title }}</a></h4>
                </div>
            </div>
        @endforeach
    </div>
@endif

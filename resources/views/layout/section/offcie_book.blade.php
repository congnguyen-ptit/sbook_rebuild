@if($item['books'])
    <div class="product-total-2">
        @foreach($item['books'] as $book)
            <div class="single-most-product bd mb-18 office-book">
                <div class="most-product-img">
                    <a href="{{ route('books.show', $book->slug . '-' . $book->id) }}">
                        <img src="{{ mediaBook($book) }}" alt="{{ $book->title }}" class="office-book-img" />
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

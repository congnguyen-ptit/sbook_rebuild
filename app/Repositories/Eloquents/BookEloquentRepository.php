<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Book;
use App\Repositories\Contracts\BookRepository;
use App\Eloquent\Category;
use App\Eloquent\Media;
use Carbon\Carbon;
use App\Eloquent\Bookmeta;
use App\Eloquent\Owner;
use App\Eloquent\User;
use App\Eloquent\Review;
use App\Eloquent\Office;
use Illuminate\Support\Facades\DB;

class BookEloquentRepository extends AbstractEloquentRepository implements BookRepository
{
    protected $onlyAttributes = [
        'id',
        'title',
        'description',
        'avg_star',
        'slug',
        'author',
        'sku',
        'count_viewed',
    ];

    public function model()
    {
        return new Book;
    }

    public function store($data = [])
    {
        $data['sku'] = strtotime(Carbon::now());
        $content = strip_tags($data['description'], config('view.text'));
        $data['description'] = preg_replace('/ style=("|\')(.*?)("|\')/', '', $content);
        $book = $this->model()->create($data);

        $countReview['key'] = 'count_review';
        $countReview['value'] = 0;
        $countReview['book_id'] = $book->id;
        app(Bookmeta::class)->create($countReview);

        return $book;
    }

    public function find($id, $with = ['medias', 'categories'], $dataSelect = ['*'])
    {
        return $this->model()->with($with)->findOrFail($id);
    }

    public function update($id, $userId, $data = [])
    {
        $book = $this->model()->findOrFail($id);
        $firstOwner = $book->owners[0]->id;
        if ($userId == $firstOwner) {
            $book->update($data);
        } else {
            $isClone = app(Bookmeta::class)
                ->where(
                    [
                        'key' => 'clone_book',
                        'value' => $userId,
                    ]
                )->first();
            if ($isClone) {
                $book = $this->model()->findOrFail($isClone->book_id);
                $book->update($data);
            } else {
                $data = array_add($data, 'sku', $book->sku);
                $book = $this->store($data);
                app(Owner::class)->create([
                    'user_id' => $userId,
                    'book_id' => $book->id,
                ]);
                app(Bookmeta::class)->create([
                    'key' => 'clone_book',
                    'value' => $userId,
                    'book_id' => $book->id,
                ]);
            }
        }

        return $book;
    }

    public function destroy($id)
    {
        $model = $this->model()->findOrFail($id);

        return $model->delete();
    }

    public function getData($with = [], $data = [], $dataSelect = ['*'], $attribute = ['id', 'desc'], $take = [])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->orderBy($attribute[0], $attribute[1])
            ->take($take)
            ->get();
    }

    public function getBookPaginate($with = [], $data = [], $dataSelect = ['*'], $attribute = ['id', 'desc'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->orderBy($attribute[0], $attribute[1])
            ->paginate(config('view.paginate.book'));
    }

    public function getLatestBook($with = [], $data = [], $take = [])
    {
        $attribute = [
            'created_at',
            'desc',
        ];
        $books = $this->getBookByAttrs($attribute);

        return $books;
    }

    public function getTopReviewBook($data = [], $take = [])
    {
        $with = [
            'book',
            'book.medias',
            'book.owners.office',
            'book.countReview',
            'book.categories',
        ];
        $bookmeta = app(Bookmeta::class)->where('key', 'count_review')
            ->orderBy('value', 'desc')
            ->take($take)
            ->whereHas('book', function ($query) {
                $query->where('deleted_at', null);
            })
            ->with($with)
            ->get();
        $books = [];
        if (!empty($bookmeta)) {
            foreach ($bookmeta as $value) {
                array_push($books, $value->book);
            }

            return $books;
        }

        return null;
    }

    public function getTopViewedBook($with = [], $data = [], $take = [])
    {
        $attribute = [
            'count_viewed',
            'desc',
        ];
        $books = $this->getBookByAttrs($attribute);

        return $books;
    }

    public function getTopInterestingBook($with = [], $data = [], $take = [])
    {
        $attribute = [
            'avg_star',
            'desc',
        ];
        $books = $this->getBookByAttrs($attribute);

        return $books;
    }

    public function getBestSharing($with = [], $data = [])
    {
        $users = app(Owner::class)->select('user_id', DB::raw('count(book_id) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take(config('view.taking_numb.author'))
            ->with(['user.office'])
            ->get();

        return $users;
    }

    public function getOfficeBooks($offices, $with = [])
    {
        $data = collect();
        foreach ($offices as $key => $value) {
            $ids = app(Bookmeta::class)
                ->where('key', $value)
                ->where('value', '>', 0)
                ->pluck('book_id');
            $data->push(
                [
                    'id' => $key,
                    'office' => $value,
                    'books' => $this->model()
                        ->select($this->onlyAttributes)
                        ->with($with)
                        ->whereIn('id', $ids)
                        ->inRandomOrder()
                        ->take(config('view.random_numb.book'))
                        ->get(),
                ]
            );
        }

        return $data;
    }

    public function getBookCategory($id)
    {
        $books = app(Category::class)->findOrFail($id)->books();

        return $books;
    }

    public function getBookOffice($data, $with = [])
    {
        $string = strstr($data, '-', true);
        $data = $string ? $string : $data;
        $office = app(Office::class)::where('name', 'like', '%' . $data . '%')->first();

        $ids = app(Bookmeta::class)
            ->where('key', $office->name)
            ->where('value', '>', 0)
            ->pluck('book_id');
        $books = $this->model()
            ->select($this->onlyAttributes)
            ->with($with)
            ->whereIn('id', $ids)
            ->paginate(config('view.paginate.book'));

        return $books;
    }

    public function getRelatedBooks($bookIds, $with = [])
    {
        return $this->model()
            ->select($this->onlyAttributes)
            ->with($with)
            ->orderBy('created_at', 'desc')
            ->whereIn('id', $bookIds)
            ->get();
    }

    public function search($attribute, $data, $page, $with = [])
    {
        return $this->model()
            ->select($this->onlyAttributes)
            ->with($with)
            ->fullTextSearch($attribute, $data)
            ->paginate(config('view.pageFull'), ['*'], $page);
    }

    public function searchPage($attribute, $data)
    {
        return $this->model()
            ->select($this->onlyAttributes)
            ->fullTextSearch($attribute, $data)
            ->get();
    }

    public function updateStar($data, $id)
    {
        $book = $this->model()->findOrFail($id);

        $star = 0;
        if (isset($data) && $data != null) {
            foreach ($data as $review) {
                $star += $review->star;
            }

            if ($data->count() > 0) {
                $avgStar['avg_star'] = $star / count($data);
            } else {
                $avgStar['avg_star'] = $star;
            }

            return $book->update($avgStar);
        }
    }

    public function countBook()
    {
        return $this->model()->count();
    }

    public function updateBook($id, $data)
    {
        $book = $this->model()->find($id);
        $description = strip_tags($data['description'], config('view.text'));
        $data['description'] = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $description);
        $book->update($data);

        return $book;
    }

    public function getBookByAttrs($attribute = [])
    {
        $with = [
            'medias',
            'owners.office',
            'countReview',
            'categories',
        ];
        $take = config('view.taking_numb.latest_book');

        return $this->getData($with, [], $this->onlyAttributes, $attribute, $take);
    }

    public function getTopBorrowed($with = [], $data = [], $take = [])
    {
        $attribute = [
            'avg_star',
            'desc',
        ];

        return $this->setCacheBorrowed($attribute);
    }

    public function getDatas($with = [], $data = [], $dataSelect = ['books.*'], $attribute = ['id', 'desc'], $take = [])
    {
        return $this->model()
            ->select('books.*')
            ->join('bookmeta', 'books.id', '=', 'bookmeta.book_id')
            ->where('key', 'count_returned')
            ->with($with)
            ->orderBy($attribute[0], $attribute[1])
            ->take($take)
            ->get();
    }

    public function setCacheBorrowed($attribute = [])
    {
        $with = [
            'medias',
            'countReview',
        ];
        $take = config('view.taking_numb.latest_book');

        return $this->getDatas($with, [], $this->onlyAttributes, $attribute, $take);
    }

    public function getYearsFilterArray($book)
    {
        $years = [];
        $nowYear = now()->year;
        $createdYear = Carbon::createFromFormat('Y-m-d H:i:s', $book->created_at)->year;
        $years[] = $createdYear;

        $i = 1;
        while ($createdYear + $i <= $nowYear) {
            $years[] = $createdYear + $i;
            $i++;
        }

        return $years;
    }

    public function getStatisticBook($id, $year)
    {
        $months = trans('settings.statisticMonths');
        $reviews = ['Reviews'];
        for ($month = 1; $month <= 12; $month++) {
            $reviewsCount = Review::where('book_id', $id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $reviews[$month] = $reviewsCount;
        }
        $data = [
            'label_x' => trans('settings.bookStatisticChart.labelX'),
            'label_y' => trans('settings.bookStatisticChart.labelY'),
            'months' => $months,
            'reviews' => $reviews
        ];

        return $data;
    }

    public function getAuthors($tern)
    {
        return $this->model()
                    ->select('author')
                    ->where('author', 'like', '%' . $tern . '%')
                    ->distinct()
                    ->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_title',
        'book_author',
        'book_genre',
        'description',
        'reference',
        'code',
        'user_id_owner',
        'book_cover',
    ];

    protected $appends = ['image'];

    /**
     * A given book has one owner.
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'user_id_owner', 'id');
    }

    /**
     * A given book has checkout information.
     */
    public function bookCheckout(): HasMany
    {
        return $this->hasMany(BookCheckout::class);
    }

    /**
     * A given book has many comments.
     */
    public function bookComments(): HasMany
    {
        return $this->hasMany(BookComments::class);
    }

    public static function generateUniqueReference()
    {
        $min = 00000; // Minimum 5-digit number
        $max = 99999; // Maximum 5-digit number

        // Generate a unique 5-digit reference
        $reference = random_int($min, $max);

        // Ensure the generated reference is unique
        while (self::where('reference', $reference)->exists()) {
            $reference = random_int($min, $max);
        }

        return $reference;
    }

    /**
     * See if the book is currently checked out
     */
    public static function isBookCheckedOut($book)
    {
        return $book->bookCheckout->whereNull('checkin_date')->isNotEmpty();
    }

    /**
     * See if the book is checked out to the current user
     */
    public static function isBookWithUser($book)
    {
        if (Auth::user()) {
            return $book->bookCheckout->where('user_id', Auth::user()->id)->whereNull('checkin_date')->isNotEmpty();
        }
    }

    /**
     * See if the book was last checked out by the current user
     */
    public static function wasBookLastWithUser($book)
    {
        if (Auth::user()) {
            return $book->bookCheckout()
                ->latest('checkin_date')
                ->get()
                ->where('user_id', Auth::user()->id)
                ->whereNotNull('checkin_date')->isNotEmpty();
        }
    }

    public function getImageAttribute() {
        return asset('storage/'.$this->book_cover);
    }

}

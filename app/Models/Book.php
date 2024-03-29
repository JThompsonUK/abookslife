<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use function PHPUnit\Framework\isNull;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'book_title',
        'book_author',
        'book_genre',
        'description',
        'published',
        'reference',
        'user_id_owner',
        'book_cover',
    ];

    protected $appends = ['image'];
    // protected $guarded = ['id']; // Make sure 'id' is guarded or fillable

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * Generate a UUID before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = Str::uuid();
        });
    }

    // use HasUuids;


    /**
     * Use 'uuid' instead of 'id' when resolving routes.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

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

    /**
     * Generate a random string with a total length of 5 (text and digits)
     */
    public static function generateUniqueReference()
    {
        $randomString = self::generateRandomCharacters(5);

        // Ensure the generated reference is unique
        while (self::where('reference', $randomString)->exists()) {
            // Regenerate if not unique
            $randomString = self::generateRandomCharacters(6);
        }

        return $randomString;
    }

    private static function generateRandomCharacters($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    /**
     * See if the book is currently checked out
     */
    public function isBookCheckedOut()
    {
        return $this->bookCheckout->whereNull('checkin_date')->isNotEmpty();
    }

    /**
     * See if the book is checked out to the current user
     */
    public function isBookWithUser()
    {
        if (Auth::user()) {
            return $this->bookCheckout->where('user_id', Auth::user()->id)->sortByDesc('created_at')->first();
        }
    }

    /**
     * Get the locations for each checkout
     */
    public function checkoutMarkers()
    {
        $markers = $this->bookCheckout->map(function ($book) {
            return [
                'lat' => floatval($book->lat),
                'lng' => floatval($book->lng),
            ];
        })->toArray();

        return $markers;
    }

    public function getImageAttribute() {
        if ( $this->book_cover ) {
            return asset('storage/images/' . basename($this->book_cover));
        };
    }
    
}

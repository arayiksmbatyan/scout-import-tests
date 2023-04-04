<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements TypesenseDocument
{
    use HasApiTokens, HasFactory, Notifiable, Searchable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_merge(
            $this->toArray(),
            [
                // Cast id to string and turn created_at into an int32 timestamp
                // in order to maintain compatibility with the Typesense index definition below
                'id' => (string) $this->id,
                'created_at' => $this->created_at->timestamp,
            ]
        );
    }

     /**
     * The Typesense schema to be created.
     *
     * @return array
     */
    public function getCollectionSchema(): array {
        return [
            'name' => $this->searchableAs(),
            'fields' => [
                [
                    'name' => 'id',
                    'type' => 'string',
                ],
                [
                    'name' => 'name',
                    'type' => 'string',
                ],
                [
                    'name' => 'email',
                    'type' => 'string',
                    'facet' => true,
                ],
                [
                    'name' => 'created_at',
                    'type' => 'int64',
                ],
            ],
            'default_sorting_field' => 'created_at',
        ];
    }

     /**
     * The fields to be queried against. See https://typesense.org/docs/0.24.0/api/search.html.
     *
     * @return array
     */
    public function typesenseQueryBy(): array {
        return [
            'name',
            'email',
        ];
    }
}

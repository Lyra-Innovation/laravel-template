<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sector_id
 * @property int $user_responsable_id
 * @property string $name
 * @property string $password
 * @property string $description
 * @property string $image
 * @property string $idea_title
 * @property string $idea_description
 * @property string $idea_needs
 * @property string $idea_value
 * @property string $idea_clients
 * @property boolean $setup_validation
 * @property boolean $end_validation
 * @property string $comment_validation
 * @property string $type
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Sector $sector
 * @property User $user
 * @property Log[] $logs
 * @property UserItem[] $userItems
 */
class User extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['sector_id', 'user_responsable_id', 'name', 'password', 'description', 'image', 'idea_title', 'idea_description', 'idea_needs', 'idea_value', 'idea_clients', 'setup_validation', 'end_validation', 'comment_validation', 'type', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector()
    {
        return $this->belongsTo('App\Sector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_responsable_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userItems()
    {
        return $this->hasMany('App\UserItem');
    }
}

<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\DatabaseNotificationCollection;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $email the user email address and account login
 * @property string $password
 * @property string|null $pid
 * @property bool $is_disabled
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Activity[] $actions
 * @property-read int|null $actions_count
 * @property-read Collection|Institution[] $institutions
 * @property-read int|null $institutions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Queue[] $queues
 * @property-read int|null $queues_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsDisabled($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePid($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @property-read Collection|\Sadeem\Commons\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{

  use HasRoles, LogsActivity, CausesActivity, SoftDeletes;

  protected $guarded = ['id'];
  protected $guard_name = 'api';

  // Rest omitted for brevity

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims(): array
  {
    return [
        'role' => $this->roles()->pluck('name')->first(),
        'roles' => $this->roles->pluck('name'),
      ] + $this->toArray();
  }

  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'pid',
    'name',
    'phone',
    'email',
    'password',
    'is_disabled'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'deleted_at',
    'password',
    'remember_token',
  ];

  // /**
  //  * The attributes that should be cast to native types.
  //  *
  //  * @var array
  //  */
  // protected $casts = [
  //     'email_verified_at' => 'datetime',
  // ];

  public function institutions(): BelongsToMany
  {
    return $this
      ->belongsToMany(Institution::class, 'institution_managers')
      ->using(InstitutionManagers::class);
  }

  public function queues(): BelongsToMany
  {
    return $this
      ->belongsToMany(Queue::class, 'queue_managers')
      ->withTimestamps()
      ->using(QueueManagers::class);
  }

  // Model Utilities

  /**
   * Searches and sort based on the request parameters
   *
   * @param $request
   * @return User|mixed
   */
  public function searchAndSort($request)
  {
    // Params list
    $q = $request['q'];
    $role = $request['role'];
    $sort_by = $request['sort_by'];
    $sort = $request['sort'];
    if ($sort != 'desc') $sort = 'asc';

    // Conditions list
    $paramQ = isSetNotEmpty($q);
    $paramRole = isSetNotEmpty($role);
    $paramSortBy = confirmColumn($sort_by, 'users');

    return $this
      ->when($paramQ && $paramRole, function () use ($q, $role) {
        return $this
          ->similarity($q)
          ->role($role);
      })
      ->when($paramQ && !$paramRole, function () use ($q) {
        return $this->similarity($q);
      })
      ->when(!$paramQ && $paramRole && $paramSortBy, function () use ($role, $sort_by, $sort) {
        return $this
          ->role($role)
          ->orderBy($sort_by, $sort);
      })
      ->when(!$paramQ  && $paramRole && !$paramSortBy, function () use ($role) {
        return $this->role($role);
      })
      ->when(!$paramQ && !$paramRole && $paramSortBy,function () use ($sort_by, $sort) {
        return $this->orderBy($sort_by, $sort);
      })
      ->when(!$paramQ && !$paramRole && !$paramSortBy, function () { return $this; });
  }

  public function similarity($q)
  {
    return similarityByName($this, $q);
  }
}

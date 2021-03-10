<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
  use HasRoles, LogsActivity, CausesActivity;

  protected $guarded = ['id'];
  protected $guard_name = 'api';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
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
    'password',
  ];

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


  // Model Utilities

  public function searchAndSort($request)
  {
    $q = $request->input('q', '');
    $filter = $request->input('filter', '');
    $sorts = explode(',', $request->input('sort', ''));
    $confirmedSort = confirmColumns($sorts, 'users');

    $arr = buildSearchSortFilterConditions($q, $filter, $confirmedSort);

    return $this
      ->when($arr['qOnly'], function () use ($q) {
        return $this
          ->similarity($q, 'email');
      })
      ->when($arr['qFilter'], function () use ($q, $filter) {
        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == 'role')
          return $this
            ->similarity($q, 'email')
            ->role($value);

        return $this
          ->similarity($q, 'email')
          ->where($criteria, $value);
      })
      ->when($arr['sortFilter'], function () use ($sorts) {

        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == 'role') return $this->role($value)->orderQuery($sorts);

        return $this->where($criteria, $value)->orderQuery($sorts);
      })
      ->when($arr['sortOnly'], function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when($arr['filterOnly'], function () {
        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == 'role')
          return $this->role($value);

        return $this->where($criteria, $value);
      })
      ->when($arr['default'], function () {
        return $this;
      });
  }

  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  public function orderQuery($sorts)
  {
    return orderQuery($this, $sorts);
  }

  public function confirmFilter()
  {
    return confirmFilter(
      request('filter'),
      'users',
      "email"
    );
  }
}

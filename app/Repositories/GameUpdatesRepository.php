<?php

namespace App\Repositories;

use App\Models\GameUpdates;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GameUpdatesRepository
 * @package App\Repositories
 * @version June 25, 2019, 3:50 pm UTC
 *
 * @method GameUpdates findWithoutFail($id, $columns = ['*'])
 * @method GameUpdates find($id, $columns = ['*'])
 * @method GameUpdates first($columns = ['*'])
*/
class GameUpdatesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'actual_name',
        'past_name',
        'datetime',
        'user_id',
        'game_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GameUpdates::class;
    }
}

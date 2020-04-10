<?php

namespace App\Repositories;

use App\Models\GameCreates;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GameCreatesRepository
 * @package App\Repositories
 * @version June 25, 2019, 3:50 pm UTC
 *
 * @method GameCreates findWithoutFail($id, $columns = ['*'])
 * @method GameCreates find($id, $columns = ['*'])
 * @method GameCreates first($columns = ['*'])
*/
class GameCreatesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'datetime',
        'user_id',
        'game_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GameCreates::class;
    }
}

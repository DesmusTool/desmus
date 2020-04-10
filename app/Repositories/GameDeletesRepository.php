<?php

namespace App\Repositories;

use App\Models\GameDeletes;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GameDeletesRepository
 * @package App\Repositories
 * @version June 25, 2019, 3:50 pm UTC
 *
 * @method GameDeletes findWithoutFail($id, $columns = ['*'])
 * @method GameDeletes find($id, $columns = ['*'])
 * @method GameDeletes first($columns = ['*'])
*/
class GameDeletesRepository extends BaseRepository
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
        return GameDeletes::class;
    }
}

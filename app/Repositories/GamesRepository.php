<?php

namespace App\Repositories;

use App\Models\Games;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GamesRepository
 * @package App\Repositories
 * @version June 22, 2019, 2:42 am UTC
 *
 * @method Games findWithoutFail($id, $columns = ['*'])
 * @method Games find($id, $columns = ['*'])
 * @method Games first($columns = ['*'])
*/
class GamesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'category',
        'type',
        'price',
        'description',
        'instructions',
        'views_quantity',
        'updates_quantity',
        'status',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Games::class;
    }
}

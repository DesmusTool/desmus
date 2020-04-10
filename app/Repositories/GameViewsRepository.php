<?php

namespace App\Repositories;

use App\Models\GameViews;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GameViewsRepository
 * @package App\Repositories
 * @version June 25, 2019, 3:50 pm UTC
 *
 * @method GameViews findWithoutFail($id, $columns = ['*'])
 * @method GameViews find($id, $columns = ['*'])
 * @method GameViews first($columns = ['*'])
*/
class GameViewsRepository extends BaseRepository
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
        return GameViews::class;
    }
}

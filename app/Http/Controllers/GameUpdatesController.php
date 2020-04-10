<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameUpdatesRequest;
use App\Http\Requests\UpdateGameUpdatesRequest;
use App\Repositories\GameUpdatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GameUpdatesController extends AppBaseController
{
    /** @var  GameUpdatesRepository */
    private $gameUpdatesRepository;

    public function __construct(GameUpdatesRepository $gameUpdatesRepo)
    {
        $this->gameUpdatesRepository = $gameUpdatesRepo;
    }

    /**
     * Display a listing of the GameUpdates.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->gameUpdatesRepository->pushCriteria(new RequestCriteria($request));
        $gameUpdates = $this->gameUpdatesRepository->all();

        return view('game_updates.index')
            ->with('gameUpdates', $gameUpdates);
    }

    /**
     * Show the form for creating a new GameUpdates.
     *
     * @return Response
     */
    public function create()
    {
        return view('game_updates.create');
    }

    /**
     * Store a newly created GameUpdates in storage.
     *
     * @param CreateGameUpdatesRequest $request
     *
     * @return Response
     */
    public function store(CreateGameUpdatesRequest $request)
    {
        $input = $request->all();

        $gameUpdates = $this->gameUpdatesRepository->create($input);

        Flash::success('Game Updates saved successfully.');

        return redirect(route('gameUpdates.index'));
    }

    /**
     * Display the specified GameUpdates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $gameUpdates = $this->gameUpdatesRepository->findWithoutFail($id);

        if (empty($gameUpdates)) {
            Flash::error('Game Updates not found');

            return redirect(route('gameUpdates.index'));
        }

        return view('game_updates.show')->with('gameUpdates', $gameUpdates);
    }

    /**
     * Show the form for editing the specified GameUpdates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $gameUpdates = $this->gameUpdatesRepository->findWithoutFail($id);

        if (empty($gameUpdates)) {
            Flash::error('Game Updates not found');

            return redirect(route('gameUpdates.index'));
        }

        return view('game_updates.edit')->with('gameUpdates', $gameUpdates);
    }

    /**
     * Update the specified GameUpdates in storage.
     *
     * @param  int              $id
     * @param UpdateGameUpdatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameUpdatesRequest $request)
    {
        $gameUpdates = $this->gameUpdatesRepository->findWithoutFail($id);

        if (empty($gameUpdates)) {
            Flash::error('Game Updates not found');

            return redirect(route('gameUpdates.index'));
        }

        $gameUpdates = $this->gameUpdatesRepository->update($request->all(), $id);

        Flash::success('Game Updates updated successfully.');

        return redirect(route('gameUpdates.index'));
    }

    /**
     * Remove the specified GameUpdates from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $gameUpdates = $this->gameUpdatesRepository->findWithoutFail($id);

        if (empty($gameUpdates)) {
            Flash::error('Game Updates not found');

            return redirect(route('gameUpdates.index'));
        }

        $this->gameUpdatesRepository->delete($id);

        Flash::success('Game Updates deleted successfully.');

        return redirect(route('gameUpdates.index'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameDeletesRequest;
use App\Http\Requests\UpdateGameDeletesRequest;
use App\Repositories\GameDeletesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GameDeletesController extends AppBaseController
{
    /** @var  GameDeletesRepository */
    private $gameDeletesRepository;

    public function __construct(GameDeletesRepository $gameDeletesRepo)
    {
        $this->gameDeletesRepository = $gameDeletesRepo;
    }

    /**
     * Display a listing of the GameDeletes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->gameDeletesRepository->pushCriteria(new RequestCriteria($request));
        $gameDeletes = $this->gameDeletesRepository->all();

        return view('game_deletes.index')
            ->with('gameDeletes', $gameDeletes);
    }

    /**
     * Show the form for creating a new GameDeletes.
     *
     * @return Response
     */
    public function create()
    {
        return view('game_deletes.create');
    }

    /**
     * Store a newly created GameDeletes in storage.
     *
     * @param CreateGameDeletesRequest $request
     *
     * @return Response
     */
    public function store(CreateGameDeletesRequest $request)
    {
        $input = $request->all();

        $gameDeletes = $this->gameDeletesRepository->create($input);

        Flash::success('Game Deletes saved successfully.');

        return redirect(route('gameDeletes.index'));
    }

    /**
     * Display the specified GameDeletes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $gameDeletes = $this->gameDeletesRepository->findWithoutFail($id);

        if (empty($gameDeletes)) {
            Flash::error('Game Deletes not found');

            return redirect(route('gameDeletes.index'));
        }

        return view('game_deletes.show')->with('gameDeletes', $gameDeletes);
    }

    /**
     * Show the form for editing the specified GameDeletes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $gameDeletes = $this->gameDeletesRepository->findWithoutFail($id);

        if (empty($gameDeletes)) {
            Flash::error('Game Deletes not found');

            return redirect(route('gameDeletes.index'));
        }

        return view('game_deletes.edit')->with('gameDeletes', $gameDeletes);
    }

    /**
     * Update the specified GameDeletes in storage.
     *
     * @param  int              $id
     * @param UpdateGameDeletesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameDeletesRequest $request)
    {
        $gameDeletes = $this->gameDeletesRepository->findWithoutFail($id);

        if (empty($gameDeletes)) {
            Flash::error('Game Deletes not found');

            return redirect(route('gameDeletes.index'));
        }

        $gameDeletes = $this->gameDeletesRepository->update($request->all(), $id);

        Flash::success('Game Deletes updated successfully.');

        return redirect(route('gameDeletes.index'));
    }

    /**
     * Remove the specified GameDeletes from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $gameDeletes = $this->gameDeletesRepository->findWithoutFail($id);

        if (empty($gameDeletes)) {
            Flash::error('Game Deletes not found');

            return redirect(route('gameDeletes.index'));
        }

        $this->gameDeletesRepository->delete($id);

        Flash::success('Game Deletes deleted successfully.');

        return redirect(route('gameDeletes.index'));
    }
}

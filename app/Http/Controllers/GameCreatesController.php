<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameCreatesRequest;
use App\Http\Requests\UpdateGameCreatesRequest;
use App\Repositories\GameCreatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GameCreatesController extends AppBaseController
{
    /** @var  GameCreatesRepository */
    private $gameCreatesRepository;

    public function __construct(GameCreatesRepository $gameCreatesRepo)
    {
        $this->gameCreatesRepository = $gameCreatesRepo;
    }

    /**
     * Display a listing of the GameCreates.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->gameCreatesRepository->pushCriteria(new RequestCriteria($request));
        $gameCreates = $this->gameCreatesRepository->all();

        return view('game_creates.index')
            ->with('gameCreates', $gameCreates);
    }

    /**
     * Show the form for creating a new GameCreates.
     *
     * @return Response
     */
    public function create()
    {
        return view('game_creates.create');
    }

    /**
     * Store a newly created GameCreates in storage.
     *
     * @param CreateGameCreatesRequest $request
     *
     * @return Response
     */
    public function store(CreateGameCreatesRequest $request)
    {
        $input = $request->all();

        $gameCreates = $this->gameCreatesRepository->create($input);

        Flash::success('Game Creates saved successfully.');

        return redirect(route('gameCreates.index'));
    }

    /**
     * Display the specified GameCreates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $gameCreates = $this->gameCreatesRepository->findWithoutFail($id);

        if (empty($gameCreates)) {
            Flash::error('Game Creates not found');

            return redirect(route('gameCreates.index'));
        }

        return view('game_creates.show')->with('gameCreates', $gameCreates);
    }

    /**
     * Show the form for editing the specified GameCreates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $gameCreates = $this->gameCreatesRepository->findWithoutFail($id);

        if (empty($gameCreates)) {
            Flash::error('Game Creates not found');

            return redirect(route('gameCreates.index'));
        }

        return view('game_creates.edit')->with('gameCreates', $gameCreates);
    }

    /**
     * Update the specified GameCreates in storage.
     *
     * @param  int              $id
     * @param UpdateGameCreatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameCreatesRequest $request)
    {
        $gameCreates = $this->gameCreatesRepository->findWithoutFail($id);

        if (empty($gameCreates)) {
            Flash::error('Game Creates not found');

            return redirect(route('gameCreates.index'));
        }

        $gameCreates = $this->gameCreatesRepository->update($request->all(), $id);

        Flash::success('Game Creates updated successfully.');

        return redirect(route('gameCreates.index'));
    }

    /**
     * Remove the specified GameCreates from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $gameCreates = $this->gameCreatesRepository->findWithoutFail($id);

        if (empty($gameCreates)) {
            Flash::error('Game Creates not found');

            return redirect(route('gameCreates.index'));
        }

        $this->gameCreatesRepository->delete($id);

        Flash::success('Game Creates deleted successfully.');

        return redirect(route('gameCreates.index'));
    }
}

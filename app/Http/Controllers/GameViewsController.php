<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGameViewsRequest;
use App\Http\Requests\UpdateGameViewsRequest;
use App\Repositories\GameViewsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GameViewsController extends AppBaseController
{
    /** @var  GameViewsRepository */
    private $gameViewsRepository;

    public function __construct(GameViewsRepository $gameViewsRepo)
    {
        $this->gameViewsRepository = $gameViewsRepo;
    }

    /**
     * Display a listing of the GameViews.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->gameViewsRepository->pushCriteria(new RequestCriteria($request));
        $gameViews = $this->gameViewsRepository->all();

        return view('game_views.index')
            ->with('gameViews', $gameViews);
    }

    /**
     * Show the form for creating a new GameViews.
     *
     * @return Response
     */
    public function create()
    {
        return view('game_views.create');
    }

    /**
     * Store a newly created GameViews in storage.
     *
     * @param CreateGameViewsRequest $request
     *
     * @return Response
     */
    public function store(CreateGameViewsRequest $request)
    {
        $input = $request->all();

        $gameViews = $this->gameViewsRepository->create($input);

        Flash::success('Game Views saved successfully.');

        return redirect(route('gameViews.index'));
    }

    /**
     * Display the specified GameViews.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $gameViews = $this->gameViewsRepository->findWithoutFail($id);

        if (empty($gameViews)) {
            Flash::error('Game Views not found');

            return redirect(route('gameViews.index'));
        }

        return view('game_views.show')->with('gameViews', $gameViews);
    }

    /**
     * Show the form for editing the specified GameViews.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $gameViews = $this->gameViewsRepository->findWithoutFail($id);

        if (empty($gameViews)) {
            Flash::error('Game Views not found');

            return redirect(route('gameViews.index'));
        }

        return view('game_views.edit')->with('gameViews', $gameViews);
    }

    /**
     * Update the specified GameViews in storage.
     *
     * @param  int              $id
     * @param UpdateGameViewsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameViewsRequest $request)
    {
        $gameViews = $this->gameViewsRepository->findWithoutFail($id);

        if (empty($gameViews)) {
            Flash::error('Game Views not found');

            return redirect(route('gameViews.index'));
        }

        $gameViews = $this->gameViewsRepository->update($request->all(), $id);

        Flash::success('Game Views updated successfully.');

        return redirect(route('gameViews.index'));
    }

    /**
     * Remove the specified GameViews from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $gameViews = $this->gameViewsRepository->findWithoutFail($id);

        if (empty($gameViews)) {
            Flash::error('Game Views not found');

            return redirect(route('gameViews.index'));
        }

        $this->gameViewsRepository->delete($id);

        Flash::success('Game Views deleted successfully.');

        return redirect(route('gameViews.index'));
    }
}

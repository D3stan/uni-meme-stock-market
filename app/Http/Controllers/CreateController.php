<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\CreateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
{
    protected CreateService $createService;

    public function __construct(CreateService $createService)
    {
        $this->createService = $createService;
    }

    /**
     * Prepares the meme creation view with available categories and user balance.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $categories = $this->createService->getCategories();
        $balance = $this->createService->getUserBalance(Auth::user());
        
        return view('pages.meme.create', compact('categories', 'balance'));
    }

    /**
     * Checks if a proposed ticker symbol already exists in the database via AJAX.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkTicker(Request $request)
    {
        $ticker = $request->input('ticker');
        $exists = $this->createService->tickerExists($ticker);
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Validates and stores a new meme submission, charging the listing fee if the user has sufficient funds.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:50',
            'ticker' => 'required|string|min:3|max:6|unique:memes,ticker',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'text_alt' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Errore nella validazione dei dati.');
        }

        $user = Auth::user();
        if ($user->cfu_balance < 20.00) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Saldo insufficiente per pagare la fee di listing (20 CFU).');
        }

        try {
            $this->createService->createMeme(
                $request->only(['title', 'ticker', 'category_id', 'text_alt']),
                $request->file('image'),
                $user
            );

            return redirect()->route('create')
                ->with('success', 'Meme inviato con successo! Sarà valutato dal Rettorato.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Errore durante il caricamento del meme. Riprova.');
        }
    }
}

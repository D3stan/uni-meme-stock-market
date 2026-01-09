<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\CreateService;
use Illuminate\Http\Request;
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
     * Display the create meme page.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $categories = $this->createService->getCategories();
        $balance = $this->createService->getUserBalance(Auth::user());
        $fee = $this->createService->getListingFee();
        
        return view('pages.meme.create', compact('categories', 'balance', 'fee'));
    }

    /**
     * Check if ticker already exists (AJAX).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTicker(Request $request)
    {
        $ticker = $request->input('ticker');
        $exists = $this->createService->tickerExists($ticker);
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Store a new meme.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation
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

        // Check user balance
        $user = Auth::user();
        if (!$this->createService->hasSufficientFundsForListing($user)) {
            $fee = $this->createService->getListingFee();
            return redirect()->back()
                ->withInput()
                ->with('error', "Saldo insufficiente per pagare la fee di listing ({$fee} CFU).");
        }

        try {
            // Create meme
            $meme = $this->createService->createMeme(
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

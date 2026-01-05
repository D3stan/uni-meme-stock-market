<?php

namespace App\Http\Controllers;

use App\Services\CreateService;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    protected CreateService $createService;

    public function __construct(CreateService $createService)
    {
        $this->createService = $createService;
    }

    /**
     * Display the marketplace page with memes.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $categories = $this->createService->getCategories();
        
        return view('pages.meme.create', compact('categories'));
    }
}

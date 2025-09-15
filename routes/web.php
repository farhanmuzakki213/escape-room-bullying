<?php

use App\Livewire\GameManager;
use App\Livewire\HomePage;
use App\Livewire\PetaMisiPage;
use Illuminate\Support\Facades\Route;

Route::get('/', GameManager::class);
// Route::get('/', PetaMisiPage::class);

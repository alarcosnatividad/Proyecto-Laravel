<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Si el idioma está en nuestra lista, lo guardamos en la sesión
        if (array_key_exists($locale, config('app.available_locales') ?? ['es' => 'Spanish', 'en' => 'English'])) {
            Session::put('applocale', $locale);
        }
        return redirect()->back();
    }
}
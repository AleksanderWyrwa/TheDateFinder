<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Metoda do wyświetlenia dashboardu administratora
    public function index()
    {
        // Sprawdzamy, czy użytkownik jest zalogowany i ma rolę 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Pobieramy użytkowników z paginacją (3 użytkowników na stronę)
            $users = User::paginate(3); // Możesz zmienić liczbę na 6 lub 9

            // Przekazujemy użytkowników do widoku
            return view('admin.dashboard', compact('users'));
        } else {
            // Jeśli użytkownik nie jest administratorem, przekierowujemy na stronę główną
            return redirect('/'); // Strona główna
        }
    }

    // Metoda do nadania roli 'admin' użytkownikowi
    public function makeAdmin($id)
    {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (Auth::check() && Auth::user()->role === 'admin') {
            $user = User::find($id);
            
            if ($user) {
                $user->role = 'admin'; // Ustawiamy rolę na 'admin'
                $user->save(); // Zapisujemy zmiany
            }

            return redirect()->route('admin.dashboard'); // Po zmianie roli przekierowujemy do dashboardu
        } else {
            return redirect('/'); // Jeśli użytkownik nie jest adminem, przekierowujemy na stronę główną
        }
    }

    // Metoda do usunięcia użytkownika
    public function deleteUser($id)
    {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (Auth::check() && Auth::user()->role === 'admin') {
            $user = User::find($id);
            
            if ($user) {
                $user->delete(); // Usuwamy użytkownika
            }

            return redirect()->route('admin.dashboard'); // Po usunięciu przekierowujemy do dashboardu
        } else {
            return redirect('/'); // Jeśli użytkownik nie jest adminem, przekierowujemy na stronę główną
        }
    }

    // Metoda do wyświetlenia profilu użytkownika (możliwość edycji)
    public function showProfile($id)
    {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Pobieramy użytkownika
            $user = User::findOrFail($id);

            return view('admin.show-profile', compact('user')); // Wyświetlamy profil
        } else {
            return redirect('/'); // Jeśli użytkownik nie jest adminem, przekierowujemy na stronę główną
        }
    }

    // Metoda do edycji profilu użytkownika
    public function editProfile($id)
    {
        // Sprawdzamy, czy użytkownik jest administratorem
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Pobieramy użytkownika
            $user = User::findOrFail($id);

            return view('admin.edit-profile', compact('user')); // Wyświetlamy formularz do edycji
        } else {
            return redirect('/'); // Jeśli użytkownik nie jest adminem, przekierowujemy na stronę główną
        }
    }
}

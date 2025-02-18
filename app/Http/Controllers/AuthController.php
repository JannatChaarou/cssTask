<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show the login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle user login and auto-registration if user doesn't exist
    public function login(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Check if the user exists
        $user = User::where('email', $validated['email'])->first();

        // If the user doesn't exist, create the user and log them in
        if (!$user) {
            // Create a new user with a default name or set the name field later
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'name' => 'Guest',  // You can set a default name or allow the user to change it later
            ]);
        } else {
            // If the user exists, check if the password matches
            if (!Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials.']);
            }
        }

        // Log the user in
        Auth::login($user);

        // Redirect to the tasks index page
        return redirect()->route('tasks.index');
    }

    // Show the registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        // Validate and create the user
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user and hash the password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Log the user in after registration
        Auth::login($user);

        // Redirect to the tasks index page
        return redirect()->route('tasks.index');
    }

    // Logout the user
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

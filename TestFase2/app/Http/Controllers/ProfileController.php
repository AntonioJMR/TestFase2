<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
    * @OA\Get(
    *     path="/profile/edit",
    *     summary="Mostrar el formulario de perfil del usuario",
    *     tags={"Profile"},
    *     @OA\Response(
    *         response=200,
    *         description="Respuesta exitosa",
    *         @OA\JsonContent(ref="#/components/schemas/User")
    *     )
    * )
    * 
    * @OA\Put(
    *     path="/profile/update",
    *     summary="Actualizar la información del perfil del usuario",
    *     tags={"Profile"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(ref="#/components/schemas/ProfileUpdateRequest")
    *     ),
    *     @OA\Response(
    *         response=302,
    *         description="Redirige a la página de edición de perfil"
    *     )
    * )
    * 
    * @OA\Delete(
    *     path="/profile/destroy/{user}",
    *     summary="Eliminar el perfil de un usuario",
    *     tags={"Profile"},
    *     @OA\Parameter(
    *         name="user",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Prohibido"
    *     ),
    *     @OA\Response(
    *         response=302,
    *         description="Redirige al índice de usuarios"
    *     )
    * )
    */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Validar que el admin 
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $authUser = $request->user();

        
        if (! Gate::allows('delete-user', $user)) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        // Cerrar sesión del usuario que se va a eliminar si es el mismo autenticado
        if ($authUser->id === $user->id) {
            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');
        }

        // Si el admin elimina a otro usuario normal
        $user->delete();

        return Redirect::route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

}

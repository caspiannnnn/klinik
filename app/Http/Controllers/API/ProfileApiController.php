<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Profil user (akun yang sedang login)"
 * )
 */
class ProfileApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Detail profil user login",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Update profil user login",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profil terupdate"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [];

        if (in_array('name', $user->getFillable(), true)) {
            $rules['name'] = ['sometimes', 'string', 'max:255'];
        }

        if (in_array('email', $user->getFillable(), true)) {
            $rules['email'] = ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id];
        }

        if (! empty($rules)) {
            $validated = $request->validate($rules);
        } else {
            $validated = $request->all();
        }

        $fillable = $user->getFillable();
        $data = array_intersect_key($validated, array_flip($fillable));

        $user->fill($data);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/password",
     *     tags={"Profile"},
     *     summary="Update password user login",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8),
     *             @OA\Property(property="password_confirmation", type="string", format="password", minLength=8)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password terupdate"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama tidak sesuai.'],
            ]);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ], Response::HTTP_OK);
    }
}

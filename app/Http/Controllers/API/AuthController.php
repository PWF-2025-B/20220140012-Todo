<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="Todo API",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan token JWT Anda setelah login"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login pengguna dan dapatkan token JWT",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Login berhasil"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com"),
     *                     @OA\Property(property="is_admin", type="boolean", example=false)
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJK...")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Email atau password salah"),
     *     @OA\Response(response=400, description="Email dan password harus diisi"),
     *     @OA\Response(response=500, description="Terjadi kesalahan")
     * )
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (empty($data['email']) || empty($data['password'])) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Email dan password harus diisi.',
            ], 400);
        }

        try {
            if (! $token = Auth::guard('api')->attempt($data)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $user = Auth::guard('api')->user();
            return response()->json([
                'status_code' => 200,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin,
                    ],
                    'token' => $token,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout pengguna dan hapus token JWT",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logout berhasil. Token telah dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Gagal logout")
     * )
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status_code' => 200,
                'message' => 'Logout berhasil. Token telah dihapus.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Gagal logout, terjadi kesalahan.',
            ], 500);
        }
    }
}

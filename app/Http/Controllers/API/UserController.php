<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved all users.',
            'data' => User::paginate(15)
        ]);
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['sometimes', 'required', Rule::in(['user', 'admin'])],
        ]);

        if ($validator->fails()) {
            // Respons gagal validasi sudah otomatis
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        // Jika role tidak dikirim, default ke 'user'
        if (!isset($validatedData['role'])) {
            $validatedData['role'] = 'user';
        }

        $user = User::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user
        ], 201);
    }

    /**
     * Menampilkan satu pengguna spesifik.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user
        ]);
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|string|min:8',
            'role' => ['sometimes', 'required', Rule::in(['user', 'admin'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $dataToUpdate = $validator->validated();

        if (!empty($dataToUpdate['password'])) {
            $dataToUpdate['password'] = Hash::make($dataToUpdate['password']);
        } else {
            unset($dataToUpdate['password']);
        }

        $user->update($dataToUpdate);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user
        ]);
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}

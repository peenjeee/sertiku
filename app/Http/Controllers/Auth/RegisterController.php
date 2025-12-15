<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Models\Organization; // aktifkan kalau sudah punya model/tabel organizations
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman register multi-step (tab Pengguna & Lembaga).
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Simpan register PENGGUNA (wizard multi-step).
     * Sesuai form di TAB "Pengguna".
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Step 1
            'full_name'   => ['required', 'string', 'max:255'],
            'occupation'  => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],

            // Step 2
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['required', 'string', 'max:50'],
            'country'     => ['required', 'string', 'max:100'],

            // Step 3
            'password'    => ['required', 'string', 'min:8', 'confirmed'],

            // Step 4 (kalau nanti dipakai)
            'interests'   => ['sometimes', 'array'],
            'interests.*' => ['string', 'max:255'],
        ]);

        $user = User::create([
            'name'         => $validated['full_name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'phone'        => $validated['phone'],
            'country'      => $validated['country'],
            'occupation'   => $validated['occupation'] ?? null,
            'institution'  => $validated['institution'] ?? null,
            'account_type' => 'pengguna',
            'interests'    => isset($validated['interests'])
                ? json_encode($validated['interests'])
                : null,
        ]);

        event(new Registered($user));
        Auth::login($user);

        // GANTI 'dashboard' dengan route yang kamu punya setelah login
        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di SertiKu!');
    }

    /**
     * Simpan register LEMBAGA (TAB "Lembaga", 4 step).
     */
    public function storeLembaga(Request $request)
    {
        // validasi semua field dari step 1–4
        $validated = $request->validate([
            // ========== STEP 1: INFO LEMBAGA ==========
            'institution_name' => ['required', 'string', 'max:255'],
            'institution_type' => ['required', 'string', 'max:100'],
            'sector'           => ['required', 'string', 'max:255'],
            'website'          => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],

            // ========== STEP 2: ALAMAT ==========
            'address_line'     => ['required', 'string', 'max:255'],
            'city'             => ['required', 'string', 'max:100'],
            'province'         => ['required', 'string', 'max:100'],
            'postal_code'      => ['required', 'string', 'max:20'],
            'country'          => ['required', 'string', 'max:100'],

            // ========== STEP 3: ADMIN / PIC ==========
            'admin_name'       => ['required', 'string', 'max:255'],
            'admin_email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_phone'      => ['required', 'string', 'max:50'],
            'admin_position'   => ['required', 'string', 'max:100'],
            'admin_password'   => ['required', 'string', 'min:8', 'confirmed'],

            // ========== STEP 4: DOKUMEN & PERSETUJUAN ==========
            'doc_npwp'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'doc_akta'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'doc_siup'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'agreement'        => ['accepted'], // checkbox "saya menyetujui..."
        ]);

        // Simpan file dokumen kalau ada
        $docs = [];
        foreach (['doc_npwp', 'doc_akta', 'doc_siup'] as $field) {
            if ($request->hasFile($field)) {
                // simpan ke storage/app/public/lembaga_docs
                $docs[$field] = $request->file($field)->store('lembaga_docs', 'public');
            }
        }

        // TODO: simpan ke tabel organizations / institutions
        // Contoh kalau sudah punya model Organization:
        /*
        $org = Organization::create([
            'name'        => $validated['institution_name'],
            'type'        => $validated['institution_type'],
            'sector'      => $validated['sector'],
            'website'     => $validated['website'] ?? null,
            'description' => $validated['description'] ?? null,

            'address'     => $validated['address_line'],
            'city'        => $validated['city'],
            'province'    => $validated['province'],
            'postal_code' => $validated['postal_code'],
            'country'     => $validated['country'],

            'admin_name'     => $validated['admin_name'],
            'admin_email'    => $validated['admin_email'],
            'admin_phone'    => $validated['admin_phone'],
            'admin_position' => $validated['admin_position'],

            'doc_npwp_path' => $docs['doc_npwp'] ?? null,
            'doc_akta_path' => $docs['doc_akta'] ?? null,
            'doc_siup_path' => $docs['doc_siup'] ?? null,
        ]);
        */

        // Create the user account for lembaga
        $user = User::create([
            'name'             => $validated['admin_name'],
            'email'            => $validated['admin_email'],
            'password'         => Hash::make($validated['admin_password']),
            'phone'            => $validated['admin_phone'],
            'country'          => $validated['country'],
            'account_type'     => 'lembaga',
            'institution_name' => $validated['institution_name'],
            'institution_type' => $validated['institution_type'],
            'sector'           => $validated['sector'],
            'website'          => $validated['website'] ?? null,
            'description'      => $validated['description'] ?? null,
            'address'          => $validated['address_line'],
            'city'             => $validated['city'],
            'province'         => $validated['province'],
            'postal_code'      => $validated['postal_code'],
            'admin_position'   => $validated['admin_position'],
            'doc_npwp_path'    => $docs['doc_npwp'] ?? null,
            'doc_akta_path'    => $docs['doc_akta'] ?? null,
            'doc_siup_path'    => $docs['doc_siup'] ?? null,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()
            ->route('lembaga.dashboard')
            ->with('success', 'Akun lembaga berhasil dibuat. Selamat datang di SertiKu!');
    }
}

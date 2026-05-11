<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $member = $user->member;

        // Jika user belum punya data anggota (seperti Ketua/Admin baru), buatkan otomatis
        if (!$member) {
            $member = \App\Models\Member::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'status' => 'Aktif',
                'nim' => 'ID-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'member_code' => 'ISBA-' . date('Y') . '-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        return view('member.profile.index', compact('member'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            $member = \App\Models\Member::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'status' => 'Aktif',
                'nim' => 'ID-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'member_code' => 'ISBA-' . date('Y') . '-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            ]);
        }
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university' => 'nullable|string',
        ]);

        $data = $request->only(['phone', 'address', 'university']);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}

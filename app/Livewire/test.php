<?php

public function updatePassword(Request $request) {
    // Node 1: Cek Current Pass
    if (!$user->password) return back()->with('error'); // Node 2
    
    // Node 3: Cek Hash Match
    if (!Hash::check($input, $user->password)) return back()->withErrors(); // Node 4
    
    // Node 5: Validasi & Update
    $validate($new);
    $user->update(['password' => Hash::make($new)]);
    return back()->with('success'); // Node 6
}
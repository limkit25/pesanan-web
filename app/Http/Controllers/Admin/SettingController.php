<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $taxEnabledSetting = Setting::firstOrCreate(
            ['key' => 'tax_enabled'],
            ['value' => '1'] // Default to enabled
        );

        return view('admin.settings.index', compact('taxEnabledSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tax_enabled' => 'required|in:0,1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'tax_enabled'],
            ['value' => $request->tax_enabled]
        );

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}

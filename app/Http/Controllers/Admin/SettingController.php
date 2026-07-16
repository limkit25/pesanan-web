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

        $bankNameSetting = Setting::firstOrCreate(
            ['key' => 'bank_name'],
            ['value' => 'BCA'] 
        );

        $bankAccountSetting = Setting::firstOrCreate(
            ['key' => 'bank_account'],
            ['value' => '123456789'] 
        );

        $bankOwnerSetting = Setting::firstOrCreate(
            ['key' => 'bank_owner'],
            ['value' => 'FoodieHub Official'] 
        );

        return view('admin.settings.index', compact('taxEnabledSetting', 'bankNameSetting', 'bankAccountSetting', 'bankOwnerSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tax_enabled' => 'required|in:0,1',
            'bank_name' => 'required|string|max:50',
            'bank_account' => 'required|string|max:50',
            'bank_owner' => 'required|string|max:100',
        ]);

        Setting::updateOrCreate(
            ['key' => 'tax_enabled'],
            ['value' => $request->tax_enabled]
        );

        Setting::updateOrCreate(
            ['key' => 'bank_name'],
            ['value' => $request->bank_name]
        );

        Setting::updateOrCreate(
            ['key' => 'bank_account'],
            ['value' => $request->bank_account]
        );

        Setting::updateOrCreate(
            ['key' => 'bank_owner'],
            ['value' => $request->bank_owner]
        );

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}

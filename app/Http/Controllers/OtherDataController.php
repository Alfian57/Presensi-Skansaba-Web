<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\UpdateOtherDataRequest;
use App\Models\OtherData;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class OtherDataController extends Controller
{
    public function index()
    {
        Helper::addHistory('/admin/other-data', 'Data Lain');

        $data = [
            'title' => 'Lain-lain',
            'otherDatas' => OtherData::orderBy('id', 'ASC')->get(),
        ];

        return view('system.other-data.index', $data);
    }

    public function edit($id)
    {
        Helper::addHistory('/admin/other-data'.$id.'/edit', 'Ubah Data Lain');

        $otherData = OtherData::where('id', $id)->first();
        if ($otherData == null) {
            return redirect('/admin/other-data');
        }

        if ($otherData->name == 'QR Absensi Masuk') {
            OtherData::where('name', 'QR Absensi Masuk')->update([
                'value' => Str::random(20),
            ]);

            return redirect('/admin/other-data')->with('success', 'Data Berhasil Diperbarui');
        }

        if ($otherData->name == 'QR Absensi Pulang') {
            OtherData::where('name', 'QR Absensi Pulang')->update([
                'value' => Str::random(20),
            ]);

            return redirect('/admin/other-data')->with('success', 'Data Berhasil Diperbarui');
        }

        $data = [
            'title' => 'Edit Data',
            'otherData' => $otherData,
        ];

        return view('system.other-data.edit', $data);
    }

    public function update(UpdateOtherDataRequest $request)
    {
        $waktuMulai = OtherData::where('name', 'Waktu Absen Mulai')->first();
        $waktuMulai = $waktuMulai->value;

        $waktuBerakhir = OtherData::where('name', 'Waktu Absen Berakhir')->first();
        $waktuBerakhir = $waktuBerakhir->value;

        $validatedData = $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        if ($request->name == 'QR Absensi Masuk' || $request->name == 'QR Absensi Pulang') {
            $request->validate([
                'value' => 'size:20',
            ]);
        } elseif ($request->name == 'Waktu Absen Mulai') {
            if (strtotime($waktuBerakhir) < strtotime($request->value)) {
                return back()->with('errorMessage', 'Value must be less than "Waktu Berakhir"')
                    ->with('oldValue', $request->value);
            }
        } elseif ($request->name == 'Waktu Absen Berakhir') {
            if (strtotime($waktuMulai) > strtotime($request->value)) {
                return back()->with('errorMessage', 'Value must be grather than "Waktu Mulai"')
                    ->with('oldValue', $request->value);
            }
        } elseif ($request->name == 'Waktu Absen Terlambat') {
            if (strtotime($waktuMulai) > strtotime($request->value) || strtotime($waktuBerakhir) < strtotime($request->value)) {
                return back()->with('errorMessage', 'Value must be grather than "Waktu Mulai" and less than "Waktu Berakhir"')
                    ->with('oldValue', $request->value);
            }
        } elseif ($request->name == 'Hari Masuk') {
            Artisan::call('attendance:create');
            $days = [];
            if ($request->Senin) {
                $days[] = 'Senin';
            }
            if ($request->Selasa) {
                $days[] = 'Selasa';
            }
            if ($request->Rabu) {
                $days[] = 'Rabu';
            }
            if ($request->Kamis) {
                $days[] = 'Kamis';
            }
            if ($request->Jumat) {
                $days[] = 'Jumat';
            }
            if ($request->Sabtu) {
                $days[] = 'Sabtu';
            }
            if ($request->Minggu) {
                $days[] = 'Minggu';
            }

            $validatedData['value'] = implode(', ', $days);
        }

        OtherData::where('id', $request->id)->update($validatedData);

        return redirect('/admin/other-data')->with('success', 'Data Berhasil Diperbarui');
    }
}

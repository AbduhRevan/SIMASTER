<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Server;
use App\Models\Website;
use App\Models\LogAktivitas; // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        // Base query untuk filter bidang
        $baseQuery = Pemeliharaan::query();

        if (Auth()->user()->role != 'superadmin') {
            $id_bidang = Auth()->user()->bidang_id ?? Auth()->user()->bidang;
            $baseQuery->where('bidang_id', $id_bidang);
        }

        // Hitung statistik (sudah terfilter per bidang)
        $totalPemeliharaan = (clone $baseQuery)->count();
        $totalServer = (clone $baseQuery)->whereNotNull('server_id')->count();
        $totalWebsite = (clone $baseQuery)->whereNotNull('website_id')->count();
        $dijadwalkan = (clone $baseQuery)->where('status_pemeliharaan', 'dijadwalkan')->count();
        $berlangsung = (clone $baseQuery)->where('status_pemeliharaan', 'berlangsung')->count();

        // Query untuk list dengan relasi
        $query = (clone $baseQuery)->with(['server', 'website']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('server', function ($q) use ($search) {
                        $q->where('nama_server', 'like', "%{$search}%");
                    })
                    ->orWhereHas('website', function ($q) use ($search) {
                        $q->where('nama_website', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            if ($request->jenis == 'server') {
                $query->whereNotNull('server_id');
            } elseif ($request->jenis == 'website') {
                $query->whereNotNull('website_id');
            }
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_pemeliharaan', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pemeliharaan', $request->tanggal);
        }

        // Ambil data dengan pagination
        $pemeliharaan = $query->orderBy('tanggal_pemeliharaan', 'desc')->paginate(10);

        // Ambil data server dan website untuk dropdown (yang tidak sedang maintenance dari pemeliharaan lain)
        $serversQuery = Server::whereIn('power_status', ['ON', 'OFF'])
            ->whereNotIn('server_id', function ($query) {
                $query->select('server_id')
                    ->from('pemeliharaan')
                    ->whereNotNull('server_id')
                    ->where('status_pemeliharaan', 'berlangsung');
            });

        $websitesQuery = Website::whereIn('status', ['active', 'inactive'])
            ->whereNotIn('website_id', function ($query) {
                $query->select('website_id')
                    ->from('pemeliharaan')
                    ->whereNotNull('website_id')
                    ->where('status_pemeliharaan', 'berlangsung');
            });

        // Filter dropdown juga berdasarkan bidang jika bukan superadmin
        if (Auth()->user()->role != 'superadmin') {
            $id_bidang = Auth()->user()->bidang_id ?? Auth()->user()->bidang;
            $serversQuery->where('bidang_id', $id_bidang);
            $websitesQuery->where('bidang_id', $id_bidang);
        }

        $servers = $serversQuery->orderBy('nama_server')->get();
        $websites = $websitesQuery->orderBy('nama_website')->get();

        return view('pemeliharaan', compact(
            'pemeliharaan',
            'totalPemeliharaan',
            'totalServer',
            'totalWebsite',
            'dijadwalkan',
            'berlangsung',
            'servers',
            'websites'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'server_id' => 'required_if:jenis_asset,server|nullable|exists:server,server_id',
            'website_id' => 'required_if:jenis_asset,website|nullable|exists:website,website_id',
            'keterangan' => 'required|string|max:1000',
        ], [
            'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan harus diisi',
            'jenis_asset.required' => 'Jenis asset harus dipilih',
            'server_id.required_if' => 'Server harus dipilih',
            'website_id.required_if' => 'Website harus dipilih',
            'keterangan.required' => 'Keterangan harus diisi',
        ]);

        try {
            DB::beginTransaction();

            $bidangId = null;
            $assetName = null; // TAMBAHKAN untuk logging

            // Validasi: Cek apakah aset sedang dalam pemeliharaan
            if ($request->jenis_asset == 'server') {
                $existing = Pemeliharaan::where('server_id', $request->server_id)
                    ->where('status_pemeliharaan', 'berlangsung')
                    ->exists();

                if ($existing) {
                    return redirect()->back()
                        ->with('error', 'Server ini sedang dalam pemeliharaan yang masih berlangsung')
                        ->withInput();
                }

                // Ambil bidang_id dari server
                $server = Server::find($request->server_id);
                $bidangId = $server->bidang_id;
                $assetName = $server->nama_server; // TAMBAHKAN

            } else {
                $existing = Pemeliharaan::where('website_id', $request->website_id)
                    ->where('status_pemeliharaan', 'berlangsung')
                    ->exists();

                if ($existing) {
                    return redirect()->back()
                        ->with('error', 'Website ini sedang dalam pemeliharaan yang masih berlangsung')
                        ->withInput();
                }

                // Ambil bidang_id dari website
                $website = Website::find($request->website_id);
                $bidangId = $website->bidang_id;
                $assetName = $website->nama_website; // TAMBAHKAN
            }

            $keterangan = strip_tags($request->keterangan);

            $pemeliharaan = Pemeliharaan::create([
                'server_id' => $request->jenis_asset == 'server' ? $request->server_id : null,
                'website_id' => $request->jenis_asset == 'website' ? $request->website_id : null,
                'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
                'status_pemeliharaan' => 'dijadwalkan',
                'keterangan' => $keterangan,
                'bidang_id' => $bidangId,
            ]);

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'CREATE',
                'pemeliharaan',
                "Menambahkan jadwal pemeliharaan untuk {$request->jenis_asset} \"{$assetName}\" pada tanggal " .
                    date('d M Y', strtotime($request->tanggal_pemeliharaan))
            );

            DB::commit();

            return redirect()->route('pemeliharaan')
                ->with('success', 'Jadwal pemeliharaan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jadwal pemeliharaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mulai pemeliharaan - ubah status aset jadi maintenance
     */
    public function start($id)
    {
        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Validasi: hanya bisa dimulai jika statusnya dijadwalkan
            if (!$pemeliharaan->canStart()) {
                return redirect()->back()
                    ->with('error', 'Pemeliharaan ini tidak dapat dimulai. Status saat ini: ' . $pemeliharaan->status_pemeliharaan);
            }

            // Ubah status aset
            if ($pemeliharaan->server_id) {
                $server = Server::findOrFail($pemeliharaan->server_id);

                // Simpan status sebelumnya
                $pemeliharaan->status_sebelumnya = $server->power_status;

                // Ubah status server jadi STANDBY (maintenance)
                $server->update(['power_status' => 'STANDBY']);

                $assetName = $server->nama_server;
                $assetType = 'Server';
            } else {
                $website = Website::findOrFail($pemeliharaan->website_id);

                // Simpan status sebelumnya
                $pemeliharaan->status_sebelumnya = $website->status;

                // Ubah status website jadi maintenance
                $website->update(['status' => 'maintenance']);

                $assetName = $website->nama_website;
                $assetType = 'Website';
            }

            // Update status pemeliharaan
            $pemeliharaan->update([
                'status_pemeliharaan' => 'berlangsung',
                'status_sebelumnya' => $pemeliharaan->status_sebelumnya
            ]);

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'UPDATE',
                'pemeliharaan',
                "Memulai pemeliharaan {$assetType} \"{$assetName}\" - Status diubah menjadi Maintenance"
            );

            DB::commit();

            return redirect()->back()
                ->with('success', "Pemeliharaan {$assetType} \"{$assetName}\" telah dimulai. Status diubah menjadi Maintenance.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memulai pemeliharaan: ' . $e->getMessage());
        }
    }

    /**
     * Selesaikan pemeliharaan - kembalikan status aset ke semula
     */
    public function finish($id)
    {
        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Validasi: hanya bisa diselesaikan jika statusnya berlangsung
            if (!$pemeliharaan->canFinish()) {
                return redirect()->back()
                    ->with('error', 'Pemeliharaan ini tidak dapat diselesaikan. Status saat ini: ' . $pemeliharaan->status_pemeliharaan);
            }

            // Kembalikan status aset
            if ($pemeliharaan->server_id) {
                $server = Server::findOrFail($pemeliharaan->server_id);

                // Kembalikan ke status sebelumnya (default: ON jika null)
                $statusKembali = $pemeliharaan->status_sebelumnya ?? 'ON';
                $server->update(['power_status' => $statusKembali]);

                $assetName = $server->nama_server;
                $assetType = 'Server';
            } else {
                $website = Website::findOrFail($pemeliharaan->website_id);

                // Kembalikan ke status sebelumnya (default: active jika null)
                $statusKembali = $pemeliharaan->status_sebelumnya ?? 'active';
                $website->update(['status' => $statusKembali]);

                $assetName = $website->nama_website;
                $assetType = 'Website';
            }

            // Update status pemeliharaan
            $pemeliharaan->update([
                'status_pemeliharaan' => 'selesai',
                'tanggal_selesai_aktual' => now()
            ]);

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'UPDATE',
                'pemeliharaan',
                "Menyelesaikan pemeliharaan {$assetType} \"{$assetName}\" - Status dikembalikan ke {$statusKembali}"
            );

            DB::commit();

            return redirect()->back()
                ->with('success', "Pemeliharaan {$assetType} \"{$assetName}\" telah selesai. Status dikembalikan ke semula.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan pemeliharaan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pemeliharaan
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Validasi
            if (!$pemeliharaan->canCancel()) {
                return redirect()->back()
                    ->with('error', 'Pemeliharaan ini tidak dapat dibatalkan');
            }

            // Ambil nama asset untuk logging
            $assetName = $pemeliharaan->asset_name;
            $assetType = $pemeliharaan->server_id ? 'Server' : 'Website';

            // Jika sedang berlangsung, kembalikan status aset dulu
            if ($pemeliharaan->status_pemeliharaan === 'berlangsung') {
                if ($pemeliharaan->server_id) {
                    $server = Server::findOrFail($pemeliharaan->server_id);
                    $statusKembali = $pemeliharaan->status_sebelumnya ?? 'ON';
                    $server->update(['power_status' => $statusKembali]);
                } else {
                    $website = Website::findOrFail($pemeliharaan->website_id);
                    $statusKembali = $pemeliharaan->status_sebelumnya ?? 'active';
                    $website->update(['status' => $statusKembali]);
                }
            }

            // Update status pemeliharaan
            $pemeliharaan->update([
                'status_pemeliharaan' => 'dibatalkan'
            ]);

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'UPDATE',
                'pemeliharaan',
                "Membatalkan pemeliharaan {$assetType} \"{$assetName}\""
            );

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pemeliharaan berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membatalkan pemeliharaan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'server_id' => 'required_if:jenis_asset,server|nullable|exists:server,server_id',
            'website_id' => 'required_if:jenis_asset,website|nullable|exists:website,website_id',
            'keterangan' => 'required|string|max:1000',
        ], [
            'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan harus diisi',
            'jenis_asset.required' => 'Jenis asset harus dipilih',
            'server_id.required_if' => 'Server harus dipilih',
            'website_id.required_if' => 'Website harus dipilih',
            'keterangan.required' => 'Keterangan harus diisi',
        ]);

        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Tidak bisa edit jika sedang berlangsung atau sudah selesai
            if (in_array($pemeliharaan->status_pemeliharaan, ['berlangsung', 'selesai'])) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat mengedit pemeliharaan yang sedang berlangsung atau sudah selesai');
            }

            $bidangId = null;
            $assetName = null; // TAMBAHKAN

            // Ambil bidang_id dari asset yang dipilih
            if ($request->jenis_asset == 'server') {
                $server = Server::find($request->server_id);
                $bidangId = $server->bidang_id;
                $assetName = $server->nama_server; // TAMBAHKAN
            } else {
                $website = Website::find($request->website_id);
                $bidangId = $website->bidang_id;
                $assetName = $website->nama_website; // TAMBAHKAN
            }

            $keterangan = strip_tags($request->keterangan);

            $pemeliharaan->update([
                'server_id' => $request->jenis_asset == 'server' ? $request->server_id : null,
                'website_id' => $request->jenis_asset == 'website' ? $request->website_id : null,
                'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
                'keterangan' => $keterangan,
                'bidang_id' => $bidangId,
            ]);

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'UPDATE',
                'pemeliharaan',
                "Mengupdate jadwal pemeliharaan {$request->jenis_asset} \"{$assetName}\" menjadi tanggal " .
                    date('d M Y', strtotime($request->tanggal_pemeliharaan))
            );

            DB::commit();

            return redirect()->route('pemeliharaan')
                ->with('success', 'Data pemeliharaan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data pemeliharaan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Tidak bisa hapus jika sedang berlangsung
            if ($pemeliharaan->status_pemeliharaan === 'berlangsung') {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pemeliharaan yang sedang berlangsung');
            }

            // Ambil data untuk logging sebelum dihapus
            $assetName = $pemeliharaan->asset_name;
            $assetType = $pemeliharaan->server_id ? 'Server' : 'Website';
            $tanggal = date('d M Y', strtotime($pemeliharaan->tanggal_pemeliharaan));

            $pemeliharaan->delete();

            // TAMBAHKAN LOGGING
            LogAktivitas::log(
                'DELETE',
                'pemeliharaan',
                "Menghapus jadwal pemeliharaan {$assetType} \"{$assetName}\" tanggal {$tanggal}"
            );

            DB::commit();

            return redirect()->route('pemeliharaan')
                ->with('success', 'Data pemeliharaan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data pemeliharaan: ' . $e->getMessage());
        }
    }
}

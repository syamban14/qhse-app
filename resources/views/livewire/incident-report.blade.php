<div>
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>Lapor Insiden Baru</h4>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Insiden</label>
                            <select id="type" class="form-select" wire:model="type">
                                <option value="">Pilih Jenis</option>
                                <option value="Near Miss">Near Miss (Hampir Celaka)</option>
                                <option value="Injury">Injury (Cedera)</option>
                                <option value="Property Damage">Property Damage (Kerusakan Properti)</option>
                                <option value="Environmental">Environmental (Lingkungan)</option>
                            </select>
                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="incident_date" class="form-label">Tanggal & Waktu Insiden</label>
                            <input type="datetime-local" id="incident_date" class="form-control" wire:model="incident_date">
                            @error('incident_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" id="location" class="form-control" wire:model="location" placeholder="Contoh: Area Produksi Gedung A">
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Singkat</label>
                            <textarea id="description" class="form-control" wire:model="description" rows="4" placeholder="Jelaskan apa yang terjadi..."></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Laporan Insiden</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incidents as $incident)
                                    <tr>
                                        <td>{{ $incident->type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($incident->incident_date)->format('d M Y, H:i') }}</td>
                                        <td>{{ $incident->location }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $incident->status }}</span></td>
                                        <td>
                                            <a href="/incidents/{{ $incident->id }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada laporan insiden.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $incidents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

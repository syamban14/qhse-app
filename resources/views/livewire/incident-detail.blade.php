<div>
    <div class="mb-4">
        <a href="/incidents" class="btn btn-sm btn-outline-secondary">&larr; Kembali ke Daftar Insiden</a>
    </div>

    <div class="row">
        <!-- Kolom Detail Insiden -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Detail Insiden</h4>
                </div>
                <div class="card-body">
                    <p><strong>Jenis:</strong> {{ $incident->type }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($incident->incident_date)->format('d M Y, H:i') }}</p>
                    <p><strong>Lokasi:</strong> {{ $incident->location }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-warning text-dark">{{ $incident->status }}</span></p>
                    <hr>
                    <p><strong>Deskripsi:</strong></p>
                    <p>{{ $incident->description }}</p>
                </div>
            </div>
        </div>

        <!-- Kolom Form Tambah Tindakan -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Tambah Tindakan Perbaikan</h4>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <form wire:submit.prevent="saveAction">
                        <div class="mb-3">
                            <label for="action_description" class="form-label">Deskripsi Tindakan</label>
                            <textarea id="action_description" class="form-control" wire:model="description" rows="3" placeholder="Contoh: Perbaiki pagar pengaman..."></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Ditugaskan Kepada</label>
                            <select id="assigned_to" class="form-select" wire:model="assigned_to">
                                <option value="">Pilih Pengguna</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Tenggat Waktu</label>
                            <input type="date" id="due_date" class="form-control" wire:model="due_date">
                            @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Tindakan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Tindakan -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Tindakan</h4>
                </div>
                <div class="card-body">
                    @if (session()->has('status_update_message'))
                        <div class="alert alert-info mb-3">
                            {{ session('status_update_message') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Deskripsi</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Tenggat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incident->actions as $action)
                                    <tr>
                                        <td>{{ $action->description }}</td>
                                        <td>{{ $action->assignee->name ?? 'N/A' }}</td>
                                        <td>{{ $action->due_date ? \Carbon\Carbon::parse($action->due_date)->format('d M Y') : 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Open' => 'warning',
                                                    'In Progress' => 'info',
                                                    'Completed' => 'success',
                                                    'Cancelled' => 'secondary',
                                                ];
                                                $color = $statusColors[$action->status] ?? 'light';
                                            @endphp
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-{{ $color }} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ $action->status }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="updateActionStatus({{ $action->id }}, 'Open')">Open</a></li>
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="updateActionStatus({{ $action->id }}, 'In Progress')">In Progress</a></li>
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="updateActionStatus({{ $action->id }}, 'Completed')">Completed</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="updateActionStatus({{ $action->id }}, 'Cancelled')">Cancelled</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada tindakan untuk insiden ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

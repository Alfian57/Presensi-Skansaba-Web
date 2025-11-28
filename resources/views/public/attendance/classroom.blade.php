@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2"><i class="fas fa-clipboard-list me-2"></i>Daftar Presensi Siswa</h2>
                        <h4 class="mb-2"><i class="fas fa-school me-2"></i>{{ $classroom->name }}</h4>
                        <p class="mb-0 opacity-75">
                            <i class="far fa-calendar me-2"></i>{{ $today->isoFormat('dddd, D MMMM Y') }}
                            <span class="ms-3"><i class="far fa-clock me-2"></i><span id="current-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span></span>
                        </p>
                    </div>
                    <div class="text-end mt-3 mt-md-0">
                        <a href="{{ route('dashboard.display.attendance.today') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                @php
                    $totalStudents = $classroom->students->count();
                    $presentCount = 0;
                    $sickCount = 0;
                    $permissionCount = 0;
                    $absentCount = 0;

                    $studentsWithAttendance = [];

                    foreach ($classroom->students as $student) {
                        $attendance = $student->attendances->first();
                        $status = 'absent';
                        $statusLabel = 'Alpha';
                        $badgeClass = 'badge-alpha';
                        $checkInTime = '-';
                        $checkOutTime = '-';

                        if ($attendance) {
                            $status = $attendance->status->value;
                            $checkInTime = $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-';
                            $checkOutTime = $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-';

                            switch ($status) {
                                case 'present':
                                    $statusLabel = 'Hadir';
                                    $badgeClass = 'badge-hadir';
                                    $presentCount++;
                                    break;
                                case 'late':
                                    $statusLabel = 'Terlambat';
                                    $badgeClass = 'badge-terlambat';
                                    $presentCount++;
                                    break;
                                case 'sick':
                                    $statusLabel = 'Sakit';
                                    $badgeClass = 'badge-sakit';
                                    $sickCount++;
                                    break;
                                case 'permission':
                                    $statusLabel = 'Izin';
                                    $badgeClass = 'badge-izin';
                                    $permissionCount++;
                                    break;
                                case 'absent':
                                    $statusLabel = 'Alpha';
                                    $badgeClass = 'badge-alpha';
                                    $absentCount++;
                                    break;
                            }
                        } else {
                            $absentCount++;
                        }

                        $studentsWithAttendance[] = [
                            'student' => $student,
                            'statusLabel' => $statusLabel,
                            'badgeClass' => $badgeClass,
                            'checkInTime' => $checkInTime,
                            'checkOutTime' => $checkOutTime,
                        ];
                    }
                @endphp

                {{-- Summary Statistics --}}
                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Ringkasan Presensi</h5>
                    </div>
                    @php
                        $percentage = $totalStudents > 0 ? round((($presentCount) / $totalStudents) * 100, 1) : 0;
                    @endphp
                    <div class="col-12 mb-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Total Siswa</span>
                                    <strong class="h4 mb-0">{{ $totalStudents }}</strong>
                                </div>
                                <div class="progress mt-2" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                                        {{ $percentage }}% Hadir
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-3">
                        <div class="card border-0 h-100 stat-card bg-success-light">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle text-success mb-2" style="font-size: 2.5rem;"></i>
                                <h2 class="mb-0 text-success">{{ $presentCount }}</h2>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-3">
                        <div class="card border-0 h-100 stat-card bg-warning-light">
                            <div class="card-body text-center">
                                <i class="fas fa-notes-medical text-warning mb-2" style="font-size: 2.5rem;"></i>
                                <h2 class="mb-0 text-warning">{{ $sickCount }}</h2>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-3">
                        <div class="card border-0 h-100 stat-card bg-info-light">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt text-info mb-2" style="font-size: 2.5rem;"></i>
                                <h2 class="mb-0 text-info">{{ $permissionCount }}</h2>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-3">
                        <div class="card border-0 h-100 stat-card bg-danger-light">
                            <div class="card-body text-center">
                                <i class="fas fa-times-circle text-danger mb-2" style="font-size: 2.5rem;"></i>
                                <h2 class="mb-0 text-danger">{{ $absentCount }}</h2>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Student List --}}
                <div class="row">
                    <div class="col-12 mb-3">
                        <h5 class="mb-0"><i class="fas fa-list me-2 text-primary"></i>Daftar Siswa</h5>
                    </div>
                </div>

                @if($totalStudents === 0)
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada siswa</h4>
                        <p class="mb-0 text-muted">Belum ada siswa yang terdaftar di kelas ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th class="text-center" style="width: 120px;">
                                        <i class="fas fa-sign-in-alt me-1"></i>Jam Masuk
                                    </th>
                                    <th class="text-center" style="width: 120px;">
                                        <i class="fas fa-sign-out-alt me-1"></i>Jam Keluar
                                    </th>
                                    <th class="text-center" style="width: 130px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentsWithAttendance as $index => $item)
                                    <tr class="student-row">
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-muted me-2" style="font-size: 1.5rem;"></i>
                                                <strong>{{ $item['student']->name }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $item['student']->nisn }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                <i class="far fa-clock me-1"></i>{{ $item['checkInTime'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <i class="far fa-clock me-1"></i>{{ $item['checkOutTime'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $badgeConfig = [
                                                    'badge-hadir' => ['class' => 'success', 'icon' => 'check-circle'],
                                                    'badge-terlambat' => ['class' => 'warning', 'icon' => 'clock'],
                                                    'badge-sakit' => ['class' => 'info', 'icon' => 'notes-medical'],
                                                    'badge-izin' => ['class' => 'primary', 'icon' => 'file-alt'],
                                                    'badge-alpha' => ['class' => 'danger', 'icon' => 'times-circle'],
                                                ];
                                                $config = $badgeConfig[$item['badgeClass']] ?? ['class' => 'secondary', 'icon' => 'question'];
                                            @endphp
                                            <span class="badge bg-{{ $config['class'] }}" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $item['statusLabel'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }
        
        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        
        .student-row {
            transition: background-color 0.2s ease;
        }
        
        .student-row:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
    </style>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        
        setInterval(updateTime, 1000);
        
        // Auto refresh every 30 seconds
        setTimeout(function () {
            location.reload();
        }, 30000);
    </script>
@endsection
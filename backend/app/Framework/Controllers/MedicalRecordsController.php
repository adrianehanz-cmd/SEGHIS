<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class MedicalRecordsController
{
    public function index(Request $request): void
    {
        $patientPid = trim((string) ($request->query()['patient_pid'] ?? ''));
        if ($patientPid === '') {
            Response::json(null, 'patient_pid is required.', 422);
        }

        Response::json([
            'patient_pid' => $patientPid,
            'notice' => 'Demonstration data only - not a clinical record.',
            'medical_history' => [
                ['date' => '2026-06-12', 'title' => 'Outpatient consultation', 'details' => 'Sample consultation note: patient reported mild fever and cough.'],
                ['date' => '2026-02-03', 'title' => 'Routine check-up', 'details' => 'Sample wellness assessment. No urgent findings recorded.'],
            ],
            'test_results' => [
                ['date' => '2026-06-12', 'test' => 'Complete blood count', 'result' => 'Within sample reference range', 'status' => 'normal'],
                ['date' => '2026-06-12', 'test' => 'Chest X-ray', 'result' => 'No acute sample findings', 'status' => 'normal'],
            ],
        ], 'Sample medical records retrieved successfully.');
    }
}

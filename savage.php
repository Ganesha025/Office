<?php

namespace App\Services;

use App\Models\StudentAcademicSummaryModel;
use App\Models\StudentRiskPredictionModel;
use App\Models\StudentModel;

class RiskRuleEngine
{
    protected $academicModel;
    protected $studentModel;
    protected $predictionsModel;

    public function __construct()
    {
        $this->academicModel    = model(StudentAcademicSummaryModel::class);
        $this->studentModel     = model(StudentModel::class);
        $this->predictionsModel = model(StudentRiskPredictionModel::class);
    }
public function fetchAllStudentsData(){
    // 1. Fetch all academic records
    $students = $this->academicModel->findAll();

    $result = [];

    foreach ($students as $student) {
        // 2. Fetch student master data using student_id
        $studentMaster = $this->studentModel
            ->where('student_id', $student['student_id'])
            ->first();
              // 3. Calculate pending fee safely
            $departmentFee = $studentMaster['department_fees_amount'];
            
            $student['department_fees_amount']=$departmentFee; 
            $paidFee       = $student['fee_due_amount'];
        $student['pending_amount'] = $paidFee > 0 ? $departmentFee - $paidFee : 0;
            $riskScore = $this->calculateRisk($student);
            $remarks   = $this->generateRemarks($student);
            
            $level= $this->getRiskLevel($riskScore);
        // Data to insert (ONE ROW)
        $predictionData = [
            'student_id'       => $studentMaster['id'],
            'risk_score'       => $riskScore,   // fixed typo
            'risk_level'       => $level,
            'ai_remarks'       => $remarks,
            'prediction_date'  => date('Y-m-d'),
        ];

        // Save prediction
        if($riskScore!=0){
            
        $this->predictionsModel->save($predictionData);

        }
        // Collect for response
        // $result[] = array_merge(['id' => $student['id']], $predictionData);
        $result[]=[
            'Academic'=>$studentMaster,
            'Student'=>$student,
            'Score'=>$riskScore
        ];
    }

    return $result;
}

    public function calculateAll(){
        // 1. Fetch all academic records
        $students = $this->academicModel->findAll();

        foreach ($students as $student) {

            // 2. Fetch student master data using student_id
            $studentMaster = $this->studentModel
                ->where('student_id', $student['student_id'])
                ->first();

            // 3. Calculate pending fee safely
            $departmentFee = $studentMaster['department_fees_amount'] ?? 0;
            $paidFee       = $student['fee_due_amount'] ?? 0;
            $pendingAmount = $departmentFee - $paidFee;

            // 4. Add pending amount to student array
            $student['pending_amount'] = $pendingAmount;

            // 5. Calculate risk
            $riskScore = $this->calculateRisk($student);
            $riskLevel = $this->getRiskLevel($riskScore);
            $remarks   = $this->generateRemarks($student);
        $this->predictionsModel->save([
    'student_id'      => $student['student_id'],
    'risk_score'      => $riskScore,
    'risk_level'      => $riskLevel,
    'ai_remarks'      => $remarks,
    'prediction_date' => date('Y-m-d')
]);

        }

        return true;
    }

public function calculateRisk(array $s): int{
    $attendance = (float) ($s['attendance_percentage'] ?? 100);
    $marks = (float) ($s['avg_internal_marks'] ?? 50);
    $pending = (float) ($s['pending_amount'] ?? 0);
    $departmentFee = (float) ($s['department_fees_amount'] ?? 0);
    $incidents = (int) ($s['incident_count'] ?? 0);

    $risk = 0;

  if ($incidents == 10) {
    $risk = 100;
} elseif ($incidents == 9) {
    $risk = 85;
    if ($marks < 20 || $attendance < 75) {
        $risk = 100;
    } elseif ($marks >= 35 && $attendance >= 85 && $pending == 0) {
        $risk = 70;
    } else {
        // compare marks with 50
        $risk += (int) round((50 - min($marks,50)) / 50 * 15);
    }
} elseif ($incidents == 8) {
    $risk = 75;
    if ($marks < 20) {
        $risk += 15;
    } elseif ($marks >= 30 && $attendance >= 80) {
        $risk -= 10;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 12);
    }
} elseif ($incidents == 7) {
    $risk = 65;
    if ($marks < 20 || $attendance < 65) {
        $risk += 10;
    } elseif ($marks >= 35 && $attendance >= 85) {
        $risk -= 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 10);
    }
} elseif ($incidents == 6) {
    $risk = 55;
    if ($marks < 20) {
        $risk += 10;
    } elseif ($marks >= 30 && $attendance >= 80) {
        $risk -= 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 8);
    }
} elseif ($incidents == 5) {
    $risk = 50;
    if ($marks < 20 || $attendance < 65) {
        $risk += 10;
    } elseif ($marks >= 35 && $attendance >= 85) {
        $risk -= 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 7);
    }
} elseif ($incidents == 4) {
    $risk = 40;
    if ($marks < 20) {
        $risk += 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 5);
    }
} elseif ($incidents == 3) {
    $risk = 35;
    if ($marks < 20) {
        $risk += 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 4);
    }
} elseif ($incidents == 2) {
    $risk = 20;
    if ($marks < 20) {
        $risk += 15;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 2);
    }
} elseif ($incidents == 1) {
    $risk = 10;
    if ($marks >= 40) {
        $risk += 5;
    } else {
        $risk += (int) round((50 - min($marks,50)) / 50 * 1);
    }
} else {
    $risk = 0;
}


if ($marks < 20) {
    if ($incidents >= 5) {
        $risk += (int) round((50 - $marks) / 50 * 30); // scale proportionally to max 30 points
    } else {
        $risk += ($marks < 10 ? 25 : 15);
    }
} elseif ($marks < 30) {
    if ($incidents >= 5) {
        $risk += (int) round((50 - $marks) / 50 * 10); // small adjustment for mid-level incidents
    } else {
        $risk += 5;
    }
} elseif ($marks >= 40) {
    $risk -= 10;
}


    if ($pending == 0) {
        $risk += 0;
    } elseif ($pending > 0) {
        $pendingPercent = min(1.0, $pending / max(1, $departmentFee));
        $risk += (int) round($pendingPercent * 25);
    }

    if ($attendance < 50) {
        $risk += 20;
    } elseif ($attendance < 65) {
        $risk += 12;
    } elseif ($attendance <= 75) {
        $risk += 2;
    } elseif ($attendance >= 90) {
        $risk -= 5;
    }

    if ($incidents >= 7 && $risk < 50) {
        $risk = 50;
    }

    if ($marks < 20 && $attendance < 60) {
        $risk = max($risk, 75);
    }

    return max(0, min((int) round($risk), 100));
}


public function getRiskLevel(int $score): string{
        if ($score <= 40) {
            return 'LOW';
        } elseif ($score <= 70) {
            return 'MEDIUM';
        }
        return 'HIGH';
    }

public function generateRemarks(array $s): string
{
    $remarks = [];

    $attendance = (float) ($s['attendance_percentage'] ?? 100);
    $marks      = (float) ($s['avg_internal_marks'] ?? 50);
    $pending    = (float) ($s['pending_amount'] ?? 0);
    $department = (float) ($s['department_fees_amount'] ?? 0);
    $incidents  = (int)   ($s['incident_count'] ?? 0);

    /* ---------------- Behavioral Risk ---------------- */
    if ($incidents >= 10) {
        $remarks[] = 'Critical behavioral risk (extreme incident count)';
    } elseif ($incidents >= 9) {
        $remarks[] = 'Severe behavioral instability';
    } elseif ($incidents >= 7) {
        $remarks[] = 'High behavioral concern';
    } elseif ($incidents >= 5) {
        $remarks[] = 'Repeated disciplinary issues';
    } elseif ($incidents >= 3) {
        $remarks[] = 'Moderate behavioral warning';
    } elseif ($incidents >= 1) {
        $remarks[] = 'Minor behavioral concern';
    }

    /* ---------------- Academic Risk ---------------- */
    if ($marks < 10) {
        $remarks[] = 'Academically critical (very low internal marks)';
    } elseif ($marks < 20) {
        $remarks[] = 'Below minimum academic threshold';
    } elseif ($marks < 30) {
        $remarks[] = 'Weak academic performance';
    } elseif ($marks >= 40) {
        $remarks[] = 'Strong academic standing';
    }

    /* ---------------- Attendance Risk ---------------- */
    if ($attendance < 50) {
        $remarks[] = 'Extremely poor attendance';
    } elseif ($attendance < 65) {
        $remarks[] = 'Attendance below acceptable limit';
    } elseif ($attendance <= 75) {
        $remarks[] = 'Marginal attendance';
    } elseif ($attendance >= 90) {
        $remarks[] = 'Excellent attendance record';
    }

    /* ---------------- Financial Risk ---------------- */
    if ($pending > 0) {
        if ($department > 0) {
            $pendingPercent = $pending / $department;
            if ($pendingPercent >= 0.75) {
                $remarks[] = 'Severe fee default';
            } elseif ($pendingPercent >= 0.40) {
                $remarks[] = 'High outstanding fees';
            } else {
                $remarks[] = 'Pending fees require attention';
            }
        } else {
            $remarks[] = 'Outstanding fee balance';
        }
    }

    /* ---------------- Combined Risk Indicators ---------------- */
    if ($marks < 20 && $attendance < 60) {
        $remarks[] = 'Academics and attendance jointly at critical risk';
    }

    if ($incidents >= 7 && $marks < 30) {
        $remarks[] = 'Behavioral issues impacting academic performance';
    }

    if ($incidents >= 7 && $attendance < 65) {
        $remarks[] = 'Discipline problems affecting attendance';
    }

    return empty($remarks)
        ? 'No significant risk indicators detected'
        : implode(', ', array_unique($remarks));
}



}

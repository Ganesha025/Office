<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\RiskRuleEngine;
use App\Models\StudentAcademicSummaryModel;
use App\Models\StudentModel;
use \App\Models\StudentRiskPredictionModel;
class RiskPredictionController extends BaseController{
 public function index(){
        $riskModel = model(StudentRiskPredictionModel::class);
        $studentModel = model(StudentModel::class);
        $data['dropout_by_department'] = $riskModel
            ->select('students.department_name, COUNT(student_risk_predictions.student_id) AS total')
            ->join('students', 'students.id = student_risk_predictions.student_id')
            ->where('student_risk_predictions.risk_level', 'HIGH')
            ->groupBy('students.department_name')->orderBy('total', 'DESC')
            ->findAll();
$data['students_by_department'] = $riskModel
    ->select('students.department_name, COUNT(students.id) AS total')
    ->join('students', 'students.id = student_risk_predictions.student_id', 'left')
    ->groupBy('students.department_name')
    ->orderBy('total', 'DESC')
    ->findAll();
        $defaultDept = '';
        if (!empty($data['dropout_by_department'])) {
            $totals = array_column($data['dropout_by_department'], 'total');
            $maxIndex = array_search(max($totals), $totals);
            $defaultDept = $data['dropout_by_department'][$maxIndex]['department_name'] ?? '';
        }
        $data['defaultDept'] = $defaultDept;
      $dept = $data['defaultDept'];
$data['students_risk_fee'] = $riskModel
    ->select('
        student_risk_predictions.student_id,
        student_academic_summary.student_name,
        student_academic_summary.fee_due_amount,
        student_risk_predictions.risk_score
    ')
    ->join('students', 'students.id = student_risk_predictions.student_id')
    ->join('student_academic_summary', 'student_academic_summary.student_id = students.student_id')
    // ->orderBy('student_risk_predictions.risk_score', 'ASC')
    ->findAll();

$data['predictions'] = $riskModel
    ->select('
        student_risk_predictions.id,
        students.student_id AS roll_no,
        student_academic_summary.student_name,
        students.department_name,
        student_academic_summary.avg_internal_marks,
        student_academic_summary.fee_due_amount,
        student_academic_summary.incident_count,
        student_academic_summary.attendance_percentage,
        student_risk_predictions.risk_score,
        student_risk_predictions.risk_level
    ')
    ->join('students', 'students.id = student_risk_predictions.student_id')
    ->join(
        'student_academic_summary',
        'student_academic_summary.student_id = students.student_id'
    )
    ->where('students.department_name', $dept)
    ->groupBy('student_risk_predictions.id')
    ->findAll();
$data['lowHigh'] = $riskModel
    ->select('risk_level, COUNT(*)')
    ->groupBy('risk_level')
    ->findAll();
     $data['incident_analysis'] = $riskModel
            ->select('students.department_name, SUM(student_academic_summary.incident_count) AS total_incidents')
            ->join('students', 'students.id = student_risk_predictions.student_id')
            ->join('student_academic_summary', 'student_academic_summary.student_id = students.student_id')
            ->groupBy('students.department_name')
            ->findAll();
$data['all_departments'] = $studentModel
    ->select('department_name, department_fees_amount')
    ->groupBy('department_name, department_fees_amount') // ensures uniqueness
    ->orderBy('department_name', 'ASC')
    ->findAll();

// Get stacked data with students
$studentModel = model(StudentModel::class);

// Get all departments with counts
$stackedData = $studentModel
    ->select('
        students.department_name,
        students.department_fees_amount
    ')
    ->groupBy('students.department_name, students.department_fees_amount')
    ->orderBy('students.department_name', 'ASC')
    ->findAll();

// Add student-level paid/unpaid details
foreach ($stackedData as &$dept) {
   $students = $studentModel
        ->select('
            students.id,
            student_academic_summary.student_name,
            student_academic_summary.fee_due_amount,
            students.department_fees_amount
        ')
        ->join(
            'student_academic_summary',
            'student_academic_summary.student_id = students.student_id'
        )
        ->where('students.department_name', $dept['department_name'])
        ->orderBy('students.id', 'DESC')
        ->findAll();
    $dept['paid_students'] = [];
    $dept['unpaid_students'] = [];

    foreach ($students as $s) {
        $paidAmount = $s['department_fees_amount'] - $s['fee_due_amount'];
        $unpaidAmount = $s['fee_due_amount'];

        if ($unpaidAmount > 0) {
            $dept['unpaid_students'][] = ['name' => $s['student_name'], 'amount' => $unpaidAmount];
        } else {
            $dept['paid_students'][] = ['name' => $s['student_name'], 'amount' => $paidAmount];
        }
    }

    // Optional: total counts
    $dept['paid_count'] = count($dept['paid_students']);
    $dept['unpaid_count'] = count($dept['unpaid_students']);
}

$data['stacked_bar'] = $stackedData;


        return view('index', $data);
    }

  // 4️⃣ AJAX endpoint to fetch students by department
public function getStudentsByDept()
{
    $dept = $this->request->getPost('department');
    $riskModel = model(StudentRiskPredictionModel::class);

    $students = $riskModel
        ->select('
            student_risk_predictions.id,
            students.student_id AS roll_no,
            student_academic_summary.student_name,
            students.department_name,
            student_academic_summary.avg_internal_marks,
            student_academic_summary.fee_due_amount,
            student_academic_summary.incident_count,
            student_academic_summary.attendance_percentage,
            student_risk_predictions.risk_score,
            student_risk_predictions.risk_level
        ')
        ->join('students', 'students.id = student_risk_predictions.student_id')
        ->join('student_academic_summary', 'student_academic_summary.student_id = students.student_id');

    if ($dept) {
        $students->where('students.department_name', $dept);
    }

    $students = $students->groupBy('student_risk_predictions.id')->findAll();

    return $this->response->setJSON($students);
}

  public function run(){
        try {
            // Initialize your RiskRuleEngine
            $engine = new RiskRuleEngine();

            // Fetch all students data from the engine
            $studentsData = $engine->fetchAllStudentsData();

            // Return JSON response
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Student data fetched successfully',
                'data'    => $studentsData
            ]);

        } catch (\Throwable $e) {
            // Handle errors
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Failed to fetch student data',
                'error'   => $e->getMessage()
            ]);
        }
    }

public function newsave(){
    // Get department details from the form input
    $dept_name = strtoupper(trim($this->request->getPost('department_name')));
    $studentModel = model(StudentModel::class);
    
    // Department code mapping
    $deptCodes = [
        'CIVIL' => 'CIV', 'IT' => 'BIT', 'MECH' => 'MEC', 'AI&ML' => 'AIM', 
        'AI&DS' => 'AID', 'CYBER SECURITY' => 'CYS', 'ECE' => 'ECE', 
        'EEE' => 'EEE', 'CSE' => 'CSE', 'EIE' => 'EIE'
    ];
    $deptCode = $deptCodes[$dept_name] ?? 'KASC';
    $yearShort = substr(date('Y'), 2);

    // Check if the department exists and retrieve fees
    $existingDept = $studentModel->where('department_name', $dept_name)->first();
    $fees_amount = $existingDept['department_fees_amount'] ?? 0;

    // Generate student ID
    $latest = $studentModel->like('student_id', $yearShort . $deptCode, 'after')->orderBy('student_id', 'DESC')->first();
    $seq = $latest ? intval(preg_replace('/\D/', '', $latest['student_id'])) + 1 : 100;
    if ($seq > 200) return $this->response->setJSON(['status' => 'error', 'message' => 'Max students reached']);
    
    $student_id = $yearShort . $deptCode . $seq;

    // Insert the new student record
    $studentModel->insert(['student_id' => $student_id, 'department_name' => $dept_name, 'department_fees_amount' => $fees_amount]);

    // Get student academic data from form input
    $name = $this->request->getPost('student_name');
    $attendance = $this->request->getPost('attendance_percentage');
    $incident_count = $this->request->getPost('incident_count');
    $internal_mark = $this->request->getPost('avg_internal_marks');
    $fees_due = $this->request->getPost('remaining_amount');
    
    // Insert the academic summary, linking student_id with the newly created student
    $academicModel = model(StudentAcademicSummaryModel::class);
    $academicModel->insert([
        'student_id' => $student_id, // this links to students table's student_id
        'student_name' => $name, 
        'attendance_percentage' => $attendance,
        'avg_internal_marks' => $internal_mark, 
        'fee_due_amount' => $fees_due, 
        'incident_count' => $incident_count
    ]);

    // Get the ID of the inserted student academic record (this will be the foreign key for student_risk_predictions)
    $student_ids = $studentModel->where('student_id', $student_id)->first();
    $academicSummaryId = $student_ids['id']; // Get the id of the newly inserted academic summary record

    // Load RiskRuleEngine service
    $engine = new RiskRuleEngine();

    // Prepare the data for risk calculation
    $studentData = [
        'attendance_percentage' => $attendance,
        'avg_internal_marks' => $internal_mark,
        'incident_count' => $incident_count,
        'pending_amount' => $fees_due, // assuming 'pending_amount' is equivalent to 'remaining_amount'
        'department_fees_amount' => $fees_amount
    ];

    // Calculate risk score using the engine's method
    $riskScore = $engine->calculateRisk($studentData);
    
    // Determine risk level using the engine's method
    $riskLevel = $engine->getRiskLevel($riskScore);
    
    // Generate remarks using the engine's method
    $remarks = $engine->generateRemarks($studentData);
    
    // Insert the risk prediction, using the id from student_academic_summary as the student_id in student_risk_predictions
    $predictionModel = model(StudentRiskPredictionModel::class);
    $predictionModel->save([
        'student_id' => $academicSummaryId, // Use the id from student_academic_summary table
        'risk_score' => $riskScore,
        'risk_level' => $riskLevel,
        'ai_remarks' => $remarks,
        'prediction_date' => date('Y-m-d')
    ]);

    // Return a success response with the generated student ID
    return $this->response->setJSON([
        'status' => 'success', 
        'student_id' => $student_id,
        'message' => 'Student added successfully'
    ]);
}public function update(){
    $studentId = $this->request->getPost('student_id');

    $academicModel = model(StudentAcademicSummaryModel::class);
    $riskModel     = model(StudentRiskPredictionModel::class);
    $studentModel  = model(StudentModel::class);

    // Fetch academic summary
    $academic = $academicModel->where('student_id', $studentId)->first();
    if (!$academic) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Student not found']);
    }

    // Allowed updates only
    $attendance   = $this->request->getPost('attendance_percentage');
    $marks        = $this->request->getPost('avg_internal_marks');
    $feeDue       = $this->request->getPost('remaining_amount');
    $incidents    = $this->request->getPost('incident_count');

    // Update academic summary
    $academicModel->update($academic['id'], [
        'attendance_percentage' => $attendance,
        'avg_internal_marks'    => $marks,
        'fee_due_amount'        => $feeDue,
        'incident_count'        => $incidents
    ]);

    // Get department fees (needed for risk calculation)
    $student = $studentModel->where('student_id', $studentId)->first();

    // Recalculate risk
    $engine = new RiskRuleEngine();
    $riskData = [
        'attendance_percentage' => $attendance,
        'avg_internal_marks'    => $marks,
        'incident_count'        => $incidents,
        'pending_amount'        => $feeDue,
        'department_fees_amount'=> $student['department_fees_amount']
    ];

    $riskScore = $engine->calculateRisk($riskData);
    $riskLevel = $engine->getRiskLevel($riskScore);
    $remarks   = $engine->generateRemarks($riskData);

    // Update risk prediction
    $riskModel->where('student_id', $academic['id'])->update([
        'risk_score'      => $riskScore,
        'risk_level'      => $riskLevel,
        'ai_remarks'      => $remarks,
        'prediction_date' => date('Y-m-d')
    ]);

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Student academic data updated'
    ]);
}

public function delete(){
    $studentId = $this->request->getPost('student_id');

    $studentModel  = model(StudentModel::class);
    $academicModel = model(StudentAcademicSummaryModel::class);
    $riskModel     = model(StudentRiskPredictionModel::class);

    // Get academic summary row
    $academic = $academicModel->where('student_id', $studentId)->first();
    if (!$academic) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Student not found'
        ]);
    }

    // Delete order matters
    $riskModel->where('student_id', $academic['id'])->delete();
    $academicModel->delete($academic['id']);
    $studentModel->where('student_id', $studentId)->delete();

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Student deleted successfully'
    ]);
}






}

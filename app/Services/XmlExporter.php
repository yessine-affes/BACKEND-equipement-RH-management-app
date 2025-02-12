<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Certification;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Report;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use SimpleXMLElement;

class XmlExporter {
    protected $existDbService;

    public function __construct($existDbService) {
        $this->existDbService = $existDbService;
    }

    public function exportAdminsToXml() {
        $admins = Admin::all();
        $xml = new SimpleXMLElement('<admins/>');

        foreach ($admins as $admin) {
            $xmlAdmin = $xml->addChild('admin');
            $xmlAdmin->addChild('username', htmlspecialchars($admin->username));
            $xmlAdmin->addChild('email', htmlspecialchars($admin->email));
            // Add other fields if necessary
        }

        return $xml->asXML();
    }

    public function exportCertificationsToXml() {
        $certifications = Certification::all();
        $xml = new SimpleXMLElement('<certifications/>');

        foreach ($certifications as $certification) {
            $xmlCertification = $xml->addChild('certification');
            $xmlCertification->addChild('name', htmlspecialchars($certification->name));
            $xmlCertification->addChild('issuer', htmlspecialchars($certification->issuer));
            $xmlCertification->addChild('issue_date', htmlspecialchars($certification->issue_date));
            $xmlCertification->addChild('expiry_date', htmlspecialchars($certification->expiry_date));
            $xmlCertification->addChild('employee_id', $certification->employee_id);
        }

        return $xml->asXML();
    }

    public function exportEmployeesToXml() {
        $employees = Employee::all();
        $xml = new SimpleXMLElement('<employees/>');

        foreach ($employees as $employee) {
            $xmlEmployee = $xml->addChild('employee');
            $xmlEmployee->addChild('first_name', htmlspecialchars($employee->first_name));
            $xmlEmployee->addChild('last_name', htmlspecialchars($employee->last_name));
            $xmlEmployee->addChild('email', htmlspecialchars($employee->email));
            $xmlEmployee->addChild('admin_id', $employee->admin_id);
            $xmlEmployee->addChild('availability', $employee->availability);
            $xmlEmployee->addChild('speciality', $employee->speciality);
            $xmlEmployee->addChild('score', $employee->score);
        }

        return $xml->asXML();
    }

    public function exportEquipmentsToXml() {
        $equipments = Equipment::all();
        $xml = new SimpleXMLElement('<equipments/>');

        foreach ($equipments as $equipment) {
            $xmlEquipment = $xml->addChild('equipment');
            $xmlEquipment->addChild('name', htmlspecialchars($equipment->name));
            $xmlEquipment->addChild('status', htmlspecialchars($equipment->status));
            $xmlEquipment->addChild('type', htmlspecialchars($equipment->type));
            $xmlEquipment->addChild('availability', $equipment->availability);
            $xmlEquipment->addChild('maintenanceSchedule', json_encode($equipment->maintenanceSchedule)); // Convert array to JSON
        }

        return $xml->asXML();
    }

    public function exportProjectsToXml() {
        $projects = Project::all();
        $xml = new SimpleXMLElement('<projects/>');

        foreach ($projects as $project) {
            $xmlProject = $xml->addChild('project');
            $xmlProject->addChild('title', htmlspecialchars($project->title));
            $xmlProject->addChild('description', htmlspecialchars($project->description));
            $xmlProject->addChild('start_date', $project->start_date);
            $xmlProject->addChild('end_date', $project->end_date);
            $xmlProject->addChild('status', htmlspecialchars($project->status));
        }

        return $xml->asXML();
    }

    public function exportReportsToXml() {
        $reports = Report::all();
        $xml = new SimpleXMLElement('<reports/>');

        foreach ($reports as $report) {
            $xmlReport = $xml->addChild('report');
            $xmlReport->addChild('subject', htmlspecialchars($report->subject));
            $xmlReport->addChild('content', htmlspecialchars($report->content));
            $xmlReport->addChild('employee_id', $report->employee_id);
            $xmlReport->addChild('photo', htmlspecialchars($report->photo));
        }

        return $xml->asXML();
    }

    public function exportTasksToXml() {
        $tasks = Task::all();
        $xml = new SimpleXMLElement('<tasks/>');

        foreach ($tasks as $task) {
            $xmlTask = $xml->addChild('task');
            $xmlTask->addChild('description', htmlspecialchars($task->description));
            $xmlTask->addChild('start_date', $task->start_date);
            $xmlTask->addChild('end_date', $task->end_date);
            $xmlTask->addChild('due_date', $task->due_date);
            $xmlTask->addChild('project_id', $task->project_id);
            $xmlTask->addChild('status', htmlspecialchars($task->status));
        }

        return $xml->asXML();
    }

    public function exportTaskAssignmentsToXml() {
        $taskAssignments = TaskAssignment::all();
        $xml = new SimpleXMLElement('<taskAssignments/>');

        foreach ($taskAssignments as $taskAssignment) {
            $xmlTaskAssignment = $xml->addChild('taskAssignment');
            $xmlTaskAssignment->addChild('task_id', $taskAssignment->task_id);
            $xmlTaskAssignment->addChild('employee_id', $taskAssignment->employee_id);
            $xmlTaskAssignment->addChild('equipment_id', $taskAssignment->equipment_id);
            $xmlTaskAssignment->addChild('assigned_date', $taskAssignment->assigned_date);
            $xmlTaskAssignment->addChild('completion_date', $taskAssignment->completion_date);
            $xmlTaskAssignment->addChild('status', htmlspecialchars($taskAssignment->status));
        }

        return $xml->asXML();
    }

    public function exportUsersToXml() {
        $users = User::all();
        $xml = new SimpleXMLElement('<users/>');

        foreach ($users as $user) {
            $xmlUser = $xml->addChild('user');
            $xmlUser->addChild('name', htmlspecialchars($user->name));
            $xmlUser->addChild('email', htmlspecialchars($user->email));
            // Add other fields if necessary
        }

        return $xml->asXML();
    }

    // Method to seed all XML data to eXist-db
    public function seedDataToExistDb() {
        // Export XML data for each model and seed to eXist-db
        $this->existDbService->createDocument('your_collection', 'admins.xml', $this->exportAdminsToXml());
        $this->existDbService->createDocument('your_collection', 'certifications.xml', $this->exportCertificationsToXml());
        $this->existDbService->createDocument('your_collection', 'employees.xml', $this->exportEmployeesToXml());
        $this->existDbService->createDocument('your_collection', 'equipments.xml', $this->exportEquipmentsToXml());
        $this->existDbService->createDocument('your_collection', 'projects.xml', $this->exportProjectsToXml());
        $this->existDbService->createDocument('your_collection', 'reports.xml', $this->exportReportsToXml());
        $this->existDbService->createDocument('your_collection', 'tasks.xml', $this->exportTasksToXml());
        $this->existDbService->createDocument('your_collection', 'taskAssignments.xml', $this->exportTaskAssignmentsToXml());
        $this->existDbService->createDocument('your_collection', 'users.xml', $this->exportUsersToXml());
    }

    /**
     * @throws \Exception
     */
    public function exportXml($modelClass)
    {
        // Fetch all records from the model
        $records = $modelClass::all();

        // Create a new XML document
        $xml = new SimpleXMLElement('<?xml version="1.0"?><' . strtolower(class_basename($modelClass)) . 's></' . strtolower(class_basename($modelClass)) . 's>');

        // Add records to the XML
        foreach ($records as $record) {
            $recordXml = $xml->addChild(strtolower(class_basename($modelClass)));
            foreach ($record->getAttributes() as $key => $value) {
                $recordXml->addChild($key, htmlspecialchars($value));
            }
        }

        // Save the XML to eXist-db
        $filename = strtolower(class_basename($modelClass)) . 's.xml';
        $this->existDbService->createDocument(config('existdb.collection'), $filename, $xml->asXML());
    }
}


<?php
// controllers/EmployeeController.php
require_once 'models/Employee.php';

class EmployeeController {
    
    // Acción: Listar empleados (Index)
    public function index() {
        // Traer datos crudos de Supabase
        $raw_employees = supabase_request('employees?select=*');
        
        $employees = [];
        $total_payroll = 0;
        
        if (is_array($raw_employees)) {
            foreach ($raw_employees as $emp_data) {
                $employee = new Employee($emp_data);
                $employees[] = $employee;
                $total_payroll += $employee->getNetSalary();
            }
        }
        
        // Cargar las vistas correspondientes
        $view = 'views/index.php';
        require 'views/layout.php';
    }

    // Acción: Mostrar y procesar el formulario de registro (Create)
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir datos del formulario del usuario
            $new_data = [
                'first_name'  => $_POST['first_name'],
                'last_name'   => $_POST['last_name'],
                'national_id' => $_POST['national_id'],
                'email'       => $_POST['email'],
                'department'  => $_POST['department'],
                'job_title'   => $_POST['job_title'],
                'base_salary' => floatval($_POST['base_salary']),
                'bonuses'     => floatval($_POST['bonuses']),
                'deductions'  => floatval($_POST['deductions'])
            ];

            // Enviar a Supabase sin incluir propiedades calculadas
            supabase_request('employees', 'POST', $new_data);
            
            // Redirigir a la página principal
            header('Location: index.php');
            exit;
        }

        // Si es GET, simplemente mostrar el formulario
        $view = 'views/create.php';
        require 'views/layout.php';
    }
}
# Organizar datos de la app

Para organizar la construcción de la base de datos correctamente pondremos aquí las relaciones y características de cada tabla de la base de datos.

----------------------------------------------
## Tables
----------------------------------------------

### Employees 
- id
- last_name
- name
- phone
- city
- dni
- email
- total_hours
- created_at
- update_at

### Project Manager
- id
- last_name
- name
- created_at
- update_at

### Customer 
- id
- name
- phone
- city
- address
- email
- created_at
- update_at

### Project 
- id
- project 
- description 
- id_projectManager
- id_customer
- created_at
- finish_date
- update_at

### Tasks
- id
- task
- description
- id_project
- created_at
- update_at

### Time Codes
- id
- time_code
- description
- created_at
- update_at

### Work Orders 
- id
- work_order
- description 
- order_responsible 
- project
- created_at
- finish_date 
- update_at 

### Working Hours 
- id
- id_employee
- id_time_code
- id_work_order
- id_project
- id_task
- description 
- duration 
- date_worked
- created_at
- update_at

----------------------------------------------
## Tables User Gestion
----------------------------------------------

### Users 
- id
- name
- email
- password
- created_at
- update_at

### Roles
- id
- name
- description
- created_at
- update_at

### Roles Users
- id
- user_id
- role_id 
- created_at
- update_at

----------------------------------------------

## Relations 

### Employee --> User
An employee must have an user in the app 

### Project --> Project Manager
The project must have a project manager but the project manager can be null

### Project --> Customer
The project could have a customer but the customer can be null

### Task --> Project 
A task must be associated with a project

### Project --> Task 
A project can have many task associated with it

### Work Order --> Customer 
A work order must be associated with a customer

### Working Hours --> Employee
All the working hours must be associate with an employee

### Working Hours --> Time Code
A working hour must have a time code 

---------------------------------------------------------------------------------------------------------
If the working hour is associated with the time_code = 200, 900, 901, 905 the relations below could be alone 
---------------------------------------------------------------------------------------------------------

### Working Hours --> Work Order
A working hour must be associated with a work order

### Working Hours --> Task
A working hour must be associated with a task

### Working Hours --> Project
A working hour must be associated with a project

---------------------------------------------------------------------------------------------------------

## Problems 
The main problem now is the relation between the table users and the table employees because when que log up on the app we must only saw the information of his account (employee). --> SOLVE 

Another big problem that we must solve is the addition of workingHours, we must take the employee_id (the user) and insert in the workingHours table but withouth formulary that must be done in the front end. And now we can´t do that because the variable session (id) of the user is different that the employee's id. --> SOLVE (i create a variable id_employee to save the id of the employee active and later we use this variable to add the new working hour )

Another problem when i try to sum the new working hours to the total_hours in the table employee is that it delete the total_hours and can't solve it, tomorrow i will try again --> SOLVE 

---------------------------------------------------------------------------------------------------------

## Tasks 
1. We must do delete and edit for the working hours (only employees) and for the employees (only admin can edit and delete employees)

2. We must add a function that sum the working hours of the employee and show it in the employees table and in a section of sum of working hours, and it must be shown in the working hours table.

3. We must reset the hours of the employee and the table working hours each sunday

4. In the function export to csv we must solve the problem creating a new variable to save the email of the employee 
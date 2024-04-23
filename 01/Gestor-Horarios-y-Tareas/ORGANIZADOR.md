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
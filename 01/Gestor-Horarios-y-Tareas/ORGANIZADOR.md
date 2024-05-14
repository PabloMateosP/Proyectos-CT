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

I really don't know how to solve the organization of the privileges of the user, organiser and admin. I say what they need to think, do and everything. 

---------------------------------------------------------------------------------------------------------

## Tasks 
1. We must do delete and edit for the working hours (only employees) and for the employees (only admin can edit and delete employees)

2. We must add a function that sum the working hours of the employee and show it in the employees table and in a section of sum of working hours, and it must be shown in the working hours table. -> Done 

3. We must reset the hours of the employee and the table working hours each sunday

4. In the function export to csv we must solve the problem creating a new variable to save the email of the employee -> Done

5. We must think perfectly how to organised the privileges of the differents users in the app. 

6. We must planifing the security copies of the database. 

7. I must create a calendary to put the non laboral days and when is an holiday the employees musn't put the hours in the app


-------------------------------------------------------------------------

Hay que hacer: (muchas cosas :[)
    --> Crear un calendario para poner los no laborales y los festivos
    --> Poner método task para ver las tareas de cada proyecto en el botón success
    --> Hacer que cuando se añada un nuevo project manager con un proyecto, al campo id_projectManager de ese proyecto se le añada el id de ese projectManager, además de al editar y al eliminar.
    --> Hay que añadir a los empleados el poder introfucirlos en un proyecto al crearlo
    --> Hay que hacer que el empleado solo vea las tareas del proyecto en el que se encuentra registrado

    !--> Lo más importante es conseguir arreglar el edit de empleados en caso de que un empleado sea cambiado de un proyecto a otro¡


    [Hacer una función que recoja los proyectos de un empleado se comparan con los introducidos por el formulario
    si alguno es igual se deja, si tenía alguno antes pero ahora no se borra la relación y si no tenía ninguno se crean los nuevo]

    Hay que mirar porque cuando entras como admin al crear una tarea no puedes ver los proyectos al igual que otros usuarios excepto empleados 

------------------------------------------------------------------------------------------------------------------------------------------
## SEMANA 4     

Día 07/05/24 
    - He hecho el añadir proyectos con los empleados necesarios, el manager del proyecto y tengo que probar con el cliente 
    - He hecho para añadir managers de proyecto con su proyecto (Hay un fallo, si añade un proyecto con un project manager sale en la tabla project manager pero si se añade un project manager desde el apartado project manager con sus proyectos no sale su proyecto conectado, creo que para ello hay que añadir al crear en el project manager que se añada a la columna del proyecto id_projectManager el id de ese nuevo project manager)
    - Por otra parte hay otro fallo al añadir muchos proyectos para un project manager, se rompe la visualización correcta.
    - He añadido el main y new de customers (Me queda cambiar todos los botones a grupo de botones)
    - He hecho que el empleado solo vea el proyecto en el que se encuentra registrado 

Día 08/05/24
    - He hecho el editar y borrar de apartados como employee, projectManager y diferentes apartados 
    - Un empleado no puede estar sin proyecto, además hay método para añadir si el empleado no tiene proyecto desde el inicio y para actualizar la relación si el empleado ya lo tuviera y fuese cambiado 

Día 09/05/24
    - Llevo todo el día intentando completar la actualización del empleado para que en caso de que sea borrado de un proyecto y puesto en otro se pueda pero no lo consigo. (En parte el botón check cuando se despulsa, no envía valor por lo que no podemos saber cual es el proyecto del que ha sido borrado)

Día 10/05/24
    - He hecho que el empleado solo vea el proyecto en el que se encuentra registrado así como las tareas 
    - Por otra parte hemos completado el edit de empleado para que se pueda cambiar el proyecto elegido cuando sea necesario
    - Añadimos que cuando un empleado quiere crear una tarea solo la pueda crear de los proyectos en los que el ha sido añadido.


-------------------------------------------------------------------------------------------------------------------------------------------

## SEMANA 5 ##

Día 13/05/24
    - He arreglado fallos generales como ordenar y buscar en varias tablas y otros fallos al quitar la tabla work ordes de la base de datos.
    - He añadido la funcionalidad de sumar o restar horas totales si la duración de una working hour es modificada (Método update)
    - He añadido el borrado y creacion de relaciones tanto en tabla empleados como en tabla proyecto
    - He creado el actualizar en la tabla project Manager y el borrado y estoy en ello en la tabla customer 

Día 14/05/24
    - Con los datos del excel procedemos a intentar hacer la función para importar, para ello he tenido que descargar composer y a posterior entrar en el archivo php.ini para descomentar las líneas extension=gd y extension=zip para que el comando require phpoffice/phpspreadsheet funcionase

# Organizar datos de la app

Para organizar la construcción de la base de datos correctamente pondremos aquí las relaciones y características de cada tabla de la base de datos.

----------------------------------------------
## Tables
----------------------------------------------

### Employees 
- id
- identification 
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

8. Tengo que hacer que según el tipo de orden de tiempo elegida no haga falta introducir más datos o sí en la working hour 


-------------------------------------------------------------------------

Hay que hacer: (muchas cosas :[)
    --> Tenemos que crear una cuenta regresiva de los festivos, de partida se tienen 24 y por cada día que pasa festivo se le debe de restar uno 
    --> Por otro lado hay que crear una cuenta regresiva de las horas total del empleado contando las horas de más y las horas de menos que hay trabajado.

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
    - Tengo la duda sobre si el empleado está registrado en un único proyecto o en más 
    - Voy a modificar el método de update en project manager 
    - He arreglado el asignar y borrar proyecto a la hora de editar tanto un project manager como un employee
    - He añadido apartado identificador en tabla empleados, falta arreglar el new.

Día 15/05/24
    - He hecho el método importar en empleados "Aunque debe de tener una forma exacta el excel"
    - He creado una nueva página en empleados para ver las horas de este empleado desde la vista manager o admin 
    - He intentado hacer que haya un exportar en esa nueva página pero no consigo pasar el id del empleado a la función 
    - En la página working hours está el exportar más correcto 

Día 16/05/24
    - He hecho que si el código de tiempo es diferente de 1 que es la hora por defecto las tareas y los proyectos puedan ser nulos 
    - He arreglado el ordenar de las tareas 
    - He arreglado el borrado de las tareas para que se ponga nulo el valor en la tabla working hours

Día 17/05/24
    - He intentado hacer el calendario, aún sin funcionalidades pero visible a todos los usuarios. En otras horas buscaré la forma de insertar datos a una base de datos mediante el calendario. 

-------------------------------------------------------------------------------------------------------------------------------------------

## SEMANA 6 ##

Día 20/05/24
    - Estoy intentando hacer el calendario. He conseguido añadir un nuevo evento aunque no puedo mostrarlo en el calendario (creo que para mostrar los datos debe ser en json).
    - He conseguir ver los eventos en el calendario con varios fallos
        1. Se añaden de dos en dos.
        2. No se pueden borrar los eventos.
        3. No se pueden editar los eventos.
        4. No se pueden desplazar 
        5. Al añadir no redirije con los datos para cargar la página correctamente 

Día 21/05/24
    - He conseguido hacer el borrado de los eventos (Aunque hay que crear un método o alguna forma de refrescar el calendario para ver los datos actualizados)
    - Tengo que hacer que cuando se cree un evento no se creen dos sino uno (Cuando se le da a guardar si se le da a refrescar la página introduce dos nuevos eventos iguales pero si se le pulsa al botón calendary no)
    - Tengo que hacer que al crear un evento se refresque el calendario con el nuevo evento

Día 22/05/24
    - He conseguido que al crear un evento se actualice el calendario con el nuevo evento, aunque si pulsas refrescar la web se crean dos eventos y vuelve a la pantalla de login 
    - He conseguido que al borrar recargue la página y oculte el modal.
    - Tengo que intentar hacer el exportar según alguna fecha (Semana, Mes ...) 

Día 23/05/24
    - He conseguido arreglar el crear evento para que no se creen dos eventos.
    - He arreglado exportar de la tabla employee 

Día 24/05/24
    - He intentado hacer la forma de editar un evento aunque no encuentro la manera de pasar el id del evento.
    - He hecho avances con el tema de la exportación de los datos aunque sin conocer la forma es difícil de avanzar. (Había pensado hacer un select poniendo los meses del año)

## SEMANA 7 ##

Día 28/05/24
    - He conseguido hacer el exportar por meses del año en el año en el que nos encontramos (Diferente si es para empleado o para admin_manager)
    - He conseguido exportar mediante semana 
    - He conseguido exportar la semana actual 
    - Blas me ha comunicado que sería necesario añadir al empleado un contador de los festivos de cada persona, restando a medida de lo que queda de año 
    - Blas me ha comunicado que habría que añadir un total de horas por empleado que se reste en caso de que el empleado haya trabajado sus horas de la semana (Ejemplo: una semana he trabajado 50 horas y el total son 1200 pues 1200 menos 50 y me quedan 1160 y 10 horas extras hechas o por si de contrario hubiese trabajado 36 horas que tenga 1164 horas y que ponga que debo 4 horas)

Día 29/05/24
    - Me puse con el modelaje final de las horas totales del empleado en un año con un apartado de horas extras trabajadas y horas de menos  trabajadas
    - Además hay que hacer que el total de las horas totales de la semana ser reestablezcan a las 12 de la noche el domingo 
    - Buscar la forma de que a cada empleado le salgan los días festivos que tiene (Son 23 en total) y que cuando pase el día se reste

Día 30/05/24
    - He pensado que mediante los horarios del calendario de eventos se podría hacer que se restasen los días festivos 
    - Además se pueden añadir dos campos nuevos al empleado para que salgan las horas totales anuales y las horas extras 

Día 31/05/24
    - Seguimiento de arreglos varios
    - Buscamos como ejecutar la app en servidor y como sería su puesta a punto para lanzamiento.

## SEMANA 8 ## 

Día 03/06/24
    - Vine a la empresa pero no había nadie por lo que trabajé de forma online en mi casa explicando código para futuros programadores que usen la app así como búsqueda de la ejecucuión de la app en un servidor

Día 04/06/24
    - Llegando cerca del punto final con la empresa me he puesto a describir todos los documentos y sus funciones levemente para una fácil comprensión a futuro.

Día 05/06/24
    - Añadí al archivo README pautas de cómo debe ser el servidor donde se cimente la app desde mis conocimientos donde creo que debe de ser en un servidor apache junto con mysql y php.

Día 06/06/24
    - Finalizamos de describir métodos y funciones de los controladores, además dejé código con ascii art para su fácil comprensión y encontrar métodos más rápido mediante el minimapa lateral de los editores de código.

Día 07/06/24
    - Empiezo a implementar las horas totales de los empleados en un año como un parámetro nuevo de este y que se reste a medida que se sube una nueva hora trabajada.

## SEMANA 9 ##

Día 10/06/24
    - Debido al poco tiempo que me queda en la empresa y la de cambios que se deben de hacer para implementar las horas totales y para no dejar a medias, he optado por no añadir ese parámetro ya que habría que modificar unos 4 métodos para ello así como la base de datos.
    - He seguido documentando al máximo posible los métodos para que el siguiente que siga tenga fácil comprensión de todo. 
    - Hemos empezado con la implementación en servidor (Dandome cuenta como la seguridad no sé da nada en el grado)

## SEMANA 10 (FINAL) ##

Día 17/06/24
    - El lunes empecé a documentar cambios que hay que hacer en la web para la securización de la misma.

Día 18/06/24
    - He arreglado casos de actualización y borrado en la tabla customer. 
    - He añadido la actualización a la tabla customer.
    - A la hora de borrar un customer he añadido que se actualice la relación con los proyectos.
    - He subido la app al servidor de forma interna y se ha creado el evento que pone las horas totales a 0 cada domingo 
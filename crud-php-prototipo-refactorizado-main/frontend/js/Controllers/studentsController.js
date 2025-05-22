import { studentsAPI } from '../API/studentsAPI.js';

document.addEventListener('DOMContentLoaded', () => 
{
    loadStudents();
    /**
     * Carga la tabla de estudiantes al cargar la página.
     */
    setupFormHandler();
    /**
     * Configura el manejador del formulario de estudiantes.
     * Al enviar el formulario, crea o actualiza el estudiante.
     */
});
  
function setupFormHandler()
{
    const form = document.getElementById('studentForm');
    form.addEventListener('submit', async e => 
    {
        e.preventDefault();
        const student = getFormData();
    
        try 
        {
            /**
             * Si en el submit hay un id, entonces hay que actualizar al estudiante, sino crearlo.
             */
            if (student.id) 
            {
                await studentsAPI.update(student);
            } 
            else 
            {
                await studentsAPI.create(student);
            }
            clearForm();
            /**
             * Limpia el formulario después de crear o actualizar al estudiante.
             * Carga nuevamente la lista de estudiantes.
             * Esto es para que se vea reflejado el cambio en la tabla.
             */
            loadStudents();
        }
        catch (err)
        {
            console.error('Error aca:',err.message);
        }
    });
}
  
function getFormData()
{
    return {
        id: document.getElementById('studentId').value.trim(),
        fullname: document.getElementById('fullname').value.trim(),
        email: document.getElementById('email').value.trim(),
        age: parseInt(document.getElementById('age').value.trim(), 10)
    };
}
  
function clearForm()
{
    document.getElementById('studentForm').reset();
    document.getElementById('studentId').value = '';
}
  
async function loadStudents()
{
    try 
    {
        const students = await studentsAPI.fetchAll();
        /**
         * Carga la lista de estudiantes desde la API y renderiza la tabla.
         * Si hay un error, lo captura y lo muestra en la consola.
         */
        renderStudentTable(students);
    } 
    catch (err) 
    {
        console.error('Error cargando estudiantes:', err.message);
    }
}
  
function renderStudentTable(students)
{
    const tbody = document.getElementById('studentTableBody');
    tbody.replaceChildren();
  
    students.forEach(student => 
    {
        const tr = document.createElement('tr');
        /**
         * Crea una fila para cada estudiante y añade las celdas con la información.
         * Utiliza la función createCell para crear las celdas de la tabla y evitar repetición de código.
         */
        tr.appendChild(createCell(student.fullname));
        tr.appendChild(createCell(student.email));
        tr.appendChild(createCell(student.age.toString()));
        tr.appendChild(createActionsCell(student));
    
        tbody.appendChild(tr);
    });
}
  
function createCell(text)
{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}
  
function createActionsCell(student)
{
    const td = document.createElement('td');
  
    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => fillForm(student));
  
    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDelete(student.id));
  
    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}
  
function fillForm(student)
{
    document.getElementById('studentId').value = student.id;
    document.getElementById('fullname').value = student.fullname;
    document.getElementById('email').value = student.email;
    document.getElementById('age').value = student.age;
}
  
async function confirmDelete(id) 
{
    if (!confirm('¿Estás seguro que deseas borrar este estudiante?')) return;
  
    try 
    {
        /**
         * Confirma la eliminación del estudiante y llama a la API para eliminarlo.
         * Si la eliminación es exitosa, recarga la lista de estudiantes.
         */
        await studentsAPI.remove(id);
        loadStudents();
    } 
    catch (err) 
    {
        console.error('Error al borrar:', err.message);
    }
}
  

const API_URL = `../backend/server.php/students`;

/**
 * Define la URL del backend que se utilizará en todas las llamadas `fetch`.
 */

document.addEventListener('DOMContentLoaded', () => 
{
    /**
     * Espera a que el contenido HTML esté completamente cargado antes de ejecutar el resto del código JavaScript.
     */
    const studentForm = document.getElementById('studentForm');
    const studentTableBody = document.getElementById('studentTableBody');
    const fullnameInput = document.getElementById('fullname');
    const emailInput = document.getElementById('email');
    const ageInput = document.getElementById('age');
    const studentIdInput = document.getElementById('studentId');

    // Leer todos los estudiantes al cargar
    fetchStudents();

    // Formulario: Crear o actualizar estudiante
    studentForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // El preventDefault evita que se recarge la pagina al enviar el formulario

        const formData = {
            fullname: fullnameInput.value,
            email: emailInput.value,
            age: ageInput.value,
        };

        // Crea un objeto con la informacion introducida

        const id = studentIdInput.value;
        const method = id ? 'PUT' : 'POST';
        if (id) formData.id = id;

        // Si hay un ID es pq se esta editando, ya que al crear uno nuevo, no se le asigna ID

        try 
        {
            const response = await fetch(API_URL, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });

            // Envia los datos al backend

            if (response.ok) {
                studentForm.reset();
                studentIdInput.value = '';
                await fetchStudents();
            } else {
                alert("Error al guardar");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener estudiantes y renderizar tabla
    async function fetchStudents() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const students = await res.json();

            //Convierte la respuesta JSON a un array de estudiantes

            //Limpiar tabla de forma segura.
            studentTableBody.replaceChildren();
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //studentTableBody.innerHTML = "";

            students.forEach(student => {
                const tr = document.createElement('tr');

                const tdName = document.createElement('td');
                tdName.textContent = student.fullname;

                const tdEmail = document.createElement('td');
                tdEmail.textContent = student.email;

                const tdAge = document.createElement('td');
                tdAge.textContent = student.age;

                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-light-green', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    fullnameInput.value = student.fullname;
                    emailInput.value = student.email;
                    ageInput.value = student.age;
                    studentIdInput.value = student.id;
                };

                //Botón para editar con estilos de la w3schools: carga los datos del estudiante al formulario y guarda su id.

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deleteStudent(student.id);

                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                tr.appendChild(tdName);
                tr.appendChild(tdEmail);
                tr.appendChild(tdAge);
                tr.appendChild(tdActions);

                studentTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener estudiantes:", err);
        }
    }

    // Eliminar estudiante
    async function deleteStudent(id) 
    {
        if (!confirm("¿Seguro que querés borrar este estudiante?")) return;

        try 
        {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchStudents();
            } else {
                alert("Error al borrar");
            }
        } catch (err) {
            console.error(err);
        }
    }
});

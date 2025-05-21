
// Definir la URL base según el tipo
const API_URL = `../backend/server.php/subjects`;

/**
 * Define la URL del backend que se utilizará en todas las llamadas `fetch`.
 */

document.addEventListener('DOMContentLoaded', () => 
{
    /**
     * Espera a que el contenido HTML esté completamente cargado antes de ejecutar el resto del código JavaScript.
     */
    const subjectForm = document.getElementById('subjectForm');
    const subjectTableBody = document.getElementById('subjectTableBody');
    const subnameInput = document.getElementById('subname');   
    const subjectIdInput = document.getElementById('subjectId');


    // Leer todos los estudiantes al cargar
    fetchSubjects();

    // Formulario: Crear o actualizar estudiante
    subjectForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // El preventDefault evita que se recarge la pagina al enviar el formulario

        const formData = {
            subname: subnameInput.value,
        };

        // Crea un objeto con la informacion introducida

        const id = subjectIdInput.value;
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
                subjectForm.reset();
                subjectIdInput.value = '';
                await fetchSubjects();
            } else {
                alert("Error al guardar");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener materias y renderizar tabla
    async function fetchSubjects() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const subjects = await res.json();

            //Convierte la respuesta JSON a un array de estudiantes

            //Limpiar tabla de forma segura.
            subjectTableBody.replaceChildren();
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //studentTableBody.innerHTML = "";

            subjects.forEach(subject => {
                const tr = document.createElement('tr');

                const tdSubname = document.createElement('td');
                tdSubname.textContent = subject.name;

                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-light-green', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    subnameInput.value = subject.name;
                    subjectIdInput.value = subject.id;
                };

                //Botón para editar con estilos de la w3schools: carga los datos del estudiante al formulario y guarda su id.

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deleteSubject(subject.id);

                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                tr.appendChild(tdSubname);
                tr.appendChild(tdActions);

                subjectTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener materias:", err);
        }
    }

    // Eliminar estudiante
    async function deleteSubject(id) 
    {
        if (!confirm("¿Seguro que querés borrar esta materia?")) return;

        try 
        {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchSubjects();
            } else {
                alert("Error al borrar");
            }
        } catch (err) {
            console.error(err);
        }
    }
});
